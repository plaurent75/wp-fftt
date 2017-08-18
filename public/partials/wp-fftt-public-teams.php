<?php
/**
 * @global $club_teams
 */
if($club_teams && is_array($club_teams)) {
	?>
		<table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-striped">
			<thead>
				<tr>
					<th><?php _e('Team', 'wp-fftt') ?></th>
					<th><?php _e('Division', 'wp-fftt') ?></th>
					<th><?php _e('Rank', 'wp-fftt') ?></th>
					<th><?php _e('Results', 'wp-fftt') ?> & <?php _e('Calendar', 'wp-fftt') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
	foreach ($club_teams as $team){
	$libelle_equipe = isset( $team['libequipe'] ) && !is_array($team['libequipe']) ? $team['libequipe'] : false;
	$libelle_division = isset( $team['libdivision'] ) && !is_array($team['libdivision']) ? $team['libdivision'] : false;
	$liendivision = isset( $team['liendivision'] ) && !is_array($team['liendivision']) ? $team['liendivision'] : false;
	$id_epreuve = isset( $team['idepr'] ) && !is_array($team['idepr']) ? $team['idepr'] : false;
	$libelle_epreuve = isset( $team['libepr'] ) && !is_array($team['libepr']) ? $team['libepr'] : false;
	$idpoule = isset( $team['idpoule'] ) && !is_array($team['idpoule']) ? $team['idpoule'] : false;
	$iddiv = isset( $team['iddiv'] ) && !is_array($team['iddiv']) ? $team['iddiv'] : false;
		?>
		<tr>
			<td><?php echo preg_replace('/ - Phase [0-9]/','',$libelle_equipe) ?></td>
			<td><?php echo $libelle_division ?></td>
			<td><a href="<?php echo $this->get_rank_link($iddiv,$idpoule) ?>" class="<?php echo $this->wpfftt_css_prefix ?>card-link"><?php _e('Discover', 'wp-fftt') ?></a></td>
			<td><a href="<?php echo $this->get_results_match_link($iddiv,$idpoule) ?>" class="<?php echo $this->wpfftt_css_prefix ?>card-link"><?php _e('Details', 'wp-fftt') ?></a></td>
		</tr>

	<?php } ?>
			</tbody>
		</table>
<?php }else{
	_e('Unable to find any data for this club', 'wp-fftt');
}
