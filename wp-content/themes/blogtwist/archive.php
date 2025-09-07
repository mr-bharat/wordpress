<?php
/**
 * The template for displaying archive pages.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header(); ?>
    <div class="site-wrapper">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php if (have_posts()) : ?>
                    <header class="page-header">
                        <h1 class="page-title">
                            <?php the_archive_title(); ?>
                        </h1>
                        <?php
                        // Show an optional term description.
                        $term_description = term_description();
                        if (!empty($term_description)) {
                            /* translators: %s: term description */
                            the_archive_description('<div class="taxonomy-description">', '</div>');
                        } ?>
                    </header><!-- .page-header -->
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
            </main><!-- #main -->
        </div>
    </div>
<?php get_footer();
