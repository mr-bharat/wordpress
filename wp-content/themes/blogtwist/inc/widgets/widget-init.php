<?php
/* Theme Widget. */
require get_template_directory() . '/inc/widgets/widget-base.php';
require get_template_directory() . '/inc/widgets/class-recent-post.php';
require get_template_directory() . '/inc/widgets/class-category-grid.php';
require get_template_directory() . '/inc/widgets/class-ads-code.php';
require get_template_directory() . '/inc/widgets/class-tab-post.php';
require get_template_directory() . '/inc/widgets/class-youtube-video.php';
require get_template_directory() . '/inc/widgets/class-image-widget.php';
require get_template_directory() . '/inc/widgets/class-author.php';
require get_template_directory() . '/inc/widgets/class-social-menu.php';
require get_template_directory() . '/inc/widgets/class-cta.php';


/* Register site widgets */
if (!function_exists('blogtwist_widgets')) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function blogtwist_widgets()
    {
        register_widget('Blogtwist_Recent_Posts');
        register_widget('Blogtwist_Category_Grid');
        register_widget('Blogtwist_Ads_Code');
        register_widget('Blogtwist_Tab_Post');
        register_widget('Blogtwist_Youtube_Video');
        register_widget('Blogtwist_Image_Widget');
        register_widget('Blogtwist_Author_Widget');
        register_widget('Blogtwist_Social_Menu');
        register_widget('Blogtwist_CTA');
    }
endif;
add_action('widgets_init', 'blogtwist_widgets');


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blogtwist_widgets_init()
{

    $sidebar_args['sidebar'] = array(
        'name' => __('Sidebar', 'blogtwist'),
        'id' => 'sidebar',
        'description' => 'Add global sidebar widgets here.',
    );

    $sidebar_args['after_header'] = array(
        'name' => __('After Header', 'blogtwist'),
        'id' => 'after-header',
        'description' => __('Widgets placed in this region will be displayed below the header and above the main content.', 'blogtwist'),
    );


    $sidebar_args['homepage_before_posts'] = array(
        'name' => __('Homepage Before Posts', 'blogtwist'),
        'id' => 'homepage-before-posts',
        'description' => __('Widgets added to this region will appear on the homepage before posts listing.', 'blogtwist'),
    );

    $sidebar_args['homepage_after_posts'] = array(
        'name' => __('Homepage After Posts', 'blogtwist'),
        'id' => 'homepage-after-posts',
        'description' => __('Widgets added to this region will appear on the homepage after posts listing.', 'blogtwist'),
    );


    $sidebar_args['before_footer'] = array(
        'name' => __('Before Footer', 'blogtwist'),
        'id' => 'before-footer-widgetarea',
        'description' => __('Widgets added to this region will appear above the footer.', 'blogtwist'),
    );


    $footer_column = 3;

    $blogtwist_footer_widget_layout = get_theme_mod('blogtwist_footer_widget_layout', 'footer_layout_2');
    if ( $blogtwist_footer_widget_layout ) {
        switch ( $blogtwist_footer_widget_layout ) {
            case 'footer_layout_1':
                $footer_column = 4;
                break;
            case 'footer_layout_2':
                $footer_column = 3;
                 break;
            case 'footer_layout_3':
                $footer_column = 2;
                break;
            default:
            $footer_column = 4;

        }
    } else {
        $footer_column = 4;
    }

    $cols = intval(apply_filters('blogtwist_footer_widget_columns', $footer_column));

    for ($j = 1; $j <= $cols; $j++) {
        $footer = sprintf('footer_%d', $j);

        $footer_region_name = sprintf(__('Footer Column %1$d', 'blogtwist'), $j);
        $footer_region_description = sprintf(__('Widgets added here will appear in column %1$d of the footer.', 'blogtwist'), $j);

        $sidebar_args[$footer] = array(
            'name' => $footer_region_name,
            'id' => sprintf('footer-%d', $j),
            'description' => $footer_region_description,
        );
    }

    $sidebar_args['after_footer'] = array(
        'name' => __('After Footer', 'blogtwist'),
        'id' => 'after-footer-widgetarea',
        'description' => __('Widgets added to this region will appear after the footer and before sub-footer.', 'blogtwist'),
    );

    if ( class_exists( 'WooCommerce' ) ) {

        $sidebar_args['wc_sidebar'] = array(
            'name'        => __( 'WooCommerce Shop/Category Page Sidebar', 'blogtwist' ),
            'id'          => 'wc-sidebar',
            'description' => __( 'Widgets added to this region will appear on the shop or category page of woocommerce.', 'blogtwist' ),
        );

        $sidebar_args['wc_product_single_sidebar'] = array(
            'name'        => __( 'WooCommerce Product Page Sidebar', 'blogtwist' ),
            'id'          => 'wc-product-single-sidebar',
            'description' => __( 'Widgets added to this region will appear on detail page of a woocommerce product.', 'blogtwist' ),
        );

    }

    $sidebar_args = apply_filters('blogtwist_sidebar_args', $sidebar_args);

    foreach ($sidebar_args as $sidebar => $args) {
        $widget_tags = array(
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        );

        // Dynamically generated filter hooks. Allow changing widget wrapper and title tags. .
        $filter_hook = sprintf('blogtwist_%s_widget_tags', $sidebar);
        $widget_tags = apply_filters($filter_hook, $widget_tags);

        if (is_array($widget_tags)) {
            register_sidebar($args + $widget_tags);
        }
    }
}

add_action('widgets_init', 'blogtwist_widgets_init');
