<?php
/**
 * @global $club_players
 */
if($club_players && is_array($club_players)) {
$club_data = $club_players[0];
$club_number = $club_data['club'];
$club_name = $club_data['nclub'];
$cur_lang = get_locale();
?>
<div class="<?php echo $this->wpfftt_css_prefix ?>club_title">
	<h2><?php echo $club_name ?></h2>
	<p class="<?php echo $this->wpfftt_css_prefix ?>lead"><?php echo count($club_players) ?> <?php _e('Licensees', 'wp-fftt') ?></p>
</div>
<table id="wp_fftt_data_player" class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-striped">
	<thead>
	<tr>
		<th><?php _e('Last Name') ?></th>
		<th><?php _e('First Name') ?></th>
		<th><?php _e('Licence #', 'wp-fftt') ?></th>
		<th><?php _e('Sex', 'wp-fftt') ?></th>
		<th><?php _e('Points', 'wp-fftt') ?></th>
		<th><?php _e('Statistics', 'wp-fftt') ?></th>
	</tr>
	</thead>
	<tbody>
<?php
foreach ( $club_players as $p ) {
	?>
<tr>
	<td><?php echo $p['nom'] ?></td>
	<td><?php echo $p['prenom'] ?></td>
	<td><?php echo $p['licence'] ?></td>
	<td><?php echo $p['sexe'] ?></td>
	<td><?php echo $p['point'] ?></td>
	<td><a href="<?php echo $this->get_player_link($p['licence']) ?>"><?php _e('Details', 'wp-fftt') ?></a></td>

</tr>
<?php
}
?>
	</tbody>
</table>
<script type="text/javascript">
  (function( $ ) {
    $('#wp_fftt_data_player').DataTable(
      <?php if( 'fr_FR' === get_locale()) { ?>
      {
        "language": {
          "url" : "<?php echo $this->get_public_url() ?>/js/french.json"
        },
        "pageLength": 25,
      }
      <?php } ?>
    );
  })( jQuery );
</script>
<?php }else{
	_e('Unable to find any data for this club', 'wp-fftt');
}
