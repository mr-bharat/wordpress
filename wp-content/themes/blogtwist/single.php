<?php
/**
 * The template for displaying all single posts.
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
                    <?php while (have_posts()) : the_post();
                        /* Include the Post-Format-specific template for the content.
                        * If you want to override this in a child theme, then include a file
                        * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                        */
                        get_template_part('template-parts/single/content-single', get_post_format());
                        get_template_part('template-parts/single/single-related-post');
                        blogtwist_post_nav();
                        // If comments are open or we have at least one comment, load up the comment template
                        if (comments_open() || '0' != get_comments_number()) {
                            comments_template();
                        }
                    endwhile; // end of the loop. ?>
                </main><!-- #main -->
            </div><!-- #primary -->
            <?php get_sidebar(); ?>
        </div>
    </div>
<?php
get_footer();
