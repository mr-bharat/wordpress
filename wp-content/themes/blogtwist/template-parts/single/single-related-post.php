<?php
/**
 * Template part for displaying single related posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$blogtwist_enable_single_related_post = get_theme_mod('blogtwist_enable_single_related_post', true);
if ($blogtwist_enable_single_related_post) {
    $blogtwist_select_single_related_posts_category = get_theme_mod('blogtwist_select_single_related_posts_category');
    $blogtwist_number_single_related_posts = absint(get_theme_mod('blogtwist_number_single_related_posts', 4));
    $enable_single_related_posts_author_meta = get_theme_mod('enable_single_related_posts_author_meta', true);
    $select_single_related_posts_author_meta = get_theme_mod('select_single_related_posts_author_meta', 'with_icon');
    $single_related_posts_author_meta_label = get_theme_mod('single_related_posts_author_meta_label');
    $enable_single_related_posts_date_meta = get_theme_mod('enable_single_related_posts_date_meta', true);
    $select_single_related_posts_date_meta = get_theme_mod('select_single_related_posts_date_meta', 'with_icon');
    $single_related_posts_date_meta_label = get_theme_mod('single_related_posts_date_meta_label');
    $select_single_related_posts_date_format = get_theme_mod('select_single_related_posts_date_format');
    $enable_single_related_posts_meta_category = get_theme_mod('enable_single_related_posts_meta_category', true);
    $select_single_related_posts_category_color_style = get_theme_mod('select_single_related_posts_category_color_style', 'none');
    $single_related_posts_category_label = get_theme_mod('single_related_posts_category_label');
    $single_related_posts_category_number = get_theme_mod('single_related_posts_category_number','2');
    $enable_single_related_posts_read_time = get_theme_mod('enable_single_related_posts_read_time', false);
    $blogtwist_single_related_post_title = get_theme_mod('blogtwist_single_related_post_title', 'Related');
    $post_args = array(
        'post_type' => 'post',
        'posts_per_page' => ($blogtwist_number_single_related_posts),
        'post_status' => 'publish',
        'no_found_rows' => 1,
        'ignore_sticky_posts' => 1,
    );
    // Check for category.
    if (!empty($blogtwist_select_single_related_posts_category)) :
        $post_args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $blogtwist_select_single_related_posts_category,
            ),
        );
    endif;
    $single_related_posts = new WP_Query($post_args);
    if ($single_related_posts->have_posts()) :
        ?>
        <div class="wpmotif-element wpmotif-single-element wpmotif-single-related">
            <?php if (!empty($blogtwist_single_related_post_title)) : ?>
                <header class="wpmotif-element-header">
                    <h2 class="wpmotif-element-title">
                        <?php echo esc_html($blogtwist_single_related_post_title); ?>
                    </h2>
                </header>
            <?php endif; ?>
            <div class="wpmotif-element-content">
                <?php
                while ($single_related_posts->have_posts()) :
                    $single_related_posts->the_post();
                    ?>
                    <article id="related-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-default-post wpmotif-related-post'); ?>>
                        <?php if (has_post_thumbnail()) { ?>
                            <div class="entry-thumbnail entry-thumbnail-small has-hover-effects">
                                <?php
                                the_post_thumbnail('medium');
                                get_template_part('template-parts/featured-hover');
                                ?>
                            </div>
                        <?php } ?>
                        <div class="entry-details">
                            <?php if ($enable_single_related_posts_meta_category) { ?>
                                <?php blogtwist_post_category($select_single_related_posts_category_color_style, $single_related_posts_category_label,$single_related_posts_category_number); ?>
                            <?php } ?>
                            <header class="entry-header">
                                <?php
                                /* translators: %s: The post URL. */
                                the_title(sprintf('<h2 class="entry-title entry-title-small"><a href="%s" class="entry-permalink" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                            </header><!-- .entry-header -->
                            <div class="entry-meta-wrapper">
                                <?php
                                if ($enable_single_related_posts_date_meta) {
                                    blogtwist_posted_on($select_single_related_posts_date_format, $single_related_posts_date_meta_label, $select_single_related_posts_date_meta);
                                } ?>
                                <?php
                                if ($enable_single_related_posts_author_meta) {
                                    blogtwist_posted_by($select_single_related_posts_author_meta, $single_related_posts_author_meta_label);
                                } ?>
                                <?php if ($enable_single_related_posts_read_time) {
                                    blogtwist_get_readtime();
                                } ?>
                            </div>
                        </div>
                    </article>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    <?php
    endif;
}
