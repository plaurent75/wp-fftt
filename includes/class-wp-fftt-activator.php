<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/includes
 * @author     Patrice LAURENT <laurent.patrice@gmail.com>
 */
class Wp_Fftt_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$wpfftt_slug = Wp_Fftt_Activator::create_main_page();
		if($wpfftt_slug) {
			$options = get_option( 'wp_fftt_settings' );
				$options['wp_fftt_slug'] = $wpfftt_slug;
				update_option( 'wp_fftt_settings', $options );
		}
		flush_rewrite_rules();
	}

	public static function create_main_page(){
		if(false === Wp_Fftt_Activator::the_slug_exists('fftt-data')){
			$blog_page_title = __('FFTT Data', 'wp-fftt');
			$blog_page_check = get_page_by_title($blog_page_title);
			$blog_page = array(
				'post_type' => 'page',
				'post_title' => $blog_page_title,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_slug' => 'fftt-data'
			);
			return wp_insert_post($blog_page);
		}
		return false;
	}

	public static function the_slug_exists($post_name) {
		global $wpdb;
		if($wpdb->get_row("SELECT post_name FROM $wpdb->posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
			return true;
		} else {
			return false;
		}
	}

}
