<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/admin/partials
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// check user capabilities
if (!current_user_can('manage_options')) {
	return;
}

/**
 * Club Cache = cleanClub($numero)
 * Joueur Cache = cleanJoueur($licence)
 */
?>
	<h2>Wp FFTT</h2>
	<form method="post">
		<p><label><input value="none" name="wpfftt_cache" type="radio" checked /><?php _e('Nothing', 'wp-fftt') ?></label></p>
		<p><label><input value="club" name="wpfftt_cache" type="radio" /><?php _e('Club Cache', 'wp-fftt') ?></label></p>
		<!--p><label><input value="joueur" name="wpfftt_cache" type="radio" /><?php _e('Player Cache', 'wp-fftt') ?></label></p-->
		<input type="hidden" name="action" value="wpfftt_cache_clean">
		<?php submit_button(__('Clean Cache', 'wp-fftt')) ?>
	</form>

<?php
if(isset($_POST)) {
	if (array_key_exists('action', $_POST) && 'wpfftt_cache_clean' === $_POST['action']) {
		$type_cache = isset($_POST['wpfftt_cache']) ? $_POST['wpfftt_cache'] : 'none';
		switch ($type_cache) {
			case 'none':
				# code...
				break;

			case 'club':
				$this->api->cleanClub($this->club_id);
				break;

			case 'joueur':
				# code...
				break;

			default:
				# code...
				break;
		}
		//$this->api->cleanClub($numero);
	}
}
