<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blogtwist_Social_Menu extends Blogtwist_Widget_Base {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'widget_blogtwist_social_menu';
		$this->widget_description = __( 'Displays social menu if you have set it.', 'blogtwist' );
		$this->widget_id          = 'blogtwist_social_menu';
		$this->widget_name        = __( 'BlogTwist: Social Menu', 'blogtwist' );
		$this->settings = $this->get_widget_settings();

		parent::__construct();

	}

	/**
	 * Define widget settings.
	 */
	protected function get_widget_settings()
	{
	    return array(
			'title'      => array(
				'type'  => 'text',
				'label' => __( 'Title', 'blogtwist' ),
			),
			'color'      => array(
				'type'    => 'select',
				'label'   => __( 'Social Links Color', 'blogtwist' ),
				'options' => array(
					'has-brand-color' => __( 'Use Brand Color', 'blogtwist' ),
					'has-brand-background' => __( 'Use Brand Background', 'blogtwist' ),
				),
				'std'     => 'has-brand-background',
			),
			'style'      => array(
				'type'    => 'select',
				'label'   => __( 'Style', 'blogtwist' ),
				'options' => blogtwist_get_social_links_styles(),
				'std'     => 'style_1',
			),
			'show_label' => array(
				'type'  => 'checkbox',
				'label' => __( 'Show Label', 'blogtwist' ),
				'std'   => false,
			),
			'column'     => array(
				'type'    => 'select',
				'label'   => __( 'Column', 'blogtwist' ),
				'desc'    => __( 'Will only work when label is enabled from above and there is enough space to display the columns.', 'blogtwist' ),
				'options' => array(
					'one'   => __( 'One', 'blogtwist' ),
					'two'   => __( 'Two', 'blogtwist' ),
					'three' => __( 'Three', 'blogtwist' ),
				),
				'std'     => 'two',
			),
			'align'      => array(
				'type'    => 'select',
				'label'   => __( 'Alignment', 'blogtwist' ),
				'options' => array(
					'left'   => __( 'Left', 'blogtwist' ),
					'center' => __( 'Center', 'blogtwist' ),
					'right'  => __( 'Right', 'blogtwist' ),
				),
				'std'     => 'left',
			),
	    );
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		ob_start();

		$this->widget_start( $args, $instance );

		do_action( 'blogtwist_before_social_menu' );

		$wrapper_class = isset( $instance['align'] ) ? $instance['align'] : $this->settings['align']['std'];

		?>
		<div class="wpmotif-social-widget is-aligned-<?php echo esc_attr( $wrapper_class ); ?>">
			<?php

			if ( has_nav_menu( 'social' ) ) {

				$social_link_class  = 'widget-social-icons reset-list-style social-icons ';
				$social_link_style  = isset( $instance['style'] ) ? $instance['style'] : $this->settings['style']['std'];
				$social_link_color  = isset( $instance['color'] ) ? $instance['color'] : $this->settings['color']['std'];

				$social_link_class .= $social_link_style . ' ' . $social_link_color;

				$label_class = 'screen-reader-text';
				$show_label  = isset( $instance['show_label'] ) ? $instance['show_label'] : $this->settings['show_label']['std'];
				if ( $show_label ) {
					$label_class        = 'social-widget-label';
					$social_link_class .= ' has-label-enabled';
					$column             = isset( $instance['column'] ) ? $instance['column'] : $this->settings['column']['std'];
					$social_link_class .= ' is-column-' . $column;
				}

				wp_nav_menu(
					array(
						'theme_location'  => 'social',
						'container_class' => 'social-widget-container',
						'fallback_cb'     => false,
						'depth'           => 1,
						'menu_class'      => $social_link_class,
						'link_before'     => '<span class="' . $label_class . '">',
						'link_after'      => '</span>',
					)
				);
			} else {
				esc_html_e( 'Social menu is not set. You need to create menu and assign it to Social Menu on Menu Settings.', 'blogtwist' );
			}
			?>
		</div>
		<?php

		do_action( 'blogtwist_after_social_menu' );

		$this->widget_end( $args );

		echo ob_get_clean();
	}
}
