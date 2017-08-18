<?php
if(  get_query_var('wp_fftt_players') && is_numeric(get_query_var('wp_fftt_players')) )  {
	$club_players = $this->getPlayersByClub( get_query_var( 'wp_fftt_players' ) );
	$club_detail = $this->getClubDetails( get_query_var( 'wp_fftt_players' ) );
	include_once 'wp-fftt-public-club.php';
	include_once 'wp-fftt-public-club-licence.php';
	include_once 'wp-fftt-public-legal.php';
}