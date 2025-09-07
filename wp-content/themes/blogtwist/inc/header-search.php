<?php
$wp_customize->add_section('blogtwist_search_post_section', array(
    'title' => __('Search Options', 'blogtwist'),
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
    'priority' => 5,

));

$wp_customize->add_setting('blogtwist_enable_post_search', array(
    'default' => true,
    'sanitize_callback' => 'wp_validate_boolean',
));

$wp_customize->add_control('blogtwist_enable_post_search', array(
    'label' => __('Enable Post On Search', 'blogtwist'),
    'section' => 'blogtwist_search_post_section',
    'type' => 'checkbox',
));

$wp_customize->add_setting('blogtwist_custom_title', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control('blogtwist_custom_title', array(
    'label' => __('Section Post Title', 'blogtwist'),
    'section' => 'blogtwist_search_post_section',
    'type' => 'text',
));

$wp_customize->add_setting('blogtwist_number_of_posts', array(
    'default' => 4,
    'sanitize_callback' => 'absint',
));

$wp_customize->add_control('blogtwist_number_of_posts', array(
    'label' => __('Number of Posts', 'blogtwist'),
    'section' => 'blogtwist_search_post_section',
    'type' => 'number',
    'input_attrs' => array(
        'min' => 1,
        'max' => 8,
    ),
));

$wp_customize->add_setting('blogtwist_select_category', array(
    'default' => '',
    'sanitize_callback' => 'absint',
));

$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'blogtwist_select_category', array(
    'label' => __('Select Post Category', 'blogtwist'),
    'section' => 'blogtwist_search_post_section',
    'settings' => 'blogtwist_select_category',
    'type' => 'select',
    'choices' => blogtwist_get_category_choices(),
)));

	$wp_customize->add_setting('enable_search_author_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_search_author_meta', array(
	    'label' => __('Enable Meta Author', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('select_search_author_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_search_author_meta', array(
	    'label' => __('Select Author Meta', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'settings' => 'select_search_author_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	        'with_avatar_image'   => __( 'With Avatar Image', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('search_author_meta_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('search_author_meta_label', array(
	    'label' => __('Author Label', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('enable_search_date_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_search_date_meta', array(
	    'label' => __('Enable Search Meta Date', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'type' => 'checkbox',
	));
	$wp_customize->add_setting('select_search_date_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_search_date_meta', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'settings' => 'select_search_date_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('search_date_meta_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('search_date_meta_label', array(
	    'label' => __('Date Label', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('select_search_date_format', array(
	    'default' => 'classic',
	    'sanitize_callback' => 'blogtwist_sanitize_date_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_search_date_format', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'settings' => 'select_search_date_format',
	    'type' => 'select',
	    'choices' => array(
			'classic'   => __( 'Classic', 'blogtwist' ),
			'time_ago' => __( 'Time Ago', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('enable_search_meta_category', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_search_meta_category', array(
	    'label' => __('Enable Single Meta Category', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'type' => 'checkbox',
	));
	
	$wp_customize->add_setting('search_category_number', array(
	    'default' => '2',
	    'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control('search_category_number', array(
	    'label' => __('Select Category Display Limit', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'type' => 'number',
	));	
	
	$wp_customize->add_setting('search_category_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('search_category_label', array(
	    'label' => __('Category Label', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('select_search_category_color_style', array(
	    'default' => 'none',
	    'sanitize_callback' => 'blogtwist_sanitize_category_color',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_search_category_color_style', array(
	    'label' => __('Select Category Meta', 'blogtwist'),
	    'section' => 'blogtwist_search_post_section',
	    'settings' => 'select_search_category_color_style',
	    'type' => 'select',
	    'choices' => array(
	    	'none'   => __( 'None', 'blogtwist' ),
	    	'has-background'   => __( 'Has background', 'blogtwist' ),
	    	'has-text-color'   => __( 'Has text color', 'blogtwist' ),
	    ),
	)));

