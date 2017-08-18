<?php
if(  get_query_var('wp_fftt_results') && is_numeric(get_query_var('wp_fftt_results')) )  {
	$results = $this->api->getPouleRencontres( get_query_var( 'wp_fftt_division' ),get_query_var( 'wp_fftt_poule' ) );
	include_once 'wp-fftt-public-results.php';
	include_once 'wp-fftt-public-legal.php';
}