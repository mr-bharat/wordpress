<?php
if (!defined('ABSPATH')) {
    exit;
}

class Blogtwist_Ads_Code extends Blogtwist_Widget_Base
{
    public function __construct()
    {
        $this->widget_cssclass = 'widget_blogtwist_ads_code';
        $this->widget_description = __("Displays Advertisements or anything placed under this", 'blogtwist');
        $this->widget_id = 'blogtwist_ads_code';
        $this->widget_name = __('BlogTwist: Advertisements Code', 'blogtwist');
        $this->settings = $this->get_widget_settings();
        parent::__construct();
    }

    /**
     * Define widget settings.
     */
    protected function get_widget_settings()
    {
        return array(
            'title'            => array(
                'type'  => 'text',
                'label' => __( 'Widget Title', 'blogtwist' ),
            ),
            'ads_code'        => array(
                'type'  => 'textarea',
                'label' => __( 'Ads Code', 'blogtwist' ),
            ),
            'content_alignment'           => array(
                'type'    => 'select',
                'label'   => __( 'Content Alignment', 'blogtwist' ),
                'options' => array(
                    'left'    => __( 'Left', 'blogtwist' ),
                    'center'  => __( 'Center', 'blogtwist' ),
                    'right'   => __( 'Right', 'blogtwist' ),
                    'stretch' => __( 'Stretch', 'blogtwist' ),
                ),
                'std'     => 'center',
            ),
            'hide_on_desktop' => array(
                'type'  => 'checkbox',
                'label' => __( 'Hide on Desktop', 'blogtwist' ),
                'std'   => false,
            ),
            'hide_on_tablet'  => array(
                'type'  => 'checkbox',
                'label' => __( 'Hide on Tablet', 'blogtwist' ),
                'std'   => false,
            ),
            'hide_on_mobile'  => array(
                'type'  => 'checkbox',
                'label' => __( 'Hide on Mobile', 'blogtwist' ),
                'std'   => false,
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

        $ad_class = '';
        if ( isset( $instance['hide_on_desktop'] ) && $instance['hide_on_desktop'] ) {
            $ad_class .= ' hide-on-desktop';
        }
        if ( isset( $instance['hide_on_tablet'] ) && $instance['hide_on_tablet'] ) {
            $ad_class .= ' hide-on-tablet';
        }
        if ( isset( $instance['hide_on_mobile'] ) && $instance['hide_on_mobile'] ) {
            $ad_class .= ' hide-on-mobile';
        }

        do_action( 'blogtwist_before_ads_code' );

        if ( isset( $instance['ads_code'] ) && $instance['ads_code'] ) {
            // Check if user can use unfiltered HTML
            if ( current_user_can( 'unfiltered_html' ) ) {
                $ads_code = $instance['ads_code'];
            } else {
                $ads_code = wp_kses_post( $instance['ads_code'] );
            }

            // Apply a filter to the ads code content for further customization
            $content = apply_filters( 'widget_custom_html_content', $ads_code, $instance, $this );
            ?>

            <div class="wpmotif-avs-widget<?php echo esc_attr( $ad_class ); ?>" style="justify-items:<?php echo esc_attr( $instance['content_alignment'] ); ?>;">
                <?php echo $content; ?>
            </div>

            <?php
        }
        do_action( 'blogtwist_after_ads_code' );

        $this->widget_end( $args );

        echo ob_get_clean();
    }

}
