<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/admin
 */


class Wp_Fftt_Admin {

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
	 * @int Default Club Number
	 */
	private $club_id;

	/**
     * options settings
     *
     *  @since    1.0.0
     * @access   private
	 * @array array $options
	 */
	private $options;

	/**
	 * @var
	 */
	private $wpfftt_slug;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
     * @param       array   $options Options settings
	 */
	public function __construct( $plugin_name, $version, $options,$wpfftt_slug ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = $options;
		$this->appId = isset($this->options['wp_fftt_login']) ? $this->options['wp_fftt_login'] : false;
		$this->appKey =  isset($this->options['wp_fftt_password']) ? $this->options['wp_fftt_password'] : false;
		$this->club_id =  isset($this->options['wp_fftt_club_id']) ? $this->options['wp_fftt_club_id'] : false;
		$this->department = isset($this->options['wp_fftt_department']) ? $this->options['wp_fftt_department'] : false;
		$this->api_map = isset($this->options['wp_fftt_api_map']) ? $this->options['wp_fftt_api_map'] : false;
		$this->wpfftt_slug = $wpfftt_slug;
		//set default to false, to not disable css
		$this->wpfftt_css = ( isset($this->options['wp_fftt_css']) && 'true' === $this->options['wp_fftt_css'] ) ? true : false;


	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-fftt-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-fftt-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function wpfftt_admin_head(){
		?>
		<!-- TinyMCE Shortcode Plugin -->
		<script type='text/javascript'>
          var wpfftt_plugin = {
            'club_id': '<?php echo $this->club_id; ?>',
            'department': '<?php echo $this->department; ?>',
          };
		</script>
		<!-- TinyMCE Shortcode Plugin -->
		<?php
	}

	/**
	 * Things to do on init hook
	 */
	public function init_action(){
		//Abort early if the user will never see TinyMCE
		if (  current_user_can('edit_posts') &&  current_user_can('edit_pages') ){
			//Add a callback to register  tinymce plugin
			add_filter("mce_external_plugins", [$this,"_register_tinymce_plugin"]);
			// Add a callback to add our button to the TinyMCE toolbar
			add_filter('mce_buttons', [$this,'_add_tinymce_button']);
		}
	}

	public function _register_tinymce_plugin($plugin_array) {
		$plugin_array['wpfftt_button'] = plugin_dir_url( __FILE__ ) . 'js/wp-fftt-admin-button.js';
		return $plugin_array;
	}

	public function _add_tinymce_button($buttons) {
		//Add the button ID to the $button array
		$buttons[] = "wpfftt_button";
		return $buttons;
	}

	public function wp_fftt_add_admin_menu(  ) {

		add_menu_page( 'Wp FFTT', 'Wp FFTT', 'manage_options', 'wp-fftt-options', [$this,'wp_fftt_options_page'], plugin_dir_url( __FILE__ ) . 'js/ping-pong.png' );
add_submenu_page(
        'wp-fftt-options',
        __( 'Wp FFTT admin', 'wp-fftt' ),
        __( 'Wp FFTT admin', 'wp-fftt' ),
        'manage_options',
        'wp-fftt-options',
        [$this,'wp_fftt_options_page']
    );
	}

	public function wp_fftt_settings_init(  ) {

		register_setting( 'pluginPage', 'wp_fftt_settings', [$this, 'sanitize_settings'] );

		add_settings_section(
			'wp_fftt_pluginPage_section',
			__( 'Settings', 'wp-fftt' ),
			[$this, 'wp_fftt_settings_section_callback'],
			'pluginPage'
		);

		add_settings_field(
			'wp_fftt_login',
			__( 'Login (required)', 'wp-fftt' ),
			[$this,'wp_fftt_login_render'],
			'pluginPage',
			'wp_fftt_pluginPage_section'
		);

		add_settings_field(
			'wp_fftt_password',
			__( 'Password (required)', 'wp-fftt' ),
			[$this,'wp_fftt_password_render'],
			'pluginPage',
			'wp_fftt_pluginPage_section'
		);

		add_settings_field(
			'wp_fftt_club_id',
			__( 'Club Number (optional)', 'wp-fftt' ),
			[$this,'wp_fftt_club_id_render'],
			'pluginPage',
			'wp_fftt_pluginPage_section'
		);

		add_settings_field(
			'wp_fftt_department',
			__( 'department (optional)', 'wp-fftt' ),
			[$this,'wp_fftt_department_render'],
			'pluginPage',
			'wp_fftt_pluginPage_section'
		);

		add_settings_field(
			'wp_fftt_slug', // id
			__( 'Where to display it ?', 'wp-fftt' ), // title
			[ $this, 'where_to_display_it_2_callback' ], // callback
			'pluginPage', // page
			'wp_fftt_pluginPage_section' // section
		);

		add_settings_field(
			'wp_fftt_api_map',
			__( 'Google Maps Api Key (optional)', 'wp-fftt' ),
			[$this,'wp_fftt_api_map_render'],
			'pluginPage',
			'wp_fftt_pluginPage_section'
		);

		add_settings_field(
			'wp_fftt_css',
			__( 'Disable Plugin CSS', 'wp-fftt' ),
			[$this,'wp_fftt_css_render'],
			'pluginPage',
			'wp_fftt_pluginPage_section'
		);
		if (delete_transient('wp_fftt_flush_rules')) flush_rewrite_rules();


	}


	public function wp_fftt_login_render(  ) {
		?>
        <input type='text' name='wp_fftt_settings[wp_fftt_login]' value='<?php echo $this->appId; ?>'>
		<?php

	}


	public function wp_fftt_password_render(  ) {

		?>
        <input type='password' name='wp_fftt_settings[wp_fftt_password]' value='<?php echo $this->appKey; ?>'>
		<?php

	}


	public function wp_fftt_club_id_render(  ) {
		?>
        <input type='number' pattern="\d+" name='wp_fftt_settings[wp_fftt_club_id]' value='<?php echo $this->club_id; ?>'>
		<?php

	}

	public function wp_fftt_department_render(  ) {
		?>
        <input type='text' name='wp_fftt_settings[wp_fftt_department]' value='<?php echo $this->department; ?>'>
		<?php

	}

	public function wp_fftt_api_map_render(  ) {
		?>
        <input type='text' name='wp_fftt_settings[wp_fftt_api_map]' value='<?php echo $this->api_map; ?>'>
		<?php

	}

	public function wp_fftt_css_render() {
			?>
		<input type="radio" name="wp_fftt_settings[wp_fftt_css]" value="true" <?php checked(true, $this->wpfftt_css, true); ?>> Yes
        <input type="radio" name="wp_fftt_settings[wp_fftt_css]" value="false" <?php checked(false, $this->wpfftt_css, true); ?>> No
		<?php
	}


	public function wp_fftt_settings_section_callback(  ) {

		 ?><p><?php
            _e( 'Login & password must be requested from the federation. You can <a href="http://www.fftt.com/site/mediatheque/autres-medias/api" target="_blank">claim your access here</a>', 'wp-fftt' );
    ?></p><p><?php
	_e( 'Plugin is unusable without access provided by the FFTT', 'wp-fftt' );
	?></p><?php

	}

	public function where_to_display_it_2_callback() {
		$current = isset( $this->options['wp_fftt_slug'] ) ? $this->options['wp_fftt_slug'] : 0;
		wp_dropdown_pages(
			array(
				'name'              => 'wp_fftt_settings[wp_fftt_slug]',
				'echo'              => 1,
				'show_option_none'  => __( '&mdash; Select &mdash;' ),
				'option_none_value' => '0',
				'selected'          => $current,
			)
		); ?>
		<?php if ( $current && $current > 0 ) { ?>
            <a target="_blank" href="<?php echo get_permalink( $current ) ?>"><?php _e( 'Show' ) ?></a>&nbsp;|
		<?php } ?>
        &nbsp;<a href="<?php echo admin_url( 'post-new.php?post_type=page' ) ?>"><?php _e( 'Add New Page' ) ?></a>

		<?php

	}

	/**
	 * @param array $input
	 *
	 * @return array
     * wp_fftt_login, wp_fftt_password, wp_fftt_club_id, wp_fftt_department, wp_fftt_slug, wp_fftt_api_map
	 */
	public function sanitize_settings($input){
		set_transient('wp_fftt_flush_rules', '');
	    foreach ($input as $key => $value){
	        $input[$key] = sanitize_text_field($value);
        }
	    return $input;
    }

	/**
     * trigger action when option is updated.
     * Flush the rewrite rul when changing the slug
	 * @param $old
	 * @param $new
	 */
    public function update_wpfftt_options_action($old, $new){
	    flush_rewrite_rules();
    }



	public function wp_fftt_options_page(  ) {

        include_once 'partials/wp-fftt-admin-display.php';

	}

	/**
	 * Register customize new nav menu item types.
	 * This will register WooCommerce account endpoints as a nav menu item type.
	 *
	 * @since  3.1.0
	 * @param  array $item_types Menu item types.
	 * @return array
	 */
	public function register_customize_nav_menu_item_types( $item_types  ) {
		$item_types[] = array(
			'title'      => __( 'Wp FFTT endpoints', 'wp-fftt' ),
			'type_label' => __( 'Wp FFTT endpoint', 'wp-fftt' ),
			'type'       => 'wpfftt_nav',
			'object'     => 'wpfftt_endpoint',
		);
		return $item_types;
	}

	/**
	 * Register account endpoints to customize nav menu items.
	 *
	 * @since  3.1.0
	 * @param  array   $items  List of nav menu items.
	 * @param  string  $type   Nav menu type.
	 * @param  string  $object Nav menu object.
	 * @param  integer $page   Page number.
	 * @return array
	 */
	public function register_customize_nav_menu_items( $items = array(), $type = '', $object = '', $page = 0 ) {
		if ( 'wpfftt_endpoint' !== $object ) {
			return $items;
		}
		// Don't allow pagination since all items are loaded at once.
		if ( 0 < $page ) {
			return $items;
		}
		// Get items from account menu.
		$endpoints = $this->get_admin_menu_items();
		// Include missing lost password.
		foreach ( $endpoints as $endpoint => $title ) {
			$items[] = array(
				'id'         => $endpoint,
				'title'      => $title,
				'type_label' => __( 'Custom Link', 'wp-fftt' ),
				'url'        => esc_url_raw( $this->get_account_endpoint_url( $endpoint ) ),
			);
		}
		return $items;
	}

	public function get_admin_menu_items(){

		$items = array(
		    _x('club', 'slug', 'wp-fftt')          => __('Club', 'wp-fftt'),
		    _x('teams', 'slug', 'wp-fftt')       => __('Teams', 'wp-fftt'),
		    _x('players', 'slug', 'wp-fftt')    => __('Players', 'wp-fftt'),
		    _x('department', 'slug', 'wp-fftt') => __('Clubs of the department', 'wp-fftt')
		  );
		return $items;
	}
	public function get_account_endpoint_url($endpoint){
		$numero = $this->club_id;
		if(_x('department', 'slug', 'wp-fftt') === $endpoint) $numero = $this->department;
		return get_permalink($this->wpfftt_slug) .$endpoint.'/'.$numero;
	}
	public function add_nav_menu_meta_boxes() {
	        	add_meta_box(
	        		'wpfftt_endpoints_nav_link',
	        		__( 'Wp FFTT endpoints', 'wp-fftt' ),
	        		array( $this, 'nav_menu_link'),
	        		'nav-menus',
	        		'side',
	        		'low'
	        	);
	        }
	public function nav_menu_link() {
		$endpoints = $this->get_admin_menu_items();
		?>
		<div id="posttype-wpfftt-endpoints" class="posttypediv">
			<div id="tabs-panel-wpfftt-endpoints" class="tabs-panel tabs-panel-active">
				<ul id="wpfftt-endpoints-checklist" class="categorychecklist form-no-clear">
					<?php
					$i = -1;
					foreach ( $endpoints as $key => $value ) :
						?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $value ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="custom" />
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_html( $value ); ?>" />
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="<?php echo esc_url( $this->get_account_endpoint_url( $key ) ); ?>" />
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" />
						</li>
						<?php
						$i--;
					endforeach;
					?>
				</ul>
			</div>
			<p class="button-controls">
				<span class="list-controls">
					<a href="<?php echo admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-wpfftt-endpoints' ); ?>" class="select-all"><?php _e( 'Select all', 'wp-fftt' ); ?></a>
				</span>
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'wp-fftt' ); ?>" name="add-post-type-menu-item" id="submit-posttype-wpfftt-endpoints">
					<span class="spinner"></span>
				</span>
			</p>
		</div>
        <?php }


}
