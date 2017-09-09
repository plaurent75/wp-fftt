<?php

class Wp_Fftt_Teams_Results extends WP_Widget {
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
	protected $wpfftt_css_prefix;


	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'wp_fftt_teams_results',
			'description' => __( 'Display latest parties for each club teams', 'wp-fftt' ),
		);
		parent::__construct( 'wp_fftt_teams_results', 'WPFFTT - '.__( 'Club Teams results', 'wp-fftt' ), $widget_ops );
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
			}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		if ( ! empty( $instance['numero_club'] ) && is_numeric( $instance['numero_club'] ) ) {
			$club_teams = $this->api->getEquipesByClub( $instance['numero_club'] );
			?>
            <table class="<?php echo $this->wpfftt_css_prefix ?>table <?php echo $this->wpfftt_css_prefix ?>table-responsive <?php echo $this->wpfftt_css_prefix ?>table-striped">
                <tbody>
				<?php
				foreach ( $club_teams as $team ) {
					$idpoule        = isset( $team['idpoule'] ) && ! is_array( $team['idpoule'] ) ? $team['idpoule'] : false;
					$iddiv          = isset( $team['iddiv'] ) && ! is_array( $team['iddiv'] ) ? $team['iddiv'] : false;
					$libelle_equipe = isset( $team['libequipe'] ) && ! is_array( $team['libequipe'] ) ? $team['libequipe'] : false;
					$lib_equ        = preg_replace( '/ - Phase [0-9]/', '', $libelle_equipe );
					$results        = $this->api->getPouleRencontres( $iddiv, $idpoule );
					$reverse = array_reverse($results);
					//var_dump($results);
					foreach ( $reverse as $r ) {
						$scorea  = ( array_key_exists( 'scorea',
								$r ) && ! is_array( $r['scorea'] ) ) ? $r['scorea'] : 0;
						$scoreb  = ( array_key_exists( 'scoreb',
								$r ) && ! is_array( $r['scoreb'] ) ) ? $r['scoreb'] : 0;

						if ( ( 0 === $scorea ) && ( 0 === $scoreb ) ) {
							continue;
						} else {
							if ( ( $lib_equ === $r['equa'] ) || ( $lib_equ === $r['equb'] ) ) {
								$equa    = ( array_key_exists( 'equa',
										$r ) && ! is_array( $r['equa'] ) ) ? $r['equa'] : false;
								$equb    = ( array_key_exists( 'equb',
										$r ) && ! is_array( $r['equb'] ) ) ? $r['equb'] : false;
								$libelle = ( array_key_exists( 'libelle',
										$r ) && ! is_array( $r['libelle'] ) ) ? $r['libelle'] : false;

								if($lib_equ === $equa) $equa = '<strong>'.$equa.'</strong>';
								if($lib_equ === $equb) $equb = '<strong>'.$equb.'</strong>';

								?>
                                <!--tr>
                                    <th colspan="4" class="wpfftt-text-center"><?php //echo $libelle ?></th>
                                </tr-->
                                <tr>
                                    <td><?php echo $equa ?></td>
                                    <td><?php echo $scorea ?></td>
                                    <td><?php echo $scoreb ?></td>
                                    <td><?php echo $equb ?></td>
                                </tr>
								<?php
								break;
							}
						}
					}

				}
				?>
                </tbody>
            </table>
			<?php
		}
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title   = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Teams', 'wp-fftt' );
		$numclub = ! empty( $instance['numero_club'] ) ? $instance['numero_club'] : '';
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title :',
					'wp-fftt' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'numero_club' ) ); ?>"><?php esc_attr_e( 'Club Number',
					'wp-fftt' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'numero_club' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'numero_club' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $numclub ); ?>">
        </p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance                = array();
		$instance['title']       = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['numero_club'] = ( ! empty( $new_instance['numero_club'] ) ) ? strip_tags( $new_instance['numero_club'] ) : '';

		return $instance;
	}
}
