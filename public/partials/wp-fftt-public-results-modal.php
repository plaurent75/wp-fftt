<?php
/**
 * @global $lien
 */
$rencontre = $this->api->getRencontre($lien);
//$this->var_debug($rencontre);

$equia = $rencontre['resultat']['equa'];
$equib = $rencontre['resultat']['equb'];
$scorea = is_array($rencontre['resultat']['resa']) ? '0' : $rencontre['resultat']['resa'];
$scoreb = is_array($rencontre['resultat']['resb']) ? '0' : $rencontre['resultat']['resb'];
$players = array_key_exists('joueur', $rencontre) ? $rencontre['joueur'] : false;
$scores = array_key_exists('partie', $rencontre) ? $rencontre['partie'] : false;
?>
<div class="<?php echo $this->wpfftt_css_prefix ?>modal-dialog  <?php echo $this->wpfftt_css_prefix ?>modal-lg" role="document">
	<div class="<?php echo $this->wpfftt_css_prefix ?>modal-content">
		<div class="<?php echo $this->wpfftt_css_prefix ?>modal-header">
			<h5 class="<?php echo $this->wpfftt_css_prefix ?>modal-title" id="title_<?php echo $params['renc_id']?>"><?php echo $equia ?> vs <?php echo $equib ?></h5>
			<button type="button" class="<?php echo $this->wpfftt_css_prefix ?>close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="<?php echo $this->wpfftt_css_prefix ?>modal-body">
			<table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-stripped">
				<thead>
					<tr>
						<th><?php echo $equia ?></th>
						<th><?php echo $scorea ?></th>
						<th><?php echo $scoreb ?></th>
						<th><?php echo $equib ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if($players) {
					?>
					<tr><td colspan="4" class="<?php echo $this->wpfftt_css_prefix ?>text-center"><?php _e('Composition of teams', 'wp-fftt') ?></td></tr>
					<?php
					foreach ( $players as $line ) { ?>
						<tr>
							<td><?php echo $line['xja'] ?></td>
							<td><?php echo $line['xca'] ?></td>
							<td><?php echo $line['xjb'] ?></td>
							<td><?php echo $line['xcb'] ?></td>
						</tr>
					<?php }
				}
				?>
				<?php if($scores) {
					?>
					<tr><td colspan="4" class="<?php echo $this->wpfftt_css_prefix ?>text-center"><?php _e('Match detail', 'wp-fftt') ?></td></tr>
					<?php
					foreach ( $scores as $match ) { ?>
						<tr>
							<td><?php echo $match['ja'] ?></td>
							<td><?php echo $match['scorea'] ?></td>
							<td><?php echo $match['scoreb'] ?></td>
							<td><?php echo $match['jb'] ?></td>
						</tr>
					<?php }
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
