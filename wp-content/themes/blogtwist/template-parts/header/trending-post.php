<?php
/**
 * Displays Trending Post inside search modal
 *
 * @package BlogTwist 1.0.0
 */
?>
<?php
$blogtwist_enable_post_search = get_theme_mod('blogtwist_enable_post_search', true);
if ($blogtwist_enable_post_search) {
    $blogtwist_select_category = get_theme_mod('blogtwist_select_category', []);
    $blogtwist_number_of_posts = absint(get_theme_mod('blogtwist_number_of_posts', 4));
    $blogtwist_enable_search_meta_cat = get_theme_mod('blogtwist_enable_search_meta_cat', false);
    $blogtwist_enable_search_meta_data = get_theme_mod('blogtwist_enable_search_meta_data', false);
    $enable_search_author_meta = get_theme_mod('enable_search_author_meta', true);
    $select_search_author_meta = get_theme_mod('select_search_author_meta', 'with_icon');
    $search_author_meta_label = get_theme_mod('search_author_meta_label', '');
    $enable_search_date_meta = get_theme_mod('enable_search_date_meta', true);
    $select_search_date_meta = get_theme_mod('select_search_date_meta', 'with_icon');
    $search_date_meta_label = get_theme_mod('search_date_meta_label', '');
    $select_search_date_format = get_theme_mod('select_search_date_format', 'F j, Y');
    $enable_search_meta_category = get_theme_mod('enable_search_meta_category', true);
    $select_search_category_color_style = get_theme_mod('select_search_category_color_style', 'none');
    $search_category_number = get_theme_mod('search_category_number', '2');
    $search_category_label = get_theme_mod('search_category_label', '');

    $post_args = [
        'post_type' => 'post',
        'posts_per_page' => $blogtwist_number_of_posts,
        'post_status' => 'publish',
        'no_found_rows' => true, // Improves query performance.
        'ignore_sticky_posts' => true,
    ];

    if (!empty($blogtwist_select_category)) {
        $post_args['tax_query'] = [
            [
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $blogtwist_select_category,
            ],
        ];
    }
    $search_latest_posts = new WP_Query($post_args);
    if ($search_latest_posts->have_posts()) :
        $blogtwist_custom_title = get_theme_mod('blogtwist_custom_title');
        ?>
        <?php do_action('blogtwist_before_trending_post'); ?>
        <div class="search-modal-articles">
            <?php if ($blogtwist_custom_title) : ?>
                <h2>
                    <?php
                    echo esc_html($blogtwist_custom_title);
                    ?>
                </h2>
            <?php endif; ?>
            <div class="wpmotif-search-articles">
                <?php
                while ($search_latest_posts->have_posts()) :
                    $search_latest_posts->the_post();
                    ?>
                    <article id="search-articles-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-default-post wpmotif-search-post'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="entry-thumbnail entry-thumbnail-small has-hover-effects">
                                <?php
                                the_post_thumbnail('medium');
                                if (function_exists('get_template_part')) {
                                    get_template_part('template-parts/featured-hover');
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="entry-details">
                            <?php if ($enable_search_meta_category) { ?>

                                    <?php
                                    if (function_exists('blogtwist_post_category')) {
                                        blogtwist_post_category($select_search_category_color_style, $search_category_label, $search_category_number);
                                    }
                                    ?>

                            <?php } ?>

                            <header class="entry-header">
                                <?php
                                the_title(sprintf(
                                    '<h3 class="entry-title entry-title-xs"><a href="%s" class="entry-permalink" rel="bookmark">',
                                    esc_url(get_permalink())
                                ), '</a></h3>');
                                ?>
                            </header>
                            <div class="entry-meta-wrapper">
                                <?php
                                if ($enable_search_date_meta && function_exists('blogtwist_posted_on')) {
                                    blogtwist_posted_on($select_search_date_format, $search_date_meta_label, $select_search_date_meta);
                                }
                                if ($enable_search_author_meta && function_exists('blogtwist_posted_by')) {
                                    blogtwist_posted_by($select_search_author_meta, $search_author_meta_label);
                                }
                                ?>
                            </div>
                        </div>
                    </article>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php do_action('blogtwist_after_trending_post'); ?>
    <?php
    endif;
}
?>