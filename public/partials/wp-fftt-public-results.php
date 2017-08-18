<?php
/**
 * @global $results
 */
if(is_array($results) && count($results) > 0 ) {
?><div class="<?php echo $this->wpfftt_css_prefix ?>row"><?php
	$tmp = array();
	foreach ($results as $k){
		$tmp[$k['libelle']][] = $k;
	}

	foreach ($tmp as $libelle => $res){
		?>
		<div class="<?php echo $this->wpfftt_css_prefix ?>col-12 <?php echo $this->wpfftt_css_prefix ?>mb-4">
		<div class="<?php echo $this->wpfftt_css_prefix ?>card">
			<h2 class="<?php echo $this->wpfftt_css_prefix ?>card-title <?php echo $this->wpfftt_css_prefix ?>card-block <?php echo $this->wpfftt_css_prefix ?>card-body <?php echo $this->wpfftt_css_prefix ?>mb-0"><?php echo $libelle ?></h2>
		<table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-striped  <?php echo $this->wpfftt_css_prefix ?>mb-0">
		<tbody>
		<?php
		foreach ($res as $r) {
			$libelle = ( array_key_exists( 'libelle', $r ) && ! is_array( $r['libelle'] ) ) ? $r['libelle'] : false;
			$equa = ( array_key_exists( 'equa', $r ) && ! is_array( $r['equa'] ) ) ? $r['equa'] : false;
			$equb = ( array_key_exists( 'equb', $r ) && ! is_array( $r['equb'] ) ) ? $r['equb'] : false;
			$scorea = ( array_key_exists( 'scorea', $r ) && ! is_array( $r['scorea'] ) ) ? $r['scorea'] : 0;
			$scoreb = ( array_key_exists( 'scoreb', $r ) && ! is_array( $r['scoreb'] ) ) ? $r['scoreb'] : 0;
			$lien = ( array_key_exists( 'lien', $r ) && ! is_array( $r['lien'] ) ) ? $r['lien'] : 0;
			/**
			 * return array
			 * is_retour phase res_1 res_2 renc_id equip_1 equip_2 equip_id1 equip_id2
			 */
			parse_str($lien, $params);
			//$score = $scorea . '-' . $scoreb;
			$date_prevue = ( array_key_exists( 'dateprevue',
					$r ) && ! is_array( $r['dateprevue'] ) ) ? $r['dateprevue'] : '?';

			?>
			<tr id="score_<?php echo $params['renc_id']?>">
						<td><?php echo $date_prevue ?></td>
						<td><?php echo $equa ?></td>
						<td><?php echo $scorea?></td>
						<td><?php echo $scoreb?></td>
						<td><?php echo $equb ?></td>
						<td>
							<a class="wpfftt-loader" data-toggle="modal" data-target="#load_<?php echo $params['renc_id']?>" data-wpfftt-lien="<?php echo $lien ?>" data-wpfftt-collapse="<?php echo $params['renc_id']?>" href="#load_<?php echo $params['renc_id']?>">Détail</a>
				<div id="load_<?php echo $params['renc_id']?>" class="<?php echo $this->wpfftt_css_prefix ?>modal <?php echo $this->wpfftt_css_prefix ?>fade">
				<?php if(true === $this->wpfftt_css) {
					include('wp-fftt-public-results-modal.php');
				}?>
				</div>
							<div class="wpfftt-full-screen <?php echo $this->wpfftt_css_prefix ?>text-center <?php echo $this->wpfftt_css_prefix ?>table-inverse" id="loading_wpfftt_<?php echo $params['renc_id']?>" style="display:none">
								<p><?php _e('Loading') ?> <?php _e('Match detail', 'wp-fftt') ?> ...</p>
								<img src="<?php echo get_site_url() ?>/wp-includes/js/thickbox/loadingAnimation.gif" width="50%" />
							</div>
				</td>
			</tr>

			<?php
		}
		?>	</tbody>
		</table>
		</div>
		</div><?php
	}
	?>


	<?php
?></div><?php
}else{
	_e('Error : No data for this division');
}
