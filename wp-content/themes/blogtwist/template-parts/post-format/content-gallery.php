<?php
/**
 * The template for displaying the gallery post format on archives.
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$enable_archive_post_excerpt = get_theme_mod('enable_archive_post_excerpt', true);
$enable_archive_author_meta = get_theme_mod('enable_archive_author_meta', true);
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
$archive_tag_meta_label = get_theme_mod('archive_tag_meta_label', 'Tags: ');
?>
<article id="latest-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-default-post wpmotif-archive-post'); ?>>
    <header class="entry-header">
        <?php
        if ($enable_archive_meta_category) {
            blogtwist_post_category($select_archive_category_color_style, $archive_category_label,$archive_category_number);
        }
        ?>

        <?php
        the_title(
            sprintf(
                '<h2 class="entry-title"><a href="%s" class="entry-permalink" rel="bookmark">',
                esc_url(get_permalink())
            ),
            '</a></h2>'
        );
        ?>

        <div class="entry-meta-wrapper">
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
            if ($enable_archive_read_time) { ?>
                <?php blogtwist_get_readtime(); ?>
                <?php
            } ?>
        </div>
    </header><!-- .entry-header -->
    <?php
    if (has_block('gallery', get_the_content())) {
        $post_blocks = parse_blocks(get_the_content());
        foreach ($post_blocks as $block) {
            if ('core/gallery' === $block['blockName']) {
                remove_filter( 'the_content', 'sharing_display', 19 );
                echo '<div class="entry-gallery">';
                echo apply_filters('the_content', render_block($block));
                echo '</div><!-- .entry-gallery -->';
                break;
            }
        }
    } else {
        $gallery = get_post_gallery();
        if ($gallery) { ?>
            <div class="entry-gallery">
                <?php echo $gallery; ?>
            </div><!-- .entry-gallery -->
        <?php }
    }
    ?>

    <?php if ($enable_archive_post_excerpt && has_excerpt()) : ?>
        <div class="entry-summary has-colorful-summary">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
    <?php endif; ?>

    <footer class="entry-footer">
        <?php if ($enable_archive_tag_meta) {
            blogtwist_post_tag($archive_tag_meta_label);
        }?>
        <?php edit_post_link(esc_html__('Edit', 'blogtwist'), '<span class="entry-meta edit-link">', '</span>'); ?>
    </footer><!-- .entry-footer -->

    <div class="end-spacer">
        <?php blogtwist_the_theme_svg('separator'); ?>
    </div>
</article>