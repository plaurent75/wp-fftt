<?php
if(  get_query_var('wp_fftt_team') )  {
	$rank = $this->get_team_results( get_query_var( 'wp_fftt_team' ),get_query_var( 'wp_fftt_team' ) );
	//include_once 'wp-fftt-public-rank.php';
	include_once 'wp-fftt-public-legal.php';
}