<?php

class Wp_Fftt_Teams extends WP_Widget{
	/**
	 * @var string $appId ID de l'application fourni par la FFTT (ex: AM001)
	 */
	protected $appId;

	/**
	 * @var string $appKey Mot de passe fourni par la FFTT
	 */
	protected $appKey;
	protected $options;

	protected $cache;

	protected $api;
	protected $wpfftt_slug;
	protected $wpfftt_css;
	protected $wpfftt_css_prefix;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'wp_fftt_teams',
			'description' => __('Display teams for a club', 'wp-fftt'),
		);
		parent::__construct( 'wp_fftt_teams', 'WPFFTT - '.__('Club Teams', 'wp-fftt'), $widget_ops );
		$this->options = get_option( 'wp_fftt_settings' );
		$this->cache = new CacheService();
		if (empty($_SESSION['serial'])) {
			$_SESSION['serial'] = ffttAPI::generateSerial();
		}
		$this->appId = isset($this->options['wp_fftt_login']) ? $this->options['wp_fftt_login'] : false;
		$this->appKey =  isset($this->options['wp_fftt_password']) ? $this->options['wp_fftt_password'] : false;
		$this->api = new ffttAPI($this->appId, $this->appKey, $this->cache);
		$this->api->setSerial($_SESSION['serial']);
		$this->api->initialization();
		$this->wpfftt_slug = isset($this->options['wp_fftt_slug']) ?  $this->options['wp_fftt_slug']  : false;
$this->wpfftt_css = ( isset($this->options['wp_fftt_css']) && 'false' === $this->options['wp_fftt_css'] ) ? false : true;
		$this->wpfftt_css_prefix = true === $this->wpfftt_css ? '' : 'wpfftt-';
			}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		if ( ! empty( $instance['numero_club'] ) && is_numeric($instance['numero_club']) ) {
			$club_teams =  $this->api->getEquipesByClub( $instance['numero_club'] );
			?>
			<table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-striped">
				<tbody>
				<?php
				$t = 1;
				foreach ($club_teams as $team){
					$libelle_division = isset( $team['libdivision'] ) && !is_array($team['libdivision']) ? $team['libdivision'] : false;
					$idpoule = isset( $team['idpoule'] ) && !is_array($team['idpoule']) ? $team['idpoule'] : false;
					$iddiv = isset( $team['iddiv'] ) && !is_array($team['iddiv']) ? $team['iddiv'] : false;
					$ranks = $this->api->getPouleClassement( $iddiv, $idpoule );
					$clt = 0;
					foreach ($ranks as $rank){
						if($instance['numero_club'] === $rank['numero']) {
							$clt = $rank['clt'] == '1' ? $rank['clt'] .'er' : $rank['clt'].'ème';
							break;
						}
					}
					?>
					<tr>
						<td>
							<?php //echo preg_replace('/ - Phase [0-9]/','',$libelle_equipe) ?>
							<?php _e('Team', 'wp-fftt') ?> <?php echo $t ?>
						</td>
						<td><?php echo preg_replace('/ Poule [A-Z]/','',$libelle_division) ?></td>
						<td><a href="<?php echo Wp_Fftt_Public::get_rank_link_static($iddiv,$idpoule,$this->wpfftt_slug) ?>" class="<?php echo $this->wpfftt_css_prefix ?>card-link"><?php echo $clt ?></a></td>
					</tr>

				<?php $t++;
				} ?>
				</tbody>
			</table>
			<?php
		}
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Teams', 'wp-fftt' );
		$numclub = ! empty( $instance['numero_club'] ) ? $instance['numero_club'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title :', 'wp-fftt' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'numero_club' ) ); ?>"><?php esc_attr_e( 'Club Number', 'wp-fftt' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'numero_club' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'numero_club' ) ); ?>" type="text" value="<?php echo esc_attr( $numclub ); ?>">
		</p>
		<?php
	}
	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['numero_club'] = ( ! empty( $new_instance['numero_club'] ) ) ? strip_tags( $new_instance['numero_club'] ) : '';

		return $instance;
	}
}
