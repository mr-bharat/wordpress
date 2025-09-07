<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$enable_single_author_meta = get_theme_mod('enable_single_author_meta', true);
$select_author_meta = get_theme_mod('select_author_meta', 'with_icon');
$single_author_meta_label = get_theme_mod('single_author_meta_label');
$enable_single_date_meta = get_theme_mod('enable_single_date_meta', true);
$select_single_date_meta = get_theme_mod('select_single_date_meta', 'with_icon');
$single_date_meta_label = get_theme_mod('single_date_meta_label');
$select_date_format = get_theme_mod('select_date_format');
$enable_single_meta_category = get_theme_mod('enable_single_meta_category', true);
$select_category_color_style = get_theme_mod('select_category_color_style', 'none');
$single_category_label = get_theme_mod('single_category_label');
$single_category_number = get_theme_mod('single_category_number','2');
$single_tag_meta_label = get_theme_mod('single_tag_meta_label', '');
$enable_tag_meta = get_theme_mod('enable_tag_meta', true);
$enable_read_time = get_theme_mod('enable_read_time', true);
?>
<article id="single-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-post-single'); ?>>
    <?php
    ?>
    <header class="entry-header">
        <?php
        if ($enable_single_meta_category) {
            blogtwist_post_category($select_category_color_style, $single_category_label,$single_category_number);
        }
        ?>
        <?php the_title('<h1 class="entry-title entry-title-large">', '</h1>'); ?>
        <div class="entry-meta-wrapper">
            <?php if ($enable_single_date_meta) {
                blogtwist_posted_on($select_date_format, $single_date_meta_label, $select_single_date_meta);
            } ?>
            <?php if ($enable_single_author_meta) {
                blogtwist_posted_by($select_author_meta, $single_author_meta_label);
            } ?>
            <?php if ($enable_read_time) {
                blogtwist_get_readtime();
            } ?>
        </div>
        <?php if (has_excerpt()) : ?>
            <div class="entry-summary entry-summary-single">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
        <?php endif; ?>
    </header><!-- .entry-header -->
    <?php if (has_post_thumbnail()) { ?>
        <div class="entry-featured entry-thumbnail">
            <?php the_post_thumbnail('full'); ?>
            <?php
            $image_caption = wp_get_attachment_caption(get_post_thumbnail_id());
            if (!empty($image_caption)) {
                echo '<span class="entry-featured__caption">' . wp_kses_post($image_caption) . '</span>';
            }
            ?>
        </div>
    <?php } ?>
    <?php
    $content = blogtwist_get_rendered_content();
    ?>
    <div class="entry-content" <?php
    $first_letter = blogtwist_first_content_character($content);
    if (!empty($first_letter)) {
        echo 'data-first_letter="' . esc_attr($first_letter) . '"';
    } ?>>
        <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div><!-- .entry-content -->
    <footer class="entry-footer single-entry-footer">
        <?php
        if ($enable_tag_meta) {
            blogtwist_post_tag($single_tag_meta_label);
        }
        echo edit_post_link(esc_html__('Edit', 'blogtwist'), '<span class="entry-meta edit-link">', '</span>'); ?>
    </footer><!-- .entry-footer -->
</article>
