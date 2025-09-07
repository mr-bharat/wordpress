<?php
/**
 * The template part responsible for displaying the post content.
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $post;
$post_id = get_the_ID();
$enable_archive_author_meta = get_theme_mod('enable_archive_author_meta', false);
$select_archive_author_meta = get_theme_mod('select_archive_author_meta', 'with_icon');
$archive_author_meta_label = get_theme_mod('archive_author_meta_label');
$enable_archive_date_meta = get_theme_mod('enable_archive_date_meta', true);
$select_archive_date_meta = get_theme_mod('select_archive_date_meta', 'with_icon');
$archive_date_meta_label = get_theme_mod('archive_date_meta_label');
$select_archive_date_format = get_theme_mod('select_archive_date_format');
$enable_archive_meta_category = get_theme_mod('enable_archive_meta_category', true);
$select_archive_category_color_style = get_theme_mod('select_archive_category_color_style', 'none');
$archive_category_label = get_theme_mod('archive_category_label');
$archive_category_number = get_theme_mod('archive_category_number','2');
$enable_archive_read_time = get_theme_mod('enable_archive_read_time', false);
$enable_archive_tag_meta = get_theme_mod('enable_archive_tag_meta', true);
$archive_tag_meta_label = get_theme_mod('archive_tag_meta_label', '');
?>
<article id="search-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-default-post wpmotif-archive-post'); ?>>
    <header class="entry-header">
        <?php
        if ($enable_archive_meta_category) {
            blogtwist_post_category($select_archive_category_color_style, $archive_category_label, $archive_category_number);
        }
        ?>
        <?php
        the_title(
            sprintf(
                '<h2 class="entry-title entry-title-big"><a href="%s" class="entry-permalink" rel="bookmark">',
                esc_url(get_permalink())
            ),
            '</a></h2>'
        );
        ?>
    </header><!-- .entry-header -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="entry-thumbnail has-hover-effects">
            <?php
            the_post_thumbnail('medium_large');
            get_template_part('template-parts/featured-hover');
            ?>
        </div>
    <?php endif; ?>
    <div class="entry-content">
        <?php
        // Check for the "more" tag
        if (strpos($post->post_content, '<!--more') !== false) {
            the_content(wp_kses_post(__('Continue reading <span class="meta-nav">&rarr;</span>', 'blogtwist')));
        } else {
            the_excerpt();
        }
        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'blogtwist'),
            'after' => '</div>',
        ));
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        if ($enable_archive_date_meta) { ?>
            <?php blogtwist_posted_on($select_archive_date_format, $archive_date_meta_label, $select_archive_date_meta); ?>

            <?php
        } ?>
        <?php
        if ($enable_archive_author_meta) {
            blogtwist_posted_by($select_archive_author_meta, $archive_author_meta_label);
        }
        ?>
        <?php
        if ($enable_archive_read_time) { 
            blogtwist_get_readtime(); 
        } ?>
        <?php if ($enable_archive_tag_meta) {
            blogtwist_post_tag($archive_tag_meta_label);
        }?>
        <?php edit_post_link(esc_html__('Edit', 'blogtwist'), '<span class="entry-meta edit-link">', '</span>'); ?>

    </footer><!-- .entry-footer -->
</article>