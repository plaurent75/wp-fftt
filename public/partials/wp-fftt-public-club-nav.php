<?php
/**
 * @global $numero
 * @global $dep_id
 */
global $post;
$is_club = empty(get_query_var( 'wp_fftt_club')) ? false : true;
$is_club_shortcode = (has_shortcode($post->post_content,'fftt_club') && !$this->is_wpfftt_page()) ? true : false;
$is_teams = empty(get_query_var( 'wp_fftt_club_teams')) ? false : true;
$is_teams_shortcode = (has_shortcode($post->post_content,'fftt_teams') && !$this->is_wpfftt_page()) ? true : false;
$is_players = empty(get_query_var( 'wp_fftt_players')) ? false : true;
$is_players_shortcode = (has_shortcode($post->post_content,'fftt_players_club') && !$this->is_wpfftt_page()) ? true : false;
$is_dept = empty(get_query_var( 'wp_fftt_club_department')) ? false : true;
$is_dept_shortcode = (has_shortcode($post->post_content,'fftt_clubs_departement') && !$this->is_wpfftt_page()) ? true : false;
?>
<div class="<?php echo $this->wpfftt_css_prefix ?>nav <?php echo $this->wpfftt_css_prefix ?>nav-tabs <?php echo $this->wpfftt_css_prefix ?>card-header-tabs">
	<a class="<?php echo $this->wpfftt_css_prefix ?>nav-link<?php echo (false === $is_club && false === $is_club_shortcode)  ? null : ' active'; ?>" href="<?php echo $this->get_club_link($numero) ?>"><?php _e('Club', 'wp-fftt') ?></a>
	<a class="<?php echo $this->wpfftt_css_prefix ?>nav-link<?php echo (false === $is_teams && false === $is_teams_shortcode) ? null : ' active'; ?>" href="<?php echo $this->get_club_teams_link($numero) ?>"><?php _e('Teams', 'wp-fftt') ?></a>
	<a class="<?php echo $this->wpfftt_css_prefix ?>nav-link<?php echo (false === $is_players && false === $is_players_shortcode) ? null : ' active'; ?>" href="<?php echo $this->get_club_players_link($numero) ?>"><?php _e('Players', 'wp-fftt') ?></a>
    <?php if(isset($dep_id) && is_numeric($dep_id)) { ?>
	<a class="<?php echo $this->wpfftt_css_prefix ?>nav-link<?php echo (false === $is_dept && false === $is_dept_shortcode) ? null : ' active'; ?>" href="<?php echo $this->get_club_department_link($dep_id) ?>"><?php _e('Clubs of the department', 'wp-fftt') ?></a>
    <?php } ?>
</div>
