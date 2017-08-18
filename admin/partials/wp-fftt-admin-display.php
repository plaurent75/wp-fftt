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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

    <form action='options.php' method='post'>

        <h2>Wp FFTT</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

    </form>
