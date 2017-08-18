<?php
if(  get_query_var('wp_fftt_club_department') && is_numeric(get_query_var('wp_fftt_club_department')) )  {
	$clubs_departement = $this->getClubsByDepartement( get_query_var( 'wp_fftt_club_department' ) );
	include_once 'wp-fftt-public-clubs-departement.php';
	include_once 'wp-fftt-public-legal.php';
}else{
	$templateFile = apply_filters('wpfftt_department_map_template','wp-fftt-public-france.php');
	include_once $templateFile;
	include_once 'wp-fftt-public-france-table.php';
	include_once 'wp-fftt-public-legal.php';
}