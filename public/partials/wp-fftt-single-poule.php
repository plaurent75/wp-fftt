<?php
if(  get_query_var('wp_fftt_rank') )  {
	$rank = $this->getClassementPoule( get_query_var( 'wp_fftt_division' ),get_query_var( 'wp_fftt_poule' ) );
	$poules = $this->api->getPoules( get_query_var( 'wp_fftt_division' ),get_query_var( 'wp_fftt_poule' ) );
	include_once 'wp-fftt-public-rank.php';
	include_once 'wp-fftt-public-legal.php';
}