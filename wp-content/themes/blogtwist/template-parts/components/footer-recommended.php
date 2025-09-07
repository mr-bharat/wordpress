<?php
/**
 * Displays footer recommended section
 *
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$blogtwist_enable_footer_latest = get_theme_mod('blogtwist_enable_footer_latest', true);
$footer_recommended_section_title = get_theme_mod('footer_recommended_section_title', 'Recommended for You');
if ($blogtwist_enable_footer_latest) {
    $blogtwist_select_footer_recommended_category = get_theme_mod('blogtwist_select_footer_recommended_category');
    $enable_footer_latest_author_meta = get_theme_mod('enable_footer_latest_author_meta', true);
    $select_footer_recommended_author_meta = get_theme_mod('select_footer_recommended_author_meta', 'with_label');
    $footer_recommended_author_meta_label = get_theme_mod('footer_recommended_author_meta_label','');
    $enable_footer_latest_date_meta = get_theme_mod('enable_footer_latest_date_meta', true);
    $select_footer_recommended_date_meta = get_theme_mod('select_footer_recommended_date_meta', 'with_icon');
    $footer_recommended_date_meta_label = get_theme_mod('footer_recommended_date_meta_label','');
    $select_footer_recommended_date_format = get_theme_mod('select_footer_recommended_date_format','');
    $enable_footer_latest_meta_category = get_theme_mod('enable_footer_latest_meta_category', false);
    $select_footer_recommended_category_color_style = get_theme_mod('select_footer_recommended_category_color_style', 'none');
    $footer_recommended_category_label = get_theme_mod('footer_recommended_category_label');
    $footer_recommended_category_number = get_theme_mod('footer_recommended_category_number','2');
    $enable_footer_latest_read_time = get_theme_mod('enable_footer_latest_read_time', false);
    $post_args = array(
        'post_type' => 'post',
        'posts_per_page' => 4,
        'post_status' => 'publish',
        'no_found_rows' => 1,
        'ignore_sticky_posts' => 1,
    );
    // Check for category.
    if (!empty($blogtwist_select_footer_recommended_category)) :
        $post_args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $blogtwist_select_footer_recommended_category,
            ),
        );
    endif;

    $footer_recommended = new WP_Query($post_args);
    if ($footer_recommended->have_posts()) :
        ?>
        <div class="wpmotif-element wpmotif-recommended-element">
            <?php if (!empty($footer_recommended_section_title)) { ?>
                <header class="wpmotif-element-header">
                    <div class="site-wrapper">
                        <h2 class="wpmotif-element-title">
                            <?php echo esc_html($footer_recommended_section_title); ?>
                        </h2>
                    </div>
                </header>
            <?php } ?>
            <div class="site-wrapper">
                <?php
                while ($footer_recommended->have_posts()) :
                    $footer_recommended->the_post();
                    ?>
                    <article id="recommended-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-panel-post'); ?>>
                        <div class="entry-meta-wrapper">
                            <?php
                            if ($enable_footer_latest_date_meta) {
                                blogtwist_posted_on($select_footer_recommended_date_format, $footer_recommended_date_meta_label, $select_footer_recommended_date_meta);
                            } ?>
                        </div>
                        <div class="entry-details">
                            <?php if ($enable_footer_latest_meta_category) { ?>
                                <div class="entry-meta-wrapper">
                                    <?php blogtwist_post_category($select_footer_recommended_category_color_style, $footer_recommended_category_label, $footer_recommended_category_number); ?>
                                </div>
                            <?php } ?>
                            <header class="entry-header">
                                <?php
                                /* translators: %s: The post URL. */
                                the_title(sprintf('<h2 class="entry-title entry-title-medium"><a href="%s" class="entry-permalink" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                            </header><!-- .entry-header -->
                            <div class="entry-meta-wrapper">
                                <?php
                                if ($enable_footer_latest_author_meta) {
                                    blogtwist_posted_by($select_footer_recommended_author_meta, $footer_recommended_author_meta_label);
                                } ?>

                                <?php if ($enable_footer_latest_read_time) {
                                    blogtwist_get_readtime();
                                } ?>
                            </div>
                        </div>
                        <?php if (has_post_thumbnail()) { ?>
                            <div class="entry-thumbnail entry-thumbnail-medium has-hover-effects">
                                <?php
                                the_post_thumbnail('medium_large');
                                get_template_part('template-parts/featured-hover');
                                ?>
                            </div>
                        <?php } ?>
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
