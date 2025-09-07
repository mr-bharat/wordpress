<?php
// footer recommended
$wp_customize->add_section('blogtwist_footer_recommended_section', array(
    'title' => __('Footer Latest Post', 'blogtwist'),
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',

));

$wp_customize->add_setting('blogtwist_enable_footer_latest', array(
    'default' => true,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('blogtwist_enable_footer_latest', array(
    'label' => __('Enable Footer Latest Post', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'checkbox',
));

$wp_customize->add_setting('blogtwist_enable_footer_latest_on_homepage', array(
    'default' => false,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('blogtwist_enable_footer_latest_on_homepage', array(
    'label' => __('Show Footer Latest Post on Homepage Only', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'checkbox',
));

$wp_customize->add_setting('footer_recommended_section_title', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control('footer_recommended_section_title', array(
    'label' => __('Footer Recommended Title', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'text',
));
$wp_customize->add_setting('blogtwist_select_footer_recommended_category', array(
    'default' => '',
    'sanitize_callback' => 'absint',
));

$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'blogtwist_select_footer_recommended_category', array(
    'label' => __('Select Post Category', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'settings' => 'blogtwist_select_footer_recommended_category',
    'type' => 'select',
    'choices' => blogtwist_get_category_choices(),
)));

$wp_customize->add_setting( 'simple_footer_recommended_divider', array(
    'sanitize_callback' => '__return_false',
));

// Add the divider control
$wp_customize->add_control( 'simple_footer_recommended_divider', array(
    'section'     => 'blogtwist_footer_recommended_section',
    'type'        => 'hidden', 
    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
));
$wp_customize->add_setting('enable_footer_latest_author_meta', array(
    'default' => true,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('enable_footer_latest_author_meta', array(
    'label' => __('Enable Author Meta', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'checkbox',
));

$wp_customize->add_setting('select_footer_recommended_author_meta', array(
    'default' => 'with_icon',
    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
));

$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_footer_recommended_author_meta', array(
    'label' => __('Select Author Meta', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'settings' => 'select_footer_recommended_author_meta',
    'type' => 'select',
    'choices' => array(
        'with_label'   => __( 'With Label', 'blogtwist' ),
        'with_icon'   => __( 'With Icon', 'blogtwist' ),
        'with_avatar_image'   => __( 'With Avatar Image', 'blogtwist' ),
    ),
)));
$wp_customize->add_setting('footer_recommended_author_meta_label', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control('footer_recommended_author_meta_label', array(
    'label' => __('Author Label', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'text',
));

$wp_customize->add_setting( 'simple_footer_recommended_divider_1', array(
    'sanitize_callback' => '__return_false',
));

// Add the divider control
$wp_customize->add_control( 'simple_footer_recommended_divider_1', array(
    'section'     => 'blogtwist_footer_recommended_section',
    'type'        => 'hidden', 
    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
));

$wp_customize->add_setting('enable_footer_latest_date_meta', array(
    'default' => true,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('enable_footer_latest_date_meta', array(
    'label' => __('Enable Meta Date', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'checkbox',
));
$wp_customize->add_setting('select_footer_recommended_date_meta', array(
    'default' => 'with_icon',
    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
));

$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_footer_recommended_date_meta', array(
    'label' => __('Select Date Meta', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'settings' => 'select_footer_recommended_date_meta',
    'type' => 'select',
    'choices' => array(
        'with_label'   => __( 'With Label', 'blogtwist' ),
        'with_icon'   => __( 'With Icon', 'blogtwist' ),
    ),
)));
$wp_customize->add_setting('footer_recommended_date_meta_label', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control('footer_recommended_date_meta_label', array(
    'label' => __('Date Label', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'text',
));
$wp_customize->add_setting('select_footer_recommended_date_format', array(
    'default' => 'classic',
    'sanitize_callback' => 'blogtwist_sanitize_date_meta',
));

$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_footer_recommended_date_format', array(
    'label' => __('Select Date Meta', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'settings' => 'select_footer_recommended_date_format',
    'type' => 'select',
    'choices' => array(
		'classic'   => __( 'Classic', 'blogtwist' ),
		'time_ago' => __( 'Time Ago', 'blogtwist' ),
    ),
)));

$wp_customize->add_setting( 'simple_footer_recommended_divider_2', array(
    'sanitize_callback' => '__return_false',
));

// Add the divider control
$wp_customize->add_control( 'simple_footer_recommended_divider_2', array(
    'section'     => 'blogtwist_footer_recommended_section',
    'type'        => 'hidden', 
    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
));

$wp_customize->add_setting('enable_footer_latest_meta_category', array(
    'default' => true,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('enable_footer_latest_meta_category', array(
    'label' => __('Enable Meta Category', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'checkbox',
));

$wp_customize->add_setting('footer_recommended_category_number', array(
    'default' => '2',
    'sanitize_callback' => 'absint',
));

$wp_customize->add_control('footer_recommended_category_number', array(
    'label' => __('Nummber of Category', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'number',
)); 

$wp_customize->add_setting('footer_recommended_category_label', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control('footer_recommended_category_label', array(
    'label' => __('Category Label', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'text',
));
$wp_customize->add_setting('select_footer_recommended_category_color_style', array(
    'default' => 'none',
    'sanitize_callback' => 'blogtwist_sanitize_category_color',
));

$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_footer_recommended_category_color_style', array(
    'label' => __('Select Cateory Meta', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'settings' => 'select_footer_recommended_category_color_style',
    'type' => 'select',
    'choices' => array(
    	'none'   => __( 'None', 'blogtwist' ),
    	'has-background'   => __( 'Has background', 'blogtwist' ),
    	'has-text-color'   => __( 'Has text color', 'blogtwist' ),
    ),
)));

$wp_customize->add_setting( 'simple_footer_recommended_divider_3', array(
    'sanitize_callback' => '__return_false',
));

// Add the divider control
$wp_customize->add_control( 'simple_footer_recommended_divider_3', array(
    'section'     => 'blogtwist_footer_recommended_section',
    'type'        => 'hidden', 
    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
));

$wp_customize->add_setting('enable_footer_latest_read_time', array(
    'default' => false,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('enable_footer_latest_read_time', array(
    'label' => __('Enable Read Time', 'blogtwist'),
    'section' => 'blogtwist_footer_recommended_section',
    'type' => 'checkbox',
));

$wp_customize->add_setting( 'simple_footer_recommended_divider_4', array(
    'sanitize_callback' => '__return_false',
));

// Add the divider control
$wp_customize->add_control( 'simple_footer_recommended_divider_4', array(
    'section'     => 'blogtwist_footer_recommended_section',
    'type'        => 'hidden', 
    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
));


// footer section setting
$wp_customize->add_section('blogtwist_footer_section', array(
    'title' => __('Footer Options', 'blogtwist'),
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',

));

$wp_customize->add_setting('enable_footer_widget', array(
    'default' => true,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('enable_footer_widget', array(
    'label' => __('Enable Footer Widget Area', 'blogtwist'),
    'section' => 'blogtwist_footer_section',
    'type' => 'checkbox',
));


$wp_customize->add_setting('blogtwist_footer_widget_layout', array(
    'default' => 'footer_layout_2',
    'sanitize_callback' => 'blogtwist_sanitize_layout',
));

$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'blogtwist_footer_widget_layout', array(
    'label' => __('Select Footer Widget Layout', 'blogtwist'),
    'section' => 'blogtwist_footer_section',
    'settings' => 'blogtwist_footer_widget_layout',
    'type' => 'select',
    'choices' => array(
        'footer_layout_1' => __('Four Columns', 'blogtwist'),
        'footer_layout_2' => __('Three Columns', 'blogtwist'),
        'footer_layout_3' => __('Two Columns', 'blogtwist'),
        'footer_layout_4' => __('One Columns', 'blogtwist'),
    ),
)));
