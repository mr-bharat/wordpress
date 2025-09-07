<?php
/**
 * The template for displaying single audio post format posts.
 *
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

//get the media objects from the content and bring up only the first one
$media = blogtwist_audio_attachment(); ?>
<article id="single-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-post-single'); ?>>
    <header class="entry-header">
        <div class="entry-meta-wrapper">
             <span class="entry-meta entry-format">
				<a href="<?php echo esc_url(get_post_format_link('audio')); ?>"
                   title="<?php echo sprintf(esc_attr__('All %s posts', 'blogtwist'), get_post_format_string('audio')); ?>">
					<?php echo get_post_format_string('audio'); ?>
				</a>
			</span>
            <?php
            if ($enable_single_meta_category) {
                blogtwist_post_category($select_category_color_style, $single_category_label, $single_category_number);
            }
            ?>
        </div>
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
    </header><!-- .entry-header -->
    <?php if (!empty($media)) : ?>
        <div class="entry-featured  entry-media">
            <?php echo $media; ?>
        </div><!-- .entry-media -->
    <?php endif; ?>
    <?php
    // We need to first get the rendered content, and then echo it.
    // This way we can process the rendered content without firing the 'the_content' filter multiple times.
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