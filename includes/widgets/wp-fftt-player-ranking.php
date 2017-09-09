<?php
class Wp_Fftt_Player_Ranking extends WP_Widget {
	/**
	 * @var string $appId ID de l'application fourni par la FFTT (ex: AM001)
	 */
	protected $appId;

	/**
	 * @var string $appKey Mot de passe fourni par la FFTT
	 */
	protected $appKey;
	protected $options;

	protected $cache;

	protected $api;
	protected $wpfftt_slug;
	protected $wpfftt_css;
	protected $club_id;
	protected $wpfftt_css_prefix;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'wp_fftt_player_ranking',
			'description' => __( 'Display Top Player for a club', 'wp-fftt' ),
		);
		parent::__construct( 'wp_fftt_player_ranking', 'WPFFTT - '.__( 'Top Players', 'wp-fftt' ), $widget_ops );
		$this->options = get_option( 'wp_fftt_settings' );
		$this->cache   = new CacheService();
		if ( empty( $_SESSION['serial'] ) ) {
			$_SESSION['serial'] = ffttAPI::generateSerial();
		}
		$this->appId  = isset( $this->options['wp_fftt_login'] ) ? $this->options['wp_fftt_login'] : false;
		$this->appKey = isset( $this->options['wp_fftt_password'] ) ? $this->options['wp_fftt_password'] : false;
		$this->api    = new ffttAPI( $this->appId, $this->appKey, $this->cache );
		$this->api->setSerial( $_SESSION['serial'] );
		$this->api->initialization();
		$this->wpfftt_slug = isset( $this->options['wp_fftt_slug'] ) ? $this->options['wp_fftt_slug'] : false;
		$this->wpfftt_css = ( isset($this->options['wp_fftt_css']) && 'false' === $this->options['wp_fftt_css'] ) ? false : true;
		$this->wpfftt_css_prefix = true === $this->wpfftt_css ? '' : 'wpfftt-';
		$this->club_id =  isset($this->options['wp_fftt_club_id']) ? $this->options['wp_fftt_club_id'] : false;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 * @return string echo data
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Top Players', 'wp-fftt' );
		$numclub = ! empty( $instance['numero_club'] ) ? strip_tags($instance['numero_club']) : $this->club_id;
		$number = ! empty( $instance['number'] ) ? (int) $instance['number'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title :', 'wp-fftt' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'numero_club' ) ); ?>"><?php esc_attr_e( 'Club Number', 'wp-fftt' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'numero_club' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'numero_club' ) ); ?>" type="text" value="<?php echo esc_attr( $numclub ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of players to display?', 'wp-fftt' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" value="<?php echo esc_attr( $number ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['numero_club'] = ( ! empty( $new_instance['numero_club'] ) ) ? strip_tags( $new_instance['numero_club'] ) : $this->club_id;
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';

		return $instance;
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$num_club = ( ! empty( $instance['numero_club'] ) ) ? strip_tags($instance['numero_club'])  : $this->club_id;
		$number = ( ! empty( $instance['number'] ) ) ? (int) $instance['number']  : 5;
		if ( $num_club && is_numeric($num_club) ) {
			$players = $this->api->getLicencesByClub( $num_club );
			$this->sort_array_of_array($players, 'point');
			$list_players = array_slice($players, 0, $number);
			$x = 1;
			?>
            <table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-striped">
            <!--thead>
            <tr>
                <th>#</th>
                <th><?php _e('Player', 'wp-fftt') ?></th>
                <th><?php _e('Points', 'wp-fftt') ?></th>
            </tr>
            </thead-->
            <tfoot>
            <tr>
                <td colspan="3" class="<?php echo $this->wpfftt_css_prefix ?>text-center">
                    <a href="<?php echo get_permalink($this->wpfftt_slug) ._x('players', 'slug', 'wp-fftt').'/'.$num_club; ?>"><?php _e('All players of the club', 'wp-fftt') ?></a>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php
			foreach ($list_players as $l){
			    ?>
                    <tr>
                        <td><?php echo $x ?></td>
                        <td><a href="<?php echo get_permalink($this->wpfftt_slug) ._x('player', 'slug', 'wp-fftt').'/'.$l['licence'] ?>"><?php echo $l['nom'] ?> <?php echo $l['prenom'] ?></a></td>
                        <td><?php echo $l['point'] ?></td>
                    </tr>
                <?php
                $x++;
            }
            ?></tbody></table><?php

		}


		echo $args['after_widget'];
	}


	public function sortPlayerRank($item1,$item2){
		if ($item1['point'] == $item2['point']) return 0;
		return ($item1['point'] > $item2['point']) ? 1 : -1;

	}

	public function sort_array_of_array(&$array, $subfield)
	{
		$sortarray = array();
		foreach ($array as $key => $row)
		{
			$sortarray[$key] = $row[$subfield];
		}

		array_multisort($sortarray, SORT_DESC, $array);
	}
}