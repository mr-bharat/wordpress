<?php
/**
 * Theme Customizer
 * @package BlogTwist 1.0.0
 */


/**
 * Change some default texts and add our own custom settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blogtwist_customize_register( $wp_customize ) {
	// Add postMessage support for site title and tagline and title color.
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->get_control( 'display_header_text' )->label = esc_html__( 'Display Site Title &amp; Tagline', 'blogtwist' );

	// customiser category choice
	function blogtwist_get_category_choices()
	{
	    $categories = get_categories(array('hide_empty' => false));
	    $category_options = array();

	    foreach ($categories as $category) {
	        $category_options[$category->term_id] = $category->name;
	    }

	    return $category_options;
	}

	// Custom sanitize callback for author class
	function blogtwist_sanitize_layout($input) {
	    $valid = array('footer_layout_1', 'footer_layout_2', 'footer_layout_3', 'footer_layout_4');
	    return in_array($input, $valid) ? $input : 'footer_layout_1';
	}

	// Custom sanitize callback for layout choices
	function blogtwist_sanitize_author_meta($input) {
	    $valid = array('with_label', 'with_icon', 'with_avatar_image');
	    return in_array($input, $valid) ? $input : 'with_icon';
	}	

	function blogtwist_sanitize_sidebar($input) {
	    $valid = array('left_sidebar', 'right_sidebar', 'no_sidebar');
	    return in_array($input, $valid) ? $input : 'with_icon';
	}	
	// Custom sanitize callback for layout choices
	function blogtwist_sanitize_date_meta($input) {
	    $valid = array('classic', 'time_ago');
	    return in_array($input, $valid) ? $input : 'classic';
	}
	// Custom sanitize callback for layout choices
	function blogtwist_sanitize_category_color($input) {
	    $valid = array('none', 'has-background','has-text-color');
	    return in_array($input, $valid) ? $input : 'none';
	}	

	function blogtwist_sanitize_archive_category($input) {
	    $valid = array('archive-layout-grid' ,'archive-layout-default');
	    return in_array($input, $valid) ? $input : 'none';
	}


	// start of theme option panel
	$wp_customize->add_panel('theme_option_panel',
	    array(
	        'title' => esc_html__('Theme Options', 'blogtwist'),
	        'capability' => 'edit_theme_options',
	        'priority' => 30,
	    )
	);

	$wp_customize->add_section('blogtwist_header_section', array(
	    'title' => __('Header Options', 'blogtwist'),
	    'capability' => 'edit_theme_options',
	    'panel' => 'theme_option_panel',

	));

	$wp_customize->add_setting('blogtwist_enable_header_time', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_enable_header_time', array(
	    'label' => __('Enable Header Time', 'blogtwist'),
	    'section' => 'blogtwist_header_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('blogtwist_enable_header_date', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_enable_header_date', array(
	    'label' => __('Enable Header Date', 'blogtwist'),
	    'section' => 'blogtwist_header_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('blogtwist_enable_desktop_menu', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_enable_desktop_menu', array(
	    'label' => __('Enable Full-Width Menu on Desktop', 'blogtwist'),
	    'section' => 'blogtwist_header_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('blogtwist_enable_mobile_menu', array(
	    'default' => false,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_enable_mobile_menu', array(
	    'label' => __('Activate Mobile Menu on Desktop', 'blogtwist'),
	    'section' => 'blogtwist_header_section',
	    'type' => 'checkbox',
	));


	$wp_customize->add_setting('blogtwist_date_label_text', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('blogtwist_date_label_text', array(
	    'label' => __('Header Date Label', 'blogtwist'),
	    'section' => 'blogtwist_header_section',
	    'type' => 'text',
	));

	$wp_customize->add_setting('blogtwist_header_date_format', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('blogtwist_header_date_format', array(
	    'label' => __('Header Date Format', 'blogtwist'),
        'description' => sprintf( wp_kses( __( '<a href="%s" target="_blank">Date and Time Formatting Documentation</a>.', 'blogtwist' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://wordpress.org/support/article/formatting-date-and-time' ) ),
	    
	    'section' => 'blogtwist_header_section',
	    'type' => 'text',
	));


	$wp_customize->add_section('blogtwist_cursor_section', array(
	    'title' => __('Cursor Options', 'blogtwist'),
	    'capability' => 'edit_theme_options',
	    'panel' => 'theme_option_panel',

	));

	$wp_customize->add_setting('blogtwist_enable_cursor', array(
	    'default' => false,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_enable_cursor', array(
	    'label' => __('Enable Cursor', 'blogtwist'),
	    'section' => 'blogtwist_cursor_section',
	    'type' => 'checkbox',
	));



	$wp_customize->add_section('blogtwist_sticky_sidebar_section', array(
	    'title' => __('Sticky Sidebar Options', 'blogtwist'),
	    'capability' => 'edit_theme_options',
	    'panel' => 'theme_option_panel',

	));

	$wp_customize->add_setting('blogtwist_enable_sticky_sidebar', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_enable_sticky_sidebar', array(
	    'label' => __('Enable Sticky Sidebar', 'blogtwist'),
	    'section' => 'blogtwist_sticky_sidebar_section',
	    'type' => 'checkbox',
	));

	//Single Related Post
	$wp_customize->add_section('blogtwist_single_post_section', array(
	    'title' => __('Single Post Options', 'blogtwist'),
	    'capability' => 'edit_theme_options',
	    'panel' => 'theme_option_panel',

	));


	$wp_customize->add_setting('enable_single_author_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_single_author_meta', array(
	    'label' => __('Enable Single Meta Author', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('select_single_sidebar', array(
	    'default' => 'right_sidebar',
	    'sanitize_callback' => 'blogtwist_sanitize_sidebar',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_single_sidebar', array(
	    'label' => __('Select Sidebar Layout', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_single_sidebar',
	    'type' => 'select',
	    'choices' => array(
	        'left_sidebar'   => __( 'Left Sidebar', 'blogtwist' ),
	        'right_sidebar'   => __( 'Right Sidebar', 'blogtwist' ),
	        'no_sidebar'   => __( 'No Sidebar', 'blogtwist' ),
	    ),
	)));

	$wp_customize->add_setting('select_author_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_author_meta', array(
	    'label' => __('Select Author Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_author_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	        'with_avatar_image'   => __( 'With Avatar Image', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('single_author_meta_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('single_author_meta_label', array(
	    'label' => __('Author Label', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('enable_single_date_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_single_date_meta', array(
	    'label' => __('Enable Single Meta Date', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));
	$wp_customize->add_setting('select_single_date_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_single_date_meta', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_single_date_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('single_date_meta_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('single_date_meta_label', array(
	    'label' => __('Date Label', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('select_date_format', array(
	    'default' => 'classic',
	    'sanitize_callback' => 'blogtwist_sanitize_date_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_date_format', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_date_format',
	    'type' => 'select',
	    'choices' => array(
			'classic'   => __( 'Classic', 'blogtwist' ),
			'time_ago' => __( 'Time Ago', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('enable_single_meta_category', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_single_meta_category', array(
	    'label' => __('Enable Single Meta Category', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));
	

	$wp_customize->add_setting('single_category_number', array(
	    'default' => '2',
	    'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control('single_category_number', array(
	    'label' => __('Select Category Display Limit', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'number',
	));	
	
	$wp_customize->add_setting('single_category_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('single_category_label', array(
	    'label' => __('Category Label', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('select_category_color_style', array(
	    'default' => 'none',
	    'sanitize_callback' => 'blogtwist_sanitize_category_color',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_category_color_style', array(
	    'label' => __('Select Category Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_category_color_style',
	    'type' => 'select',
	    'choices' => array(
	    	'none'   => __( 'None', 'blogtwist' ),
	    	'has-background'   => __( 'Has background', 'blogtwist' ),
	    	'has-text-color'   => __( 'Has text color', 'blogtwist' ),
	    ),
	)));


	$wp_customize->add_setting('enable_tag_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_tag_meta', array(
	    'label' => __('Enable Single Tag Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));
	

	$wp_customize->add_setting('single_tag_meta_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('single_tag_meta_label', array(
	    'label' => __('Tags Label', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));
	
	$wp_customize->add_setting('enable_read_time', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_read_time', array(
	    'label' => __('Enable Read Time', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting( 'simple_divider', array(
	    'sanitize_callback' => '__return_false',
	));

	// Add the divider control
	$wp_customize->add_control( 'simple_divider', array(
	    'section'     => 'blogtwist_single_post_section',
	    'type'        => 'hidden', 
	    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
	));

	$wp_customize->add_setting('blogtwist_enable_single_related_post', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_enable_single_related_post', array(
	    'label' => __('Enable Single Related Post', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('blogtwist_single_related_post_title', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('blogtwist_single_related_post_title', array(
	    'label' => __('Section Title', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));

	$wp_customize->add_setting('blogtwist_number_single_related_posts', array(
	    'default' => 6,
	    'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control('blogtwist_number_single_related_posts', array(
	    'label' => __('Number of Posts', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'number',
	    'input_attrs' => array(
	        'min' => 3,
	        'max' => 12,
	    ),
	));

	$wp_customize->add_setting('blogtwist_select_single_related_posts_category', array(
	    'default' => '',
	    'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'blogtwist_select_single_related_posts_category', array(
	    'label' => __('Select Post Category', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'blogtwist_select_single_related_posts_category',
	    'type' => 'select',
	    'choices' => blogtwist_get_category_choices(),
	)));

	$wp_customize->add_setting('enable_single_related_posts_author_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_single_related_posts_author_meta', array(
	    'label' => __('Enable Author Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('select_single_related_posts_author_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_single_related_posts_author_meta', array(
	    'label' => __('Select Author Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_single_related_posts_author_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	        'with_avatar_image'   => __( 'With Avatar Image', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('single_related_posts_author_meta_label', array(
	    'default' => 'By',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('single_related_posts_author_meta_label', array(
	    'label' => __('Author Label', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('enable_single_related_posts_date_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_single_related_posts_date_meta', array(
	    'label' => __('Enable Meta Date', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));
	$wp_customize->add_setting('select_single_related_posts_date_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_single_related_posts_date_meta', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_single_related_posts_date_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('single_related_posts_date_meta_label', array(
	    'default' => 'On',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('single_related_posts_date_meta_label', array(
	    'label' => __('Date Label', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('select_single_related_posts_date_format', array(
	    'default' => 'classic',
	    'sanitize_callback' => 'blogtwist_sanitize_date_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_single_related_posts_date_format', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_single_related_posts_date_format',
	    'type' => 'select',
	    'choices' => array(
			'classic'   => __( 'Classic', 'blogtwist' ),
			'time_ago' => __( 'Time Ago', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('enable_single_related_posts_meta_category', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_single_related_posts_meta_category', array(
	    'label' => __('Enable Meta Category', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));
	
	$wp_customize->add_setting('single_related_posts_category_number', array(
	    'default' => '2',
	    'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control('single_related_posts_category_number', array(
	    'label' => __('Select Category Display Limit', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'number',
	));	
	
	$wp_customize->add_setting('single_related_posts_category_label', array(
	    'default' => 'In',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('single_related_posts_category_label', array(
	    'label' => __('Category Label', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('select_single_related_posts_category_color_style', array(
	    'default' => 'none',
	    'sanitize_callback' => 'blogtwist_sanitize_category_color',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_single_related_posts_category_color_style', array(
	    'label' => __('Select Category Meta', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'settings' => 'select_single_related_posts_category_color_style',
	    'type' => 'select',
	    'choices' => array(
	    	'none'   => __( 'None', 'blogtwist' ),
	    	'has-background'   => __( 'Has background', 'blogtwist' ),
	    	'has-text-color'   => __( 'Has text color', 'blogtwist' ),
	    ),
	)));
	
	$wp_customize->add_setting('enable_single_related_posts_read_time', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_single_related_posts_read_time', array(
	    'label' => __('Enable Read Time', 'blogtwist'),
	    'section' => 'blogtwist_single_post_section',
	    'type' => 'checkbox',
	));


// widget option
	$wp_customize->add_section('blogtwist_widget_option_section', array(
	    'title' => __('Widget Options', 'blogtwist'),
	    'capability' => 'edit_theme_options',
	    'panel' => 'theme_option_panel',

	));

	
	$wp_customize->add_setting( 'simple_widget_option_header_1', array(
	    'sanitize_callback' => '__return_false',
	));

	// Add the header-only control
	$wp_customize->add_control( 
	    new WP_Customize_Control(
	        $wp_customize,
	        'simple_widget_option_header_1',
	        array(
	            'section'     => 'blogtwist_widget_option_section',
	            'type'        => 'image',
	            'description' => '<h2 style="margin: 15px 0; font-size: 1.5em; font-weight: bold;">After Header Widget Option</h2>',
	        )
	    )
	);



	$wp_customize->add_setting('blogtwist_homepage_widget_section', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('blogtwist_homepage_widget_section', array(
	    'label' => __('Enable After Header Widgetarea on Homepage Only', 'blogtwist'),
	    'section' => 'blogtwist_widget_option_section',
	    'type' => 'checkbox',
	));


	$wp_customize->add_setting( 'simple_widget_option_divider_1', array(
	    'sanitize_callback' => '__return_false',
	));

	// Add the divider control
	$wp_customize->add_control( 'simple_widget_option_divider_1', array(
	    'section'     => 'blogtwist_widget_option_section',
	    'type'        => 'hidden', 
	    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
	));



	// archive section setting
	$wp_customize->add_section('blogtwist_archive_section', array(
	    'title' => __('Archive Options', 'blogtwist'),
	    'capability' => 'edit_theme_options',
	    'panel' => 'theme_option_panel',

	));



	$wp_customize->add_setting('select_archive_layout', array(
	    'default' => 'archive-layout-grid',
	    'sanitize_callback' => 'blogtwist_sanitize_archive_category',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_archive_layout', array(
	    'label' => __('Select Archive Layout', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'settings' => 'select_archive_layout',
	    'type' => 'select',
	    'choices' => array(
	    	'archive-layout-grid'   => __( 'Archive Layout Grid', 'blogtwist' ),
	    	'archive-layout-default'   => __( 'Archive Layout Default', 'blogtwist' ),
	    ),
	)));

	$wp_customize->add_setting('enable_archive_meta_category', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_archive_meta_category', array(
	    'label' => __('Enable Meta Category', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'checkbox',
	));
	
	$wp_customize->add_setting('archive_category_label', array(
	    'default' => 'In',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('archive_category_label', array(
	    'label' => __('Category Label', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'text',
	));


	$wp_customize->add_setting('archive_category_number', array(
	    'default' => '2',
	    'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control('archive_category_number', array(
	    'label' => __('Number of Category', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'number',
	));	

	$wp_customize->add_setting('select_archive_category_color_style', array(
	    'default' => 'none',
	    'sanitize_callback' => 'blogtwist_sanitize_category_color',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_archive_category_color_style', array(
	    'label' => __('Select Category Meta', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'settings' => 'select_archive_category_color_style',
	    'type' => 'select',
	    'choices' => array(
	    	'none'   => __( 'None', 'blogtwist' ),
	    	'has-background'   => __( 'Has background', 'blogtwist' ),
	    	'has-text-color'   => __( 'Has text color', 'blogtwist' ),
	    ),
	)));

	$wp_customize->add_setting( 'simple_divider_1', array(
	    'sanitize_callback' => '__return_false',
	));

	// Add the divider control
	$wp_customize->add_control( 'simple_divider_1', array(
	    'section'     => 'blogtwist_archive_section',
	    'type'        => 'hidden', 
	    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
	));
	$wp_customize->add_setting('enable_archive_post_excerpt', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_archive_post_excerpt', array(
	    'label' => __('Enable Post Excerpt', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'checkbox',
	));
	$wp_customize->add_setting('enable_archive_author_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_archive_author_meta', array(
	    'label' => __('Enable Author Meta', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting('select_archive_author_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_archive_author_meta', array(
	    'label' => __('Select Author Meta', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'settings' => 'select_archive_author_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	        'with_avatar_image'   => __( 'With Avatar Image', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('archive_author_meta_label', array(
	    'default' => 'By',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('archive_author_meta_label', array(
	    'label' => __('Author Label', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting( 'simple_divider_2', array(
	    'sanitize_callback' => '__return_false',
	));

	// Add the divider control
	$wp_customize->add_control( 'simple_divider_2', array(
	    'section'     => 'blogtwist_archive_section',
	    'type'        => 'hidden', 
	    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
	));


	$wp_customize->add_setting('enable_archive_date_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_archive_date_meta', array(
	    'label' => __('Enable Meta Date', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'checkbox',
	));
	$wp_customize->add_setting('select_archive_date_meta', array(
	    'default' => 'with_icon',
	    'sanitize_callback' => 'blogtwist_sanitize_author_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_archive_date_meta', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'settings' => 'select_archive_date_meta',
	    'type' => 'select',
	    'choices' => array(
	        'with_label'   => __( 'With Label', 'blogtwist' ),
	        'with_icon'   => __( 'With Icon', 'blogtwist' ),
	    ),
	)));
	$wp_customize->add_setting('archive_date_meta_label', array(
	    'default' => 'On',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('archive_date_meta_label', array(
	    'label' => __('Date Label', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'text',
	));
	$wp_customize->add_setting('select_archive_date_format', array(
	    'default' => 'classic',
	    'sanitize_callback' => 'blogtwist_sanitize_date_meta',
	));

	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'select_archive_date_format', array(
	    'label' => __('Select Date Meta', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'settings' => 'select_archive_date_format',
	    'type' => 'select',
	    'choices' => array(
			'classic'   => __( 'Classic', 'blogtwist' ),
			'time_ago' => __( 'Time Ago', 'blogtwist' ),
	    ),
	)));

	
	$wp_customize->add_setting( 'simple_divider_3', array(
	    'sanitize_callback' => '__return_false',
	));

	// Add the divider control
	$wp_customize->add_control( 'simple_divider_3', array(
	    'section'     => 'blogtwist_archive_section',
	    'type'        => 'hidden', 
	    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
	));



	$wp_customize->add_setting('enable_archive_read_time', array(
	    'default' => false,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_archive_read_time', array(
	    'label' => __('Enable Read Time', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'checkbox',
	));

	$wp_customize->add_setting( 'simple_divider_4', array(
	    'sanitize_callback' => '__return_false',
	));

	// Add the divider control
	$wp_customize->add_control( 'simple_divider_4', array(
	    'section'     => 'blogtwist_archive_section',
	    'type'        => 'hidden', 
	    'description' => '<hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">',
	));


	$wp_customize->add_setting('enable_archive_tag_meta', array(
	    'default' => true,
	    'sanitize_callback' => 'wp_validate_boolean',
	));

	$wp_customize->add_control('enable_archive_tag_meta', array(
	    'label' => __('Enable Archive Tag Meta', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'checkbox',
	));
	

	$wp_customize->add_setting('archive_tag_meta_label', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('archive_tag_meta_label', array(
	    'label' => __('Tags Label', 'blogtwist'),
	    'section' => 'blogtwist_archive_section',
	    'type' => 'text',
	));
	
	require_once trailingslashit( get_template_directory() ) . 'inc/customizer-upsell.php';
	require_once trailingslashit( get_template_directory() ) . 'inc/header-search.php';
	require_once trailingslashit( get_template_directory() ) . 'inc/footer-recommended.php';
}

add_action( 'customize_register', 'blogtwist_customize_register', 15 );

/**
 * Assets that will be loaded for the customizer sidebar
 */
function blogtwist_customizer_assets() {
    // Determine whether to use minified or unminified files based on SCRIPT_DEBUG.
    $min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Determine the file name based on the text direction (RTL or LTR).
    $file_name = 'customizer-style' . ( is_rtl() ? '-rtl' : '' ) . $min . '.css';

    // Enqueue the customizer style.
    wp_enqueue_style(
        'blogtwist_customizer_style',
        get_template_directory_uri() . '/assets/css/' . $file_name,
        array(),
        '1.0.0',
        'all'
    );
}
add_action( 'customize_controls_enqueue_scripts', 'blogtwist_customizer_assets' );


/**
 * JavaScript that handles the Customizer AJAX logic
 * This will be added in the preview part
 */
function blogtwist_customizer_preview_assets() {
	wp_enqueue_script( 'blogtwist_customizer_preview', get_template_directory_uri() . '/assets/js/customizer-preview.js', array( 'customize-preview' ), '1.0.0', true );
}
add_action( 'customize_preview_init', 'blogtwist_customizer_preview_assets' );