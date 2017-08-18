<?php
$dept = $this->get_dept_name();
?>
<ul id="wpfftt-list-dept" class="<?php echo $this->wpfftt_css_prefix ?>row <?php echo $this->wpfftt_css_prefix ?>list-unstyled">
<?php foreach ($dept as $cp => $nom){
	echo '<li class="'.$this->wpfftt_css_prefix.'col-12 '.$this->wpfftt_css_prefix.'col-sm-4"><a data-dept="'.$cp.'" href="'.$this->get_club_department_link($cp).'"">'.$nom.'</a></li>';
}?>
</ul>
