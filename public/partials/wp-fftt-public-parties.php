<?php
/**
 * @global array $p
 */
if($p && is_array($p)) {
?>
<tr>
<td><?php echo $p['date'] ?></td>
<td class="<?php echo $this->wpfftt_css_prefix ?>table-inverse <?php echo $this->wpfftt_css_prefix ?>text-center <?php echo ('V' === $p['vd']) ? $this->wpfftt_css_prefix.'bg-success' : $this->wpfftt_css_prefix.'bg-danger' ?>"><?php echo $p['vd'] ?></td>
<td><?php echo $p['advnompre'] ?></td>
<td><?php echo $p['advclaof'] ?></td>
<td><?php echo $p['pointres'] ?></td>
<td><?php echo $p['coefchamp'] ?></td>
</tr>
<?php }else{
	_e('Unable to find any data for this player', 'wp-fftt');
}
