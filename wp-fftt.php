<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.patricelaurent.net
 * @since             1.0.0
 * @package           Wp_Fftt
 *
 * @wordpress-plugin
 * Plugin Name:       Wp FFTT
 * Plugin URI:        https://www.patricelaurent.net/portfolio/plugin/wp-fftt/
 * Description:       Display table tennis data from the official FFTT Website.
 * Version:           1.0.0
 * Author:            Patrice LAURENT
 * Author URI:        https://www.patricelaurent.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-fftt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-fftt-activator.php
 */
function activate_wp_fftt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-fftt-activator.php';
	Wp_Fftt_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-fftt-deactivator.php
 */
function deactivate_wp_fftt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-fftt-deactivator.php';
	Wp_Fftt_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_fftt' );
register_deactivation_hook( __FILE__, 'deactivate_wp_fftt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-fftt.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_fftt() {

	$plugin = new Wp_Fftt();
	$plugin->run();

}
run_wp_fftt();
