<?php
if(  get_query_var('wp_fftt_player') && is_numeric(get_query_var('wp_fftt_player')) )  {
	$player = $this->getPlayer( get_query_var( 'wp_fftt_player' ) );
	include_once 'wp-fftt-public-joueur.php';
	include_once 'wp-fftt-public-legal.php';
}