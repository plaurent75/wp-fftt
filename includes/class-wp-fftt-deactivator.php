<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/includes
 * @author     Patrice LAURENT <laurent.patrice@gmail.com>
 */
class Wp_Fftt_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}
