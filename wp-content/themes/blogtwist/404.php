<?php
/**
 * The template for displaying 404 pages (not found).
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header(); ?>
    <div class="site-wrapper">
        <div class="error-404 not-found">
            <div class="notfound-404">
                <h1><?php esc_html_e('404', 'blogtwist'); ?></h1>
            </div>
            <h2><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'blogtwist'); ?></h2>
            <?php get_search_form(); ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="notfound-link">
                <?php blogtwist_the_theme_svg('arrow-double'); ?><?php esc_html_e('Go Back To Homepage', 'blogtwist'); ?>
            </a>
        </div>
    </div>
<?php get_footer();
