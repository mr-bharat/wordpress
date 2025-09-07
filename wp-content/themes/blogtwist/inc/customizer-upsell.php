<?php
// upsell featues
class Blogtwist_Section_Features_List extends WP_Customize_Section {
    /**
     * Control Type.
     */
    public $type              = 'section-features-list';
    public $features_list     = array();
    public $is_upsell_feature = true;
    public $upsell_link       = 'https://wpmotif.com/theme/blogtwist/#choose-pricing-plan';
    public $upsell_text       = '';
    public $button_link       = '';
    public $button_text       = '';
    public $class             = '';

    /**
     * Add custom parameters to pass to the JS via JSON.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function json() {
        $json = parent::json();

        $json['title']             = $this->title;
        $json['description']       = $this->description;
        $json['features_list']     = $this->features_list;
        $json['is_upsell_feature'] = $this->is_upsell_feature;
        $json['upsell_link']       = $this->upsell_link;
        $json['upsell_text']       = __( 'Upgrade Now', 'blogtwist' );
        $json['button_link']       = $this->button_link;
        $json['button_text']       = $this->button_text;
        $json['class']             = $this->class;

        return $json;
    }

    /**
     * Outputs the Underscore.js template.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    protected function render_template() {
        ?>


        <li id="accordion-section-{{ data.id }}" class="accordion-section control-section customize-control-upsell control-section-{{ data.type }} {{data.class}}">

            <# if ( data.title ) { #>
                <h3>{{ data.title }}</h3>
            <# } #>

            <# if ( data.description ) { #>
                <span class="feature-desc">{{{ data.description }}}</span>
            <# } #>

            <# if ( !_.isEmpty(data.features_list) ) { #>
                <ul class="blogtwist-bullet-point">
                    <# _.each( data.features_list, function(key, value) { #>
                        <li><span class="dashicons dashicons-arrow-right-alt2"></span>{{{ key }}}</li>
                    <# }) #>
                </ul>
            <# } #>

            <# if ( data.is_upsell_feature ) { #>
                <a href="{{ data.upsell_link }}" role="button" class="button upgrade-now" target="_blank">{{ data.upsell_text }}</a>
            <# } else { #>
                <# if ( data.button_text && data.button_link ) { #>
                    <a href="{{ data.button_link }}" role="button" class="button upgrade-now" target="_blank">{{ data.button_text }}</a>
                <# } #>
            <# } #>

        </li>
        <?php
    }
}

class Blogtwist_Upsell extends WP_Customize_Control {

    /**
     * Control type.
     *
     * @access public
     * @var string
     */
    public $type = 'upsell';

    /**
     * Displays the control content.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function render_content() {
        ?>
        <div>
            <div class="customize-control">
                <h3><?php esc_html_e( 'Explore Our Premium Features', 'blogtwist' ); ?></h3>
                <ul class="theme-features">
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Dedicated Premium Support', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'More Color Options', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Font Options', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Dark Mode Feature', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Additional Widget Areas', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Additional Widgets', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Extended Widget Options', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Additional Customizer Sections', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Webmaster Tools', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Mailchimp Topbar', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Post Format Support', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Remove Footer Credit', 'blogtwist' ); ?></li>
                    <li><span class="dashicons dashicons-plus"></span><?php esc_html_e( 'Many More ..', 'blogtwist' ); ?></li>
                </ul>
                <a href="<?php echo esc_url( 'https://wpmotif.com/theme/blogtwist/#choose-pricing-plan' ); ?>" target="_blank" class="button upgrade-now"><?php esc_html_e( 'Upgrade Now', 'blogtwist' ); ?></a>
            </div>
            <div class="customize-control">
                <h3><?php esc_html_e( 'Need Support?', 'blogtwist' ); ?></h3>
                <p><?php esc_html_e( 'If you have any questions about the theme, please don\'t hesitate to reach out to us.', 'blogtwist' ); ?></p>

                <a href="<?php echo esc_url( 'https://wpmotif.com/support/' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'Contact Us', 'blogtwist' ); ?></a>
            </div>
        </div>
        <?php
    }
}

// Add a new section for upselling
$wp_customize->add_section(
    'theme_upsell',
    array(
        'title'    => esc_html__( 'Unlock More Features', 'blogtwist' ),
        'priority' => 1,
    )
);
$wp_customize->add_setting(
    'theme_pro_features',
    array(
        'sanitize_callback' => '__return_true',
    )
);
$wp_customize->add_control(
    new Blogtwist_Upsell(
        $wp_customize,
        'theme_pro_features',
        array(
            'section' => 'theme_upsell',
            'type'    => 'upsell',
        )
    )
);

$wp_customize->add_section(
    new Blogtwist_Section_Features_List(
        $wp_customize,
        'theme_header_features',
        array(
            'title'         => esc_html__( 'More Options on BlogTwist Pro!', 'blogtwist' ),
            'features_list' => array(
                esc_html__( 'Dark mode options', 'blogtwist' ),
                esc_html__( 'Menu badge options', 'blogtwist' ),
                esc_html__( '17+ Preloader options', 'blogtwist' ),
                esc_html__( 'More color options', 'blogtwist' ),
                esc_html__( '404 page options', 'blogtwist' ),
                esc_html__( '... and many other premium features', 'blogtwist' ),
            ),
            'panel'         => 'header_options_panel',
            'priority'      => 999,
        )
    )
);