<?php
if(  get_query_var('wp_fftt_club_teams') && is_numeric(get_query_var('wp_fftt_club_teams')) )  {
	$club_teams = $this->getTeamsByClub( get_query_var( 'wp_fftt_club_teams' ) );
	$club_detail = $this->getClubDetails( get_query_var( 'wp_fftt_club_teams' ) );
	include_once 'wp-fftt-public-club.php';
	include_once 'wp-fftt-public-teams.php';
	include_once 'wp-fftt-public-legal.php';
}