<?php
/**
 * BlogTwist functions and definitions
 *
 * @package BlogTwist 1.0.0
 */
if (!defined('BLOGTWIST_VERSION')) {
    // Replace the version number of the theme on each release.
    define('BLOGTWIST_VERSION', '1.0.0');
}
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
    $content_width = 940; /* pixels */
}
if (!function_exists('blogtwist_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function blogtwist_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_theme_textdomain('blogtwist', get_template_directory() . '/languages');
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
        add_theme_support('title-tag');
        /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
        add_theme_support('post-thumbnails');
        // This theme uses wp_nav_menu() in three locations.
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'blogtwist'),
            'footer' => esc_html__('Footer Menu', 'blogtwist'),
            'social' => esc_html__('Social Menu', 'blogtwist'),
        ));
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ));
        /*
         * Enable support for custom logo.
         *
         */
        add_theme_support('custom-logo', array(
            'width' => 1360,
            'height' => 600,
            'flex-height' => true,
            'header-text' => array(
                'site-title',
                'site-description-text',
            )
        ));
        add_image_size('blogtwist-site-logo', 1360, 600, false);
        // Set up the WordPress core custom background feature.
        add_theme_support(
            'custom-background',
            apply_filters(
                'blogtwist_custom_background_args',
                array(
                    'default-color' => 'ffffff',
                    'default-image' => '',
                )
            )
        );
        /*
         * Enable support for Post Formats.
         * See http://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'gallery',
            'image',
            'audio',
            'video',
            'quote',
            'link'
        ));
        /*
         * Enable support for Visible Edit Shortcuts in the Customizer Preview
         *
         * @link https://make.wordpress.org/core/2016/11/10/visible-edit-shortcuts-in-the-customizer-preview/
         */
        add_theme_support('customize-selective-refresh-widgets');
        /*
         * Now some cleanup to remove features that we do not support
         */
        remove_theme_support('custom-header');
        // Theme supports wide images, galleries and videos.
        add_theme_support('align-wide');
        add_theme_support('responsive-embeds');
        add_theme_support('wp-block-styles');
    }
endif;
add_action('after_setup_theme', 'blogtwist_setup');
/**
 * Enqueue scripts and styles.
 */
function blogtwist_scripts_styles()
{
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

    // Load webfont loader and enqueue fonts.
    if (file_exists(get_theme_file_path('assets/font/wptt-webfont-loader.php'))) {
        require_once get_theme_file_path('assets/font/wptt-webfont-loader.php');
        if (function_exists('wptt_get_webfont_url')) {
            wp_enqueue_style(
                'blogtwist-fonts',
                wptt_get_webfont_url('https://fonts.googleapis.com/css2?family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap'),
                [],
                BLOGTWIST_VERSION
            );
        }
    }

    // Enqueue 404 styles
    if (is_404()) {
        $file_name = is_rtl() ? "404-style-rtl{$min}.css" : "404-style{$min}.css";
        wp_enqueue_style(
            'blogtwist-404-style',
            get_template_directory_uri() . '/assets/css/' . $file_name,
            [],
            BLOGTWIST_VERSION
        );
    }

    wp_enqueue_style(
        'blogtwist-style',
        get_template_directory_uri() . '/style.css',
        [],
        BLOGTWIST_VERSION
    );

    // Ensure RTL replacement for styles.
    wp_style_add_data('blogtwist-style', 'rtl', 'replace');

    // Enqueue navigation script.
    wp_enqueue_script(
        'blogtwist-navigation',
        get_template_directory_uri() . '/assets/js/navigation' . $min . '.js',
        [],
        BLOGTWIST_VERSION,
        true
    );

    wp_enqueue_script(
        'blogtwist-scripts',
        get_template_directory_uri() . '/assets/js/script.js',
        [],
        BLOGTWIST_VERSION,
        true
    );

    // Enqueue comment-reply script if applicable.
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'blogtwist_scripts_styles');

/**
 * Custom template tags for this theme.
 */
require_once trailingslashit(get_template_directory()) . 'inc/template-tags.php';
/**
 * Custom functions that act independently of the theme templates.
 */
require_once trailingslashit(get_template_directory()) . 'inc/extras.php';
/**
 * Load the Hybrid Media Grabber class
 */
require_once trailingslashit(get_template_directory()) . 'inc/hybrid-media-grabber.php';
/**
 * Customizer additions.
 */
require_once trailingslashit(get_template_directory()) . 'inc/customizer.php';
//widget-initialization.
require_once trailingslashit(get_template_directory()) . '/inc/widgets/widget-init.php';
// metabox
require_once trailingslashit(get_template_directory()) . '/inc/meta-box/category-meta.php';
require_once trailingslashit(get_template_directory()) . '/inc/meta-box/single-post-meta.php';
// Custom page walker.
require_once trailingslashit(get_template_directory()) . '/classes/class-walker-page.php';
require_once trailingslashit(get_template_directory()) . '/classes/class-svg-icons.php';
/**
 * Load theme dashboard
 */
if (is_admin()) {
    require trailingslashit(get_template_directory()) . '/inc/admin/class-dashboard-admin.php';
    require trailingslashit(get_template_directory()) . '/inc/admin/class-dashboard-notice.php';
    require trailingslashit(get_template_directory()) . '/inc/admin/class-dashboard.php';
}
