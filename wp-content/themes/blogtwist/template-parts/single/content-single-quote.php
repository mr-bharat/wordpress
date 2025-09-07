<?php
/**
 * The template for displaying single quote post format posts.
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Fetch theme customizations.
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
    <header class="entry-header">
        <div class="entry-meta-wrapper">
              <span class="entry-meta entry-format">
                <a href="<?php echo esc_url(get_post_format_link('quote')); ?>"
                   title="<?php echo sprintf(esc_attr__('All %s posts', 'blogtwist'), get_post_format_string('quote')); ?>">
                    <?php echo get_post_format_string('quote'); ?>
                </a>
            </span>
            <?php if ($enable_single_meta_category) {
                blogtwist_post_category($select_category_color_style, $single_category_label, $single_category_number);
            } ?>
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

    <div class="single-quote-panel">
        <?php if (has_post_thumbnail()) { ?>
            <div class="entry-featured entry-thumbnail">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php } ?>
        <div class="content-quote">
            <?php
            // Extract quote content from the block editor (if any).
            $quote_content = '';
            $post_blocks = parse_blocks(get_the_content());
            foreach ($post_blocks as $block) {
                if ('core/quote' === $block['blockName']) {
                    $quote_content = apply_filters('the_content', render_block($block));
                    break;
                }
            }
            if ($quote_content) { ?>
                <div class="entry-quote">
                    <?php echo $quote_content; ?>
                </div><!-- .entry-quote -->
            <?php } else {
                // If no quote block is found, fallback to excerpt.
                if ($excerpt = get_the_excerpt()) { ?>
                    <div class="entry-quote">
                        <blockquote><?php echo $excerpt; ?></blockquote>
                    </div><!-- .entry-quote -->
                <?php }
            } ?>
        </div>
    </div>

    <?php
    // Fetch the rendered content for further processing (e.g., for first letter).
    $content = blogtwist_get_rendered_content();
    $first_letter = blogtwist_first_content_character($content); // Get the first character of content.

    ?>
    <div class="entry-content" <?php if (!empty($first_letter)) { echo 'data-first_letter="' . esc_attr($first_letter) . '"'; } ?>>
        <?php
        $filtered_content = '';
        foreach ($post_blocks as $block) {
            // Skip the 'quote' block and render the rest.
            if ('core/quote' !== $block['blockName']) {
                $filtered_content .= render_block($block);
            }
        }
        echo apply_filters('the_content', $filtered_content);
        ?>
    </div>

    <footer class="entry-footer single-entry-footer">
        <?php
        // Display tags if enabled.
        if ($enable_tag_meta) {
            blogtwist_post_tag($single_tag_meta_label);
        }
        // Show the edit post link.
        echo edit_post_link(esc_html__('Edit', 'blogtwist'), '<span class="entry-meta edit-link">', '</span>'); ?>
    </footer><!-- .entry-footer -->
</article>