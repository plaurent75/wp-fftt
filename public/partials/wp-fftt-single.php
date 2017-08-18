<div class="<?php echo $this->wpfftt_css_prefix ?>row">
	<div class="<?php echo $this->wpfftt_css_prefix ?>col-12 <?php echo $this->wpfftt_css_prefix ?>col-sm-6">
		<h3><?php _e('Find Licensees by License #', 'wp-fftt') ?></h3>
		<div class="<?php echo $this->wpfftt_css_prefix ?>input-group">
			<input pattern=".{3,}" placeholder="588400" id="licence_number" type="number" class="<?php echo $this->wpfftt_css_prefix ?>form-control" required title="3 characters minimum" />
			<div class="<?php echo $this->wpfftt_css_prefix ?>input-group-btn">
				<button class="<?php echo $this->wpfftt_css_prefix ?>btn <?php echo $this->wpfftt_css_prefix ?>btn-primary" data-wpfftt-redir="<?php echo $this->get_player_link(null) ?>" id="submit_licence_number"><?php _e('Get Player', 'wp-fftt') ?></button>
			</div>
		</div>
	</div>

	<div class="<?php echo $this->wpfftt_css_prefix ?>col-12 <?php echo $this->wpfftt_css_prefix ?>col-sm-6">
		<h3><?php _e('Get data for a club #', 'wp-fftt') ?></h3>
		<div class="<?php echo $this->wpfftt_css_prefix ?>input-group">
			<input pattern=".{3,}" placeholder="02890023" id="club_number" type="number" class="<?php echo $this->wpfftt_css_prefix ?>form-control" required title="3 characters minimum" />
			<div class="<?php echo $this->wpfftt_css_prefix ?>input-group-btn">
				<button class="<?php echo $this->wpfftt_css_prefix ?>btn <?php echo $this->wpfftt_css_prefix ?>btn-primary" data-wpfftt-redir="<?php echo $this->get_club_link(null) ?>" id="submit_club_number"><?php _e('Club Details', 'wp-fftt') ?></button>
			</div>
		</div>

	</div>
</div>
<h3 class="<?php echo $this->wpfftt_css_prefix ?>text-center"><?php _e('Get Clubs by department', 'wp-fftt') ?></h3>
<div class="<?php echo $this->wpfftt_css_prefix ?>text-center"><?php include_once 'wp-fftt-public-france.php'; ?></div>

<?php include_once 'wp-fftt-public-france-table.php'; ?>
<?php 	include_once 'wp-fftt-public-legal.php'; ?>
