<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/public
 */

class Wp_Fftt_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * @array array $options options settings
	 */
	private $options;


	/**
	 * @var object $api
	 */
	private $api;

	/**
	 * @var
	 */
	public $wpfftt_slug;

	public $wpfftt_css;

	public $fftt_website = 'http://www.fftt.com/';

	public $dev_website = 'https://www.patricelaurent.net';


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $options, $api, $wpfftt_slug ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = $options;

		$this->appId = isset($this->options['wp_fftt_login']) ? $this->options['wp_fftt_login'] : false;
		$this->appKey =  isset($this->options['wp_fftt_password']) ? $this->options['wp_fftt_password'] : false;
		$this->club_id =  isset($this->options['wp_fftt_club_id']) ? $this->options['wp_fftt_club_id'] : false;
		$this->department = isset($this->options['wp_fftt_department']) ? $this->options['wp_fftt_department'] : false;
		$this->api_map = isset($this->options['wp_fftt_api_map']) ? $this->options['wp_fftt_api_map'] : false;
		$this->api =  $api;
		$this->wpfftt_slug = $wpfftt_slug;
		$this->wpfftt_css = ( isset($this->options['wp_fftt_css']) && 'false' === $this->options['wp_fftt_css'] ) ? false : true;
		$this->wpfftt_css_prefix = true === $this->wpfftt_css ? '' : 'wpfftt-';
		add_shortcode( 'fftt_club', [$this,'shortcode_club'] );
		add_shortcode( 'fftt_teams', [$this,'shortcode_teams'] );
		add_shortcode( 'fftt_players_club', [$this,'shortcode_players_club'] );
		add_shortcode( 'fftt_clubs_departement', [$this,'shortcode_clubs_departement'] );
		add_shortcode( 'fftt_player', [$this,'shortcode_joueur'] );
		add_shortcode( 'fftt_rank', [$this,'shortcode_classement'] );


	}
	public function add_body_class($classes){
		if($this->is_wpfftt_page()) $classes[] = 'wpfftt-page-content';
		if($this->is_wpfftt_content()) $classes[] = 'wpfftt-shortcode';
		return $classes;
	}

	public function used_shortcode(){
		$shortcodes = [
			'fftt_club',
			'fftt_teams',
			'fftt_players_club',
			'fftt_clubs_departement',
			'fftt_player',
			'fftt_rank',
		];
		return $shortcodes;
	}
	public function is_wpfftt_content($shortcodes = null){
		global $post;
		if( is_a( $post, 'WP_Post' )){
			if(is_null($shortcodes)) {
				$shortcodes = $this->used_shortcode();
			}
			if($this->multi_has_shortcode($post->post_content, $shortcodes)) return true;
		}

		return false;
	}

	public function multi_has_shortcode( $content, $tags ) {
		if( is_array( $tags ) ) {
			foreach ( $tags as $tag ) {
				preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
				if ( empty( $matches ) )
					return false;
				foreach ( $matches as $shortcode ) {
					if ( $tag === $shortcode[2] )
						return true;
				}
			}
		} else {
			if ( shortcode_exists( $tags ) ) {
				preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
				if ( empty( $matches ) )
					return false;
				foreach ( $matches as $shortcode ) {
					if ( $tags === $shortcode[2] )
						return true;
				}
			}
		}
		return false;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if(false === $this->wpfftt_css) :
		if($this->is_wpfftt_content()  || $this->is_wpfftt_page() || $this->is_using_widget() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-fftt-public.css', array(), $this->version, 'all' );
			//wp_enqueue_style( 'bs4_wpfftt', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');
		}
		endif;
		if($this->is_wpfftt_content(['fftt_players_club']) || get_query_var( 'wp_fftt_players' )) {
			wp_enqueue_style( 'datatables_wpfftt', plugin_dir_url( __FILE__ ) . 'css/datatables.min.css', array(), $this->version, 'all');
		}
		if($this->is_wpfftt_content(['fftt_player']) || get_query_var( 'wp_fftt_player' )) {
			wp_enqueue_style( 'chartist-css', plugin_dir_url( __FILE__ ) . 'js/chartist-js/chartist.min.css', array(), $this->version, 'all');
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $wp_query;
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-fftt-public.js', array( 'jquery' ), $this->version, false );
		if(get_query_var( 'wp_fftt_results' )) {
			wp_localize_script( $this->plugin_name,
				'wpffttajaxmatch',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wp_fftt_nonce' => wp_create_nonce( 'wpfftt_ajax_detail_party_nonce' ),
					'wp_fftt_division' => get_query_var('wp_fftt_division'),
					'wp_fftt_poule' => get_query_var('wp_fftt_poule'),
					'wp_fftt_prefix' => $this->wpfftt_css_prefix,
				) );
		}
		if($this->is_wpfftt_content(['fftt_players_club']) || get_query_var( 'wp_fftt_players' )) {
			wp_enqueue_script( 'datatables_wpfftt_js', plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array('jquery'), $this->version, false);
		}
		if($this->is_wpfftt_content(['fftt_player']) || get_query_var( 'wp_fftt_player' )) {
			wp_enqueue_script( 'chartist-js', plugin_dir_url( __FILE__ ) . 'js/chartist-js/chartist.min.js', array('jquery'), $this->version, false);
		}

	}

	public function var_debug($var){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
	}

	public function getClubDetails($club_id = false){
		if(false === $club_id) $club_id = isset($_GET['clubId']) ? (int) $_GET['clubId'] : $this->club_id;
		$club_detail = $this->api->getClub( $club_id );
		return $club_detail;
	}

	public function getTeamsByClub($club_id = false){
		if(false === $club_id) $club_id = isset($_GET['clubId']) ? (int) $_GET['clubId'] : $this->club_id;
		$club_teams = $this->api->getEquipesByClub( $club_id );
		return $club_teams;
	}

	public function getPlayersByClub($club_id = false){
		if(false === $club_id) $club_id = isset($_GET['clubId']) ? (int) $_GET['clubId'] : $this->club_id;
		$club_players = $this->api->getLicencesByClub( $club_id );
		return $club_players;
	}

	public function getClubsByDepartement($department = 89){
		if(!$department) $department = isset($_GET['department']) ? sanitize_text_field($_GET['department']) : 89;
		$clubs_department = $this->api->getClubsByDepartement( $department );
		return $clubs_department;
	}

	public function getPlayer($licence){
		if(!$licence) $licence = isset($_GET['licence']) ? (int) $_GET['licence'] : 588400;
		$player = $this->api->getJoueur( $licence );
		return $player;
	}

	public function getClassementPoule($division, $poule = null){
		$classement = $this->api->getPouleClassement( $division, $poule );
		usort($classement,[$this,'sortRank']);
		return $classement;
	}
	public function sortRank($item1,$item2) {
		if ($item1['clt'] == $item2['clt']) return 0;
		return ($item1['clt'] > $item2['clt']) ? 1 : -1;
	}


	public function get_team_results( $team_id){
		//$res = $this->api->getPouleRencontres($division);
		//$this->var_debug($team_id);
	}
	public function shortcode_club($atts){
		// Attributes
		$atts = shortcode_atts(
			array(
				'club_id' => $this->club_id,
				'show_map' => '0',
				'show_link' => '1'
			),
			$atts,
			'fftt_club'
		);
		$club_id = $atts['club_id'];
		$show_map = $atts['show_map'];
		$club_detail = $this->getClubDetails($club_id);
		$footer_card = ($atts['club_id']) == 1 ? true : false;
		ob_start();
		include_once 'partials/wp-fftt-public-club.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function shortcode_teams($atts){
		// Attributes
		$atts = shortcode_atts(
			array(
				'club_id' => $this->club_id,
			),
			$atts,
			'fftt_teams'
		);
		$club_id = $atts['club_id'];
		$club_teams = $this->api->getEquipesByClub( $club_id );
		ob_start();
		include_once 'partials/wp-fftt-public-teams.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function shortcode_players_club($atts){
		// Attributes
		$atts = shortcode_atts(
			array(
				'club_id' => $this->club_id,
			),
			$atts,
			'fftt_players_club'
		);
		$club_players = $this->getPlayersByClub($atts['club_id']);
		ob_start();
		include_once 'partials/wp-fftt-public-club-licence.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function shortcode_clubs_departement($atts){
		// Attributes
		$atts = shortcode_atts(
			array(
				'department' => $this->department,
				'show_map_department' => '0',
			),
			$atts
		);
		$show_map_department = $atts['show_map_department'];
		$department = $atts['department'];
		$clubs_departement = $this->getClubsByDepartement($department );
		ob_start();
				include_once 'partials/wp-fftt-public-clubs-departement.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function shortcode_joueur($atts){
		// Attributes
		$atts = shortcode_atts(
			array(
				'licence' => 588400,
			),
			$atts,
			'fftt_player'
		);
		$player = $this->getPlayer($atts['licence']);
		ob_start();
		include_once 'partials/wp-fftt-public-joueur.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;

	}
	public function shortcode_classement($atts){
		// Attributes
		$atts = shortcode_atts(
			array(
				'fftt_division' => 3797,
				'fftt_poule' => 6867,
			),
			$atts,
			'fftt_rank'
		);
		$rank = $this->getClassementPoule( $atts['fftt_division'], $atts['fftt_poule'] );
		ob_start();
		include_once 'partials/wp-fftt-public-rank.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;

	}

	public function get_player_link($licence){
		return get_permalink($this->wpfftt_slug) ._x('player', 'slug', 'wp-fftt').'/'.$licence;
	}

	public function get_rank_link($iddiv, $idpoule = null){
		$id_poule = isset($idpoule) ? '/'.$idpoule : null;
		$endpoint = $iddiv.$id_poule;
		return get_permalink($this->wpfftt_slug)._x('rank', 'slug', 'wp-fftt').'/'.$endpoint ;
	}
	public static function get_rank_link_static($iddiv, $idpoule = null, $wpfftt_slug){
		$id_poule = isset($idpoule) ? '/'.$idpoule : null;
		$endpoint = $iddiv.$id_poule;
		return get_permalink($wpfftt_slug)._x('rank', 'slug', 'wp-fftt').'/'.$endpoint ;
	}

	public function get_club_link($club_id){
		return get_permalink($this->wpfftt_slug) ._x('club', 'slug', 'wp-fftt').'/'.$club_id;
	}
	public function get_club_department_link($dep_id = null){
		return get_permalink($this->wpfftt_slug) ._x('department', 'slug', 'wp-fftt').'/'.$dep_id;
	}

	public function getDepartementCodeFromCP($cp) {
		$cp = str_pad(str_replace(' ', '', $cp), 5, '0', STR_PAD_LEFT);
		$dep_code = substr($cp, 0, 2);
		if($dep_code == '20') {
			if((int)$cp < 20200 || in_array((int)$cp, array(20223,20900))){
				$dep_code = '2A';
			}else {
				$dep_code = '2B';
			}
		}

		if((int)$dep_code > 95) {
			$dep_code = substr($cp, 0, 3);
		}

		return $dep_code;
	}

	public function get_public_url(){
		return plugin_dir_url( __FILE__ );
	}
	public function get_club_players_link($numero){
		return get_permalink($this->wpfftt_slug) ._x('players', 'slug', 'wp-fftt').'/'.$numero;
	}
	public function get_team_link($numero){
		return get_permalink($this->wpfftt_slug) ._x('team', 'slug', 'wp-fftt').'/'.$numero;
	}
	public function get_club_teams_link($numero){
		return get_permalink($this->wpfftt_slug) ._x('teams', 'slug', 'wp-fftt').'/'.$numero;
	}

	public function get_results_match_link($iddiv, $idpoule = null){
		$id_poule = isset($idpoule) ? '/'.$idpoule : null;
		$endpoint = $iddiv.$id_poule;
		return get_permalink($this->wpfftt_slug) ._x('results', 'slug', 'wp-fftt').'/'.$endpoint;

	}

	public function get_club_name($numero){

		$club_detail = $this->getClubDetails($numero);
		$title = isset($club_detail['nom']) ? $club_detail['nom'] : false;
		return $title;
	}

	public function get_division_poule_name($club_id = false, $idpoule = false){

		$title = null;
		if($idpoule & $club_id) {
			$teams = $this->api->getEquipesByClub( $club_id );
			foreach ($teams as $t){
				if($idpoule === $t['idpoule']) {
					return $t['libdivision'];
					break;
				}
			}
		}
		return $title;
	}


	public function rewrite() {
		$page = str_replace( home_url().'/', "", get_permalink($this->wpfftt_slug) );
		$player = _x('player', 'slug', 'wp-fftt');
		$players = _x('players', 'slug', 'wp-fftt');
		$team = _x('team', 'slug', 'wp-fftt');
		$teams = _x('teams', 'slug', 'wp-fftt');
		$club = _x('club', 'slug', 'wp-fftt');
		$rank = _x('rank', 'slug', 'wp-fftt');
		$results = _x('results', 'slug', 'wp-fftt');
		//$poule = _x('poule', 'slug', 'wp-fftt');
		//$division = _x('division', 'slug', 'wp-fftt');
		$department = _x('department', 'slug', 'wp-fftt');
		add_rewrite_endpoint( $player, EP_PERMALINK | EP_PAGES , 'wp_fftt_player');
		add_rewrite_endpoint( $club, EP_PERMALINK | EP_PAGES , 'wp_fftt_club');
		add_rewrite_endpoint( $players, EP_PERMALINK | EP_PAGES , 'wp_fftt_players');
		add_rewrite_endpoint( $department, EP_PERMALINK | EP_PAGES , 'wp_fftt_club_department');
		add_rewrite_endpoint( $teams, EP_PERMALINK | EP_PAGES , 'wp_fftt_club_teams');
		add_rewrite_endpoint( $team, EP_PERMALINK | EP_PAGES , 'wp_fftt_team');
		add_rewrite_endpoint( $rank, EP_PERMALINK | EP_PAGES , 'wp_fftt_rank');
		add_rewrite_endpoint( $results, EP_PERMALINK | EP_PAGES , 'wp_fftt_rank');
		add_rewrite_rule(
			'(.?.+?)/'.$rank.'/([0-9]{1,})/([0-9]{1,})/?',
		'index.php?pagename=$matches[1]&wp_fftt_division=$matches[2]&wp_fftt_poule=$matches[3]&wp_fftt_rank=1',
			'top'
	);
		add_rewrite_rule(
			'(.?.+?)/'.$results.'/([0-9]{1,})/([0-9]{1,})/?',
		'index.php?pagename=$matches[1]&wp_fftt_division=$matches[2]&wp_fftt_poule=$matches[3]&wp_fftt_results=1',
			'top'
	);
	}

	public function query_vars($vars){
		$vars[] = 'wp_fftt_player';
		$vars[] = 'wp_fftt_players';
		$vars[] = 'wp_fftt_club';
		$vars[] = 'wp_fftt_club_department';
		$vars[] = 'wp_fftt_club_teams';
		$vars[] = 'wp_fftt_rank';
		$vars[] = 'wp_fftt_poule';
		$vars[] = 'wp_fftt_division';
		$vars[] = 'wp_fftt_team';
		$vars[] = 'wp_fftt_results';
		$vars[] = 'wp_fftt_rencontre';
		return $vars;
	}
	public function is_wpfftt_page(){
		if (is_array($this->options) && array_key_exists('wp_fftt_slug',$this->options) && is_page($this->options['wp_fftt_slug']) ) return true;
		else return false;
	}

	public function insert_content($content){
		$output ='';
		if ( get_query_var('wp_fftt_players') ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_players_template','partials/wp-fftt-single-club-players.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif (get_query_var( 'wp_fftt_club_department' ) ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_department_template','partials/wp-fftt-single-club-department.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif ( get_query_var('wp_fftt_player') ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_player_template','partials/wp-fftt-single-player.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif ( get_query_var('wp_fftt_club') ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_club_template','partials/wp-fftt-single-club.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif ( get_query_var('wp_fftt_club_teams') ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_club_teams_template','partials/wp-fftt-single-club-teams.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif ( get_query_var('wp_fftt_rank') ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_team_rank','partials/wp-fftt-single-poule.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif ( get_query_var('wp_fftt_team') ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_team_template','partials/wp-fftt-single-team.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif ( get_query_var('wp_fftt_results') ) {
			ob_start();
			$templateFile = apply_filters('wpfftt_results','partials/wp-fftt-single-results.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}elseif ($this->is_wpfftt_page()){
			ob_start();
			$templateFile = apply_filters('wpfftt_single_template','partials/wp-fftt-single.php');
			include_once $templateFile;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		if($this->is_wpfftt_content()){
			$output = $content;
			ob_start();
			include_once 'partials/wp-fftt-public-legal.php';
			$output .= ob_get_contents();
			ob_end_clean();
			return $output;
		}

		return $content;

	}

	public function get_score_from_ajax(){
		/* First, check nonce */
		check_ajax_referer( 'wpfftt_ajax_detail_party_nonce', 'wpajx_fftt_nonce' );

		$divid = $_GET['wpajx_fftt_division'];
		$pouleid = $_GET['wpajx_fftt_poule'];
		$rencid = $_GET['wpajx_fftt_rencontre'];
		$lien = $_GET['wpajx_fftt_lien'];

		include_once 'partials/wp-fftt-public-results-modal.php';

		wp_die();
	}

	public function set_page_title($title, $id = null){

		/**
		 * We are in a FFTT page and in front end part
		 */
		if(!is_admin() && $id == $this->wpfftt_slug && in_the_loop() ) {
			if ( get_query_var( 'wp_fftt_club_department' ) && is_string( get_query_var( 'wp_fftt_club_department' ) ) ) {
				$title = sprintf(__('Clubs of the %s department', 'wp-fftt'), $this->get_dept_name(get_query_var( 'wp_fftt_club_department' )));
			}
			if ( get_query_var( 'wp_fftt_club' ) && is_numeric( get_query_var( 'wp_fftt_club' ) ) ) {
				$club_name = $this->get_club_name( get_query_var( 'wp_fftt_club' ) );
				//$new_title = $title.' &rsaquo; '.$club_name;
				$title     = $club_name ? $club_name : $title;
			}
			if ( get_query_var( 'wp_fftt_players' ) && is_numeric( get_query_var( 'wp_fftt_players' ) ) ) {
				$club_name = $this->get_club_name( get_query_var( 'wp_fftt_players' ) );
				//$new_title = $title.' &rsaquo; '.$club_name;
				$title     = $club_name ? sprintf(__('%s Licencees', 'wp-fftt'), $club_name) : $title;
			}
			if ( get_query_var( 'wp_fftt_club_teams' ) && is_numeric( get_query_var( 'wp_fftt_club_teams' ) ) ) {
				$club_name = $this->get_club_name( get_query_var( 'wp_fftt_club_teams' ) );
				//$new_title = $title.' &rsaquo; '.$club_name;
				$title     = $club_name ? sprintf(__('%s Teams', 'wp-fftt'), $club_name) : $title;
			}
			if ( get_query_var( 'wp_fftt_results' ) && is_numeric( get_query_var( 'wp_fftt_results' ) ) ) {
				$division = get_query_var( 'wp_fftt_division' );
				$poule = get_query_var( 'wp_fftt_poule' );
					$results = $this->api->getPouleClassement( get_query_var( 'wp_fftt_division' ),get_query_var( 'wp_fftt_poule' ) );
					$poule_name = $this->get_division_poule_name($results[0]['numero'], $poule);
				$the_title = $poule_name;
				//$new_title = $title.' &rsaquo; '.$club_name;
				$title     = $the_title ? sprintf(__('%s Results', 'wp-fftt'), $the_title) : $title;
			}
		}
		return $title;
	}
	public function get_widgets(){
		$widgets = [
			'Wp_Fftt_Teams' => 'wp_fftt_teams',
			'Wp_Fftt_Teams_Results' => 'wp_fftt_teams_results',
			'Wp_Fftt_Player_Ranking' => 'wp_fftt_player_ranking'
		];
		return $widgets;
	}
	public function is_using_widget(){
		foreach ($this->get_widgets() as $widget => $idbase){
			if ( is_active_widget( false, false, $idbase, true ) ) return true;

		}

		return false;
	}
	public function widgets_init(){
		foreach ($this->get_widgets() as $widget => $idbase){
			register_widget( $widget );
		}
	}

	public function get_dept_name($cp =false)
	{
		$nom_dept = array (
			"01" => "Ain",
			"02" => "Aisne",
			"03" => "Allier",
			"04" => "Alpes-de-Haute Provence",
			"05" => "Hautes-Alpes",
			"06" => "Alpes Maritimes",
			"07" => "Ardèche",
			"08" => "Ardennes",
			"09" => "Ariège",
			"10" => "Aube",
			"11" => "Aude",
			"12" => "Aveyron",
			"13" => "Bouches-du-Rhône",
			"14" => "Calvados",
			"15" => "Cantal",
			"16" => "Charente",
			"17" => "Charente-Maritime",
			"18" => "Cher",
			"19" => "Corrèze",
			"98" => "Haute Corse",
			"99" => "Corse du sud",
			"21" => "Côte d'Or",
			"22" => "Côtes d'Armor",
			"23" => "Creuse",
			"24" => "Dordogne",
			"25" => "Doubs",
			"26" => "Drôme",
			"27" => "Eure",
			"28" => "Eure-et-Loire",
			"29" => "Finistère",
			"30" => "Gard",
			"31" => "Haute-Garonne",
			"32" => "Gers",
			"33" => "Gironde",
			"34" => "Hérault",
			"35" => "Ille-et-Vilaine",
			"36" => "Indre",
			"37" => "Indre-et-Loire",
			"38" => "Isère",
			"39" => "Jura",
			"40" => "Landes",
			"41" => "Loir-et-Cher",
			"42" => "Loire",
			"43" => "Haute-Loire",
			"44" => "Loire-Atlantique",
			"45" => "Loiret",
			"46" => "Lot",
			"47" => "Lot-et-Garonne",
			"48" => "Lozère",
			"49" => "Maine-et-Loire",
			"50" => "Manche",
			"51" => "Marne",
			"52" => "Haute-Marne",
			"53" => "Mayenne",
			"54" => "Meurthe-et-Moselle",
			"55" => "Meuse",
			"56" => "Morbihan",
			"57" => "Moselle",
			"58" => "Nièvre",
			"59" => "Nord",
			"60" => "Oise",
			"61" => "Orne",
			"62" => "Pas-de-Calais",
			"63" => "Puy-de-Dôme",
			"64" => "Pyrenées-Atlantiques",
			"65" => "Hautes-Pyrenées",
			"66" => "Pyrenées-Orientales",
			"67" => "Bas-Rhin",
			"68" => "Haut-Rhin",
			"69" => "Rhône",
			"70" => "Haute-Saône",
			"71" => "Saône-et-Loire",
			"72" => "Sarthe",
			"73" => "Savoie",
			"74" => "Haute-Savoie",
			"75" => "Paris",
			"76" => "Seine-Maritime",
			"77" => "Seine-et-Marne",
			"78" => "Yvelines",
			"79" => "Deux-Sèvres",
			"80" => "Somme",
			"81" => "Tarn",
			"82" => "Tarn-et-Garonne",
			"83" => "Var",
			"84" => "Vaucluse",
			"85" => "Vendée",
			"86" => "Vienne",
			"87" => "Haute-Vienne",
			"88" => "Vosges",
			"89" => "Yonne",
			"90" => "Territoire de Belfort",
			"91" => "Essonne",
			"92" => "Hauts-de-Seine",
			"93" => "Seine-Saint-Denis",
			"94" => "Val-de-Marne",
			"95" => "Val-d'Oise",
			"9A" => "Guadeloupe",
			"9B" => "Martinique",
			"9C" => "Guyane",
			"9D" => "Réunion",
			"9E" => "COMITE PROVINCIAL NORD",
			"9F" => "COMITE PROVINCIAL SUD",
			"9G" => "Mayotte",
			"9H" => "Tahiti",
			"9W" => "Wallis et Futuna",
		);

		if($cp) return $nom_dept[$cp];
		else return $nom_dept;
	}

	public function wpfftt_bcn_breadcrumb_url($bcn){
		//var_dump($bcn->breadcrumbs);
	}
}
