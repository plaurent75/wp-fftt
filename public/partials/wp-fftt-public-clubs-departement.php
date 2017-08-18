<?php
/**
 * Provide a public-facing view for the clubs and clubs by department shortcode
 *
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/public/partials
 */
$footer_card = true;
if(is_array($clubs_departement) && count($clubs_departement)>0){
	foreach ($clubs_departement as $club){
		$club_detail = $this->getClubDetails($club['numero']);
		include "wp-fftt-public-club.php";
	}
}