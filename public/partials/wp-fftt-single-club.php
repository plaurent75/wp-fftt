<?php
if(  get_query_var('wp_fftt_club') && is_numeric(get_query_var('wp_fftt_club')) )  {
	$club_detail = $this->getClubDetails( get_query_var( 'wp_fftt_club' ) );
	$show_map = 1;
	include_once 'wp-fftt-public-club.php';
	include_once 'wp-fftt-public-legal.php';
}