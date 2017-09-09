<?php
/**
 * @global array $player
 */
if($player && is_array($player)) {
	$licence = array_key_exists( 'licence', $player ) ? $player['licence'] : false;
	$nom     = array_key_exists( 'nom', $player ) ? $player['nom'] : false;
	$prenom  = array_key_exists( 'prenom', $player ) ? $player['prenom'] : false;
	$club    = array_key_exists( 'club', $player ) ? $player['club'] : false;
	$nclub   = array_key_exists( 'nclub', $player ) ? $player['nclub'] : false;
	$natio   = array_key_exists( 'natio', $player ) ? $player['natio'] : false;
	$clglob  = array_key_exists( 'clglob', $player ) ? $player['clglob'] : false;
	$point   = array_key_exists( 'point', $player ) ? $player['point'] : false;
	$aclglob = array_key_exists( 'aclglob', $player ) ? $player['aclglob'] : false;
	$apoint  = array_key_exists( 'apoint', $player ) ? $player['apoint'] : false;
	$clast   = array_key_exists( 'clast', $player ) ? $player['clast'] : false;
	$clnat   = array_key_exists( 'clnat', $player ) ? $player['clnat'] : false;
	$categ   = array_key_exists( 'categ', $player ) ? $player['categ'] : false;
	$rangreg = array_key_exists( 'rangreg', $player ) ? $player['rangreg'] : false;
	$rangdep = array_key_exists( 'rangdep', $player ) ? $player['rangdep'] : false;
	$valcla  = array_key_exists( 'valcla', $player ) ? $player['valcla'] : false;
	$clpro   = array_key_exists( 'clpro', $player ) ? $player['clpro'] : false;
	$valinit = array_key_exists( 'valinit', $player ) ? $player['valinit'] : false;
//$photo = array_key_exists('photo', $player) ? $player['photo'] : false;
	$progmois          = array_key_exists( 'progmois', $player ) ? $player['progmois'] : false;
	$progann           = array_key_exists( 'progann', $player ) ? $player['progann'] : false;
	$parties           = $this->api->getJoueurParties( $licence );
	$color_class       = ( $progann > 0 ) ? $this->wpfftt_css_prefix.'card-outline-success' : $this->wpfftt_css_prefix.'card-outline-danger';
	$stats_badge_class = ( $progann > 0 ) ? $this->wpfftt_css_prefix.'bg-success' : $this->wpfftt_css_prefix.'bg-danger';
	$stats_badge       = ( $progann > 0 ) ? '&#8593;' : '&#8595;';
	?>

    <div class="<?php echo $this->wpfftt_css_prefix ?>card-deck wpfftt-plugin <?php echo $this->wpfftt_css_prefix ?>mb-4">
        <div class="<?php echo $this->wpfftt_css_prefix ?>card">
            <div class="<?php echo $this->wpfftt_css_prefix ?>card-block <?php echo $this->wpfftt_css_prefix ?>card-body">
                <h2 class="<?php echo $this->wpfftt_css_prefix ?>card-title"><?php echo ucwords( $prenom ) ?> <?php echo strtoupper( $nom ) ?> <span
                            class="<?php echo $this->wpfftt_css_prefix ?>badge <?php echo $this->wpfftt_css_prefix ?>badge-default <?php echo $this->wpfftt_css_prefix ?>badge-secondary <?php echo $this->wpfftt_css_prefix ?>float-right"><?php echo $clast ?></span>
                </h2>
                <h4 class="<?php echo $this->wpfftt_css_prefix ?>card-subtitle <?php echo $this->wpfftt_css_prefix ?>text-muted"><a href="<?php echo $this->get_club_link($nclub) ?>"><?php echo strtoupper( $club ) ?></a></h4>
            </div>
            <table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-striped <?php echo $this->wpfftt_css_prefix ?>mb-0">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Officials Points', 'wp-fftt' ) ?></th>
                    <td><?php echo $valcla ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Early Season Points', 'wp-fftt' ) ?></th>
                    <td><?php echo $valinit ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Licence #', 'wp-fftt' ) ?></th>
                    <td><?php echo $licence ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Category', 'wp-fftt' ) ?></th>
                    <td><?php echo $categ ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Nationnality', 'wp-fftt' ) ?></th>
                    <td><?php echo $natio ?></td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="<?php echo $this->wpfftt_css_prefix ?>card <?php echo $color_class ?>">
            <div class="<?php echo $this->wpfftt_css_prefix ?>card-block <?php echo $this->wpfftt_css_prefix ?>card-body">
                <h3 class="<?php echo $this->wpfftt_css_prefix ?>card-title"><?php _e( 'Statistics', 'wp-fftt' ) ?> <span
                            class="<?php echo $this->wpfftt_css_prefix ?>badge <?php echo $stats_badge_class ?> <?php echo $this->wpfftt_css_prefix ?>float-right"><?php echo $stats_badge ?></span>
                </h3>
            </div>
            <table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-striped">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Monthly points', 'wp-fftt' ) ?></th>
                    <td><?php echo $point ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Annual growth', 'wp-fftt' ) ?></th>
                    <td><?php echo $progann ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Monthly growth', 'wp-fftt' ) ?></th>
                    <td><?php echo $progmois ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Nationnality', 'wp-fftt' ) ?></th>
                    <td><?php echo $natio ?></td>
                </tr>
                </tbody>
            </table>
            <table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-striped <?php echo $this->wpfftt_css_prefix ?>mb-0">
                <thead>
                <tr>
                    <th><?php _e( 'national', 'wp-fftt' ) ?></th>
                    <th><?php _e( 'region', 'wp-fftt' ) ?></th>
                    <th><?php _e( 'depart.', 'wp-fftt' ) ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $clnat ?></td>
                    <td><?php echo $rangreg ?></td>
                    <td><?php echo $rangdep ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    $historic = $this->api->getJoueurHistorique($licence);
    if(isset($historic) && is_array($historic)) {
	    ?>
    <div class="<?php echo $this->wpfftt_css_prefix ?>card <?php echo $this->wpfftt_css_prefix ?>mb-4">
        <div class="<?php echo $this->wpfftt_css_prefix ?>card-block <?php echo $this->wpfftt_css_prefix ?>card-body">
            <h4 class="<?php echo $this->wpfftt_css_prefix ?>card-subtitle"><?php _e( 'Historic', 'wp-fftt' ) ?></h4>
        </div>
        <?php
        $line_val = '';
        $line_label = '';
        foreach ($historic as $h){
            $label = preg_replace('/Saison /', '',$h['saison']);
            $label_arr = explode(' / ', $label);
            $phase = $h['phase'];
            if('1' === $phase) $axe_label = $label_arr[0];
            else $axe_label = null;
            $line_val .= $h['point'].',';
            /*$line_label .= "'".preg_replace('/Saison /', '',$h['saison'])."";
            $line_label .= $h['phase']."',";*/
            $line_label .= "'".$axe_label."',";
        }
        $line_val = substr($line_val, 0, -1);
        ?>
        <div class="<?php echo $this->wpfftt_css_prefix ?>col-12 joueur_stats"></div>
    </div>
        <script type="text/javascript">
          (function( $ ) {
            var data = {
              labels: [<?php echo $line_label ?>],
              series: [
                [<?php echo $line_val ?>]
              ]
            };

// We are setting a few options for our chart and override the defaults
            var options = {
              // Don't draw the line chart points
              showPoint: true,
              // Disable line smoothing
              lineSmooth: true,
              height : 200,
              // X-Axis specific configuration
              axisX: {
                // We can disable the grid for this axis
                showGrid: true,
                // and also don't show the label
                showLabel: true,
                //position: 'start'

              },
              // Y-Axis specific configuration
              axisY: {
                // Lets offset the chart a bit from the labels
                offset: 60
              }
            };

            new Chartist.Line('.joueur_stats', data, options);



          })( jQuery );
        </script>
        <?php } ?>

	<?php
	if ( is_array( $parties ) && count( $parties ) > 0 ) {
		?>
        <div class="<?php echo $this->wpfftt_css_prefix ?>card <?php echo $this->wpfftt_css_prefix ?>mb-4">
        <div class="<?php echo $this->wpfftt_css_prefix ?>card-block <?php echo $this->wpfftt_css_prefix ?>card-body">
            <h4 class="<?php echo $this->wpfftt_css_prefix ?>card-subtitle"><?php _e( 'Details of Validated parties', 'wp-fftt' ) ?></h4>
        </div>
        <table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-striped <?php echo $this->wpfftt_css_prefix ?>table-hover <?php echo $this->wpfftt_css_prefix ?>mb-0">
            <thead>
            <tr>
                <th><?php _e( 'Date', 'wp-fftt' ) ?></th>
                <th><?php _e( 'v/d', 'wp-fftt' ) ?></th>
                <th><?php _e( 'Opponent', 'wp-fftt' ) ?></th>
                <th><?php _e( 'Rank.', 'wp-fftt' ) ?></th>
                <th><?php _e( 'Gain / loss', 'wp-fftt' ) ?></th>
                <th><?php _e( 'Coef.', 'wp-fftt' ) ?></th>
                <!--th><?php //_e('Day #', 'wp-fftt')
				?></th-->
            </tr>
            </thead>
			<?php
			foreach ( $parties as $p ) {
				include 'wp-fftt-public-parties.php';
			}
			?></table>
        </div><?php
	}
}else{
    _e('Unable to find any data for this licensee', 'wp-fftt');
}
