<?php
/**
 * The template for displaying search results pages.
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
                        <h1 class="page-title  page-title--search"><?php
                            /* translators: %s: The search query. */
                            printf(esc_html__('Search Results for: %s', 'blogtwist'), '<span>' . esc_html(get_search_query()) . '</span>'); ?></h1>
                    </header><!-- .page-header -->
                    <?php $select_archive_layout = get_theme_mod('select_archive_layout', 'archive-layout-grid'); ?>
                    <div id="search-results" class="archive-layout <?php echo esc_attr($select_archive_layout); ?>">
                        <?php
                        /* Start the Loop */
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
                    get_template_part('template-parts/content', 'none');
                endif; ?>
            </main>
            <!-- #main -->
        </div><!-- #primary -->
    </div>
<?php get_footer();
