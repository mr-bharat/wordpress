<?php
/**
 * The template for displaying posts home.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header(); ?>
    <div class="site-wrapper">
    <div class="row-group">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php if (!is_paged() && is_front_page()) {
                    // Homepage Before Posts widget area shows here
                    if (is_active_sidebar('homepage-before-posts')) {
                        dynamic_sidebar('homepage-before-posts');
                    }
                } ?>
                <?php if (have_posts()) : ?>

                    <?php $select_archive_layout = get_theme_mod('select_archive_layout', 'archive-layout-grid'); ?>
                    <div id="latest-posts" class="archive-layout <?php echo esc_attr($select_archive_layout); ?>">
                        <?php /* Start the Loop */
                        while (have_posts()) : the_post();
                            /* Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part('template-parts/post-format/content', get_post_format());
                        endwhile; ?>
                    </div>
                    <?php blogtwist_paging_nav();
                else :
                    get_template_part('content', 'none');
                endif; ?>
                <?php if (!is_paged() && is_front_page()) {
                    // Homepage After Posts widget area shows here
                    if (is_active_sidebar('homepage-after-posts')) {
                        dynamic_sidebar('homepage-after-posts');
                    }
                } ?>
            </main><!-- #main -->
        </div>
        <?php get_sidebar(); ?>
    </div>
    </div>
<?php
//no sidebar on home please
get_footer();