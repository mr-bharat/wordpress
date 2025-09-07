<?php
/**
 * The header for our theme.
 * Displays all of the <head> section and everything up till <div id="content">
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">
    <?php $blogtwist_enable_cursor = get_theme_mod('blogtwist_enable_cursor', false);
    if ($blogtwist_enable_cursor) { ?>
        <div class="circle-cursor circle-cursor-outer"></div>
        <div class="circle-cursor circle-cursor-inner">
            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.06055 0H20.0605V18H17.0605V5.12155L2.12132 20.0608L0 17.9395L14.9395 3H2.06055V0Z"/>
            </svg>
        </div>
    <?php } ?>
    <div id="wpmotif-preloader">
        <div class="wpmotif-preloader-wrapper">
            <div class="spinner"></div>
        </div>
    </div>
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'blogtwist'); ?></a>
    <?php get_template_part('template-parts/header/topbar'); ?>
    <header id="masthead" class="site-header" role="banner">
        <div class="site-branding">
            <div class="site-wrapper">
                <?php the_custom_logo(); ?>
                <?php
                // on the front page and home page we use H1 for the title
                echo (is_front_page() && is_home()) ? '<h1 class="site-title">' : '<div class="site-title">'; ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <span class="hide-on-desktop"><?php bloginfo('name'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="site-branding-svg hide-on-mobile hide-on-tablet">
                        <text x="50%" y="0.82em" stroke="var(--theme-background-color)" text-anchor="middle" stroke-width="<?php echo esc_attr(get_theme_mod('blogtwist_site_title_outline', '3')); ?>">
                            <?php bloginfo('name'); ?>
                        </text>
                    </svg>
                </a>
                <?php echo (is_front_page() && is_home()) ? '</h1>' : '</div>'; ?>
                <div class="site-description">
                    <span class="site-description-text"><?php bloginfo('description'); ?></span>
                </div>
            </div>
        </div>
        <?php
        $blogtwist_enable_desktop_menu = get_theme_mod('blogtwist_enable_desktop_menu', true);
        if (has_nav_menu('primary')) {
            ?>
            <nav id="header-navigation" class="site-navigation main-navigation hide-on-tablet hide-on-mobile <?php if (!$blogtwist_enable_desktop_menu) { echo 'hide-on-desktop'; } ?>" role="navigation">
                <div class="site-wrapper">
                    <ul class="primary-menu reset-list-style">
                        <?php
                        if (has_nav_menu('primary')) {
                            wp_nav_menu(
                                array(
                                    'container' => '',
                                    'items_wrap' => '%3$s',
                                    'theme_location' => 'primary',
                                )
                            );
                        }
                        ?>
                    </ul>
                </div>
            </nav>
            <?php
        } else { ?>
            <nav id="header-navigation" class="site-navigation main-navigation hide-on-tablet hide-on-mobile <?php if (!$blogtwist_enable_desktop_menu) { echo 'hide-on-desktop'; } ?>" role="navigation">
                <div class="site-wrapper">
                    <ul class="primary-menu reset-list-style fallback-menu">
                        <?php
                        wp_list_pages(
                            array(
                                'match_menu_classes' => true,
                                'show_sub_menu_icons' => true,
                                'title_li' => false,
                            )
                        );
                        ?>
                    </ul>
                </div>
            </nav>
        <?php } ?>
    </header><!-- #masthead -->
    <?php
    get_template_part('template-parts/components/modal-search');
    get_template_part('template-parts/components/modal-menu');
    ?>
    <?php
    $blogtwist_homepage_widget_section = get_theme_mod('blogtwist_homepage_widget_section', false);
    if ($blogtwist_homepage_widget_section == 1) {
        if (!is_paged() && is_front_page()) {
            get_template_part('template-parts/widgetarea/widgetarea-after-header');
        }
    } else {
        get_template_part('template-parts/widgetarea/widgetarea-after-header');
    }
    ?>
    <div id="content" class="site-content">
