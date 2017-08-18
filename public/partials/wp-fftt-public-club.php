<?php

/**
 * Provide a public-facing view for the club and club shortcode
 *
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/public/partials
 */

/**
 * @global $club_detail
 * @return array
 * string array['idclub']  id unique
 * string array['numero'] Public Club Number default to $this->club_id
 * array['nom'] string
 * array['nomsalle'] string
 * array['adressesalle1'] string
 * array['adressesalle2'] string
 * array['adressesalle3'] array
 * array['codepsalle'] string
 * array['villesalle'] string
 * array['web'] string
 * array['nomcor'] string
 * array['prenomcor'] string
 * array['mailcor'] string
 * array['telcor'] string
 * array['latitude'] array
 * array['longitude'] array
 * global $footer_card
 */
if($club_detail && is_array($club_detail)) {
$nom           = isset( $club_detail['nom'] ) && !is_array($club_detail['nom']) ? $club_detail['nom'] : false;
$numero        = isset( $club_detail['numero'] ) && !is_array($club_detail['numero']) ? $club_detail['numero'] : false;
$nomsallle     = isset( $club_detail['nomsalle'] ) && !is_array($club_detail['nomsalle']) ? $club_detail['nomsalle'] : false;
$adressesalle1 = isset( $club_detail['adressesalle1'] ) && !is_array($club_detail['adressesalle1']) ? $club_detail['adressesalle1'] : false;
$adressesalle2 = isset( $club_detail['adressesalle2'] ) && !is_array($club_detail['adressesalle2']) ? $club_detail['adressesalle2'] : false;
$adressesalle3 = count( $club_detail['adressesalle3'] ) > 0 ? $club_detail['adressesalle3'] : false;
$codepsalle    = isset( $club_detail['codepsalle'] ) && !is_array($club_detail['codepsalle']) ? $club_detail['codepsalle'] : false;
$villesalle    = isset( $club_detail['villesalle'] ) && !is_array($club_detail['villesalle']) ? $club_detail['villesalle'] : false;
$adresse_salle = $codepsalle . ' ' . $villesalle;
$web           = isset( $club_detail['web'] ) && !is_array($club_detail['web']) ? $club_detail['web'] : false;
$nomcor        = isset( $club_detail['nomcor'] ) && !is_array($club_detail['nomcor']) ? $club_detail['nomcor'] : false;
$prenomcor     = isset( $club_detail['prenomcor'] ) && !is_array($club_detail['prenomcor']) ? $club_detail['prenomcor'] : false;
$mailcor       = isset( $club_detail['mailcor'] ) && !is_array($club_detail['mailcor']) ? $club_detail['mailcor'] : false;
$telcor        = isset( $club_detail['telcor'] ) && !is_array($club_detail['telcor']) ? $club_detail['telcor'] : false;
$latitude      = count( $club_detail['latitude'] ) > 0 ? $club_detail['latitude'] : false;
$longitude     = count( $club_detail['longitude'] ) > 0 ? $club_detail['longitude'] : false;
$dep_id = isset( $club_detail['codepsalle'] ) && !is_array($club_detail['codepsalle']) ? $this->getDepartementCodeFromCP($club_detail['codepsalle']) : false;

$show_map      = isset( $show_map ) ? $show_map : 0;
	$footer_card = isset($footer_card) ? $footer_card : false;
?>
<div class="<?php echo $this->wpfftt_css_prefix ?>card <?php echo $this->wpfftt_css_prefix ?>mb-4">
    <div class="<?php echo $this->wpfftt_css_prefix ?>card-header">
	    <?php include 'wp-fftt-public-club-nav.php' ?>
    </div>
    <div class="<?php echo $this->wpfftt_css_prefix ?>card-block <?php echo $this->wpfftt_css_prefix ?>card-body">
        <h2 class="<?php echo $this->wpfftt_css_prefix ?>card-title"><?php echo $nom ?></h2>
        <p class="<?php echo $this->wpfftt_css_prefix ?>card-subtitle"><?php _e( 'Club Number', 'wp-fftt' ) ?>: <?php echo $numero ?></p>
        <div class="<?php echo $this->wpfftt_css_prefix ?>row <?php echo $this->wpfftt_css_prefix ?>card-block <?php echo $this->wpfftt_css_prefix ?>card-body">
            <div class="<?php echo $this->wpfftt_css_prefix ?>col-12 <?php echo $this->wpfftt_css_prefix ?>col-sm-6">
                <address>
					<?php if ( $nomsallle ) { ?><strong><?php echo $nomsallle ?></strong><br><?php } ?>
					<?php if ( $adressesalle1 ) { ?><?php echo $adressesalle1 ?><br><?php } ?>
					<?php if ( $adressesalle2 && $adresse_salle !== $adressesalle2 ) { ?><?php echo $adressesalle2 ?>
                        <br><?php } ?>
					<?php if ( $adressesalle3 ) { ?><?php echo $adressesalle3 ?><br><?php } ?>
					<?php if ( $codepsalle ) { ?><?php echo $codepsalle ?>&nbsp;<?php } ?>
					<?php if ( $villesalle ) { ?><?php echo $villesalle ?><br><?php } ?>
					<?php if ( $web ) { ?><a href="<?php echo $web ?>" target="_blank"
                                             title="<?php echo $nom ?>"><?php _e( 'Visit Website',
							'wp-fftt' ) ?></a><?php } ?>
                </address>
				<?php if ( 0 === $show_map || !$this->api_map ) { ?>
            </div>
            <div class="<?php echo $this->wpfftt_css_prefix ?>col-12 <?php echo $this->wpfftt_css_prefix ?>col-sm-6">
				<?php } ?>

                <strong><?php _e( 'Club Correspondent', 'wp-fftt' ) ?></strong><br>
				<?php if ( $nomcor ) { ?><?php echo $nomcor ?><?php } ?>
				<?php if ( $prenomcor ) { ?><?php echo $prenomcor ?><br><?php } ?>
				<?php if ( $mailcor ) { ?><a
                    href="mailto:<?php echo antispambot( $mailcor, 1 ) ?>"><?php echo antispambot( $mailcor ) ?></a>
                    <br><?php } ?>
				<?php if ( $telcor ) { ?><?php echo $telcor ?><br><?php } ?>
				<?php if ( $latitude ) { ?><?php echo $latitude ?><br><?php } ?>
				<?php if ( $longitude ) { ?><?php echo $longitude ?><br><?php } ?>

            </div>

			<?php if ( 0 !== $show_map  && $this->api_map ) {
				include 'wp-fftt-public-club-map.php';
			} ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php if($footer_card) { ?>
    <div class="<?php echo $this->wpfftt_css_prefix ?>card-footer <?php echo $this->wpfftt_css_prefix ?>text-muted <?php echo $this->wpfftt_css_prefix ?>text-center">
        <a href="<?php echo $this->get_club_link($numero) ?>" class="<?php echo $this->wpfftt_css_prefix ?>card-link"><?php _e( 'More about', 'wp-fftt' ) ?> <?php echo $nom ?> &#8594;</a>
    </div>
    <?php } ?>

</div>
<?php }else{
	_e('Unable to find any data for this club', 'wp-fftt');
}
