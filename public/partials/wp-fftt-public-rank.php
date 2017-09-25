<?php
/**
 * @global $rank
 * @global $poules
 * @global $atts Wp_Fftt_Public shortcode attributes
 */
if(is_array($rank) && count($rank) > 0 ){
	/*if(isset($atts) && is_array($atts)) {
		if(array_key_exists('fftt_poule', $atts) && !empty($atts['fftt_poule']))  $id_poule = $atts['fftt_poule'];
		else $id_poule = false;
	}
	else*/
	$id_poule = is_numeric(get_query_var('wp_fftt_poule')) ? get_query_var('wp_fftt_poule') : false;
	?>
	<?php
	// Do not display title for shortcode
	if($id_poule) {?><h2><?php echo $this->get_division_poule_name($rank[0]['numero'],$id_poule) ?></h2><?php } ?>

	<?php if(isset($atts) && is_array($atts)) {
		if(array_key_exists('fftt_poule', $atts) && !empty($atts['fftt_poule']))  $id_poule_active = $atts['fftt_poule'];
		else $id_poule_active = false;
	}		else {
		$id_poule_active = $id_poule;
	}
	?>
	<?php if(isset($poules) && is_array($poules)) {
		?><div class="<?php echo $this->wpfftt_css_prefix ?>nav <?php echo $this->wpfftt_css_prefix ?>nav-tabs"><?php
		foreach ($poules as $poule){
			?>
            <a href="<?php echo $this->get_rank_link($poule['iddiv'], $poule['idpoule']) ?>" class="<?php echo $this->wpfftt_css_prefix ?>nav-link<?php echo ($poule['idpoule'] === $id_poule_active) ? ' active' : null; ?>">
				<?php echo $poule['libelle'] ?>
            </a>
			<?php
		}
		?></div><?php
	}?>
    <table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-striped">
        <thead>
        <tr>
            <th><?php _e('Team', 'wp-fftt') ?></th>
            <th><?php _e('P', 'wp-fftt') ?></th>
            <th><?php _e('pts', 'wp-fftt') ?></th>
            <th><?php _e('W', 'wp-fftt') ?></th>
            <th><?php _e('L', 'wp-fftt') ?></th>
            <th><?php _e('R', 'wp-fftt') ?></th>
            <th><?php _e('Club', 'wp-fftt') ?></th>
        </tr>
        </thead>
		<?php
		if(is_array($rank)) {
			foreach ( $rank as $r ) {
				?>
                <tr>
                    <td><?php echo is_string($r['equipe']) ? $r['equipe'] : 'exempt' ?></td>
                    <td><?php echo is_string($r['joue']) ? $r['joue'] : '0' ?></td>
                    <td><?php echo is_string($r['pts']) ? $r['pts'] : '0' ?></td>
                    <td class="<?php echo $this->wpfftt_css_prefix ?>bg-success <?php echo $this->wpfftt_css_prefix ?>table-inverse"><?php echo $r['totvic'] ?></td>
                    <td class="<?php echo $this->wpfftt_css_prefix ?>bg-danger <?php echo $this->wpfftt_css_prefix ?>table-inverse"><?php echo $r['totdef'] ?></td>
                    <td class="<?php echo $this->wpfftt_css_prefix ?>text-center"><a
                                href="<?php echo $this->get_results_match_link( $poule['iddiv'], $id_poule ) ?>">&#8693;</a>
                    </td>
                    <td class="<?php echo $this->wpfftt_css_prefix ?>text-right">
						<?php if(is_string($r['numero'])) { ?>
                            <small><em><a class="<?php echo $this->wpfftt_css_prefix ?>text-muted"
                                          href="<?php echo $this->get_club_link( $r['numero'] ) ?>"><?php echo $this->get_club_name( $r['numero'] ) ?>
                                </em></small>
                            </a>
						<?php } ?>
                    </td>
                </tr>
				<?php
			}
		}else{
			?>
            <tr>
                <td colspan="7">&nbsp;</td>
            </tr>
			<?php
		}
		?>
    </table>
	<?php
}
