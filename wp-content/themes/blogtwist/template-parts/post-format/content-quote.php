<?php
/**
 * The template for displaying the quote post format on archives.
 * @package BlogTwist 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<article id="latest-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-default-post wpmotif-archive-post'); ?>>
    <?php if (has_post_thumbnail()) { ?>

        <div class="entry-thumbnail has-hover-effects">
            <?php
            the_post_thumbnail('medium_large');
            get_template_part('template-parts/featured-hover');
            ?>
        </div>

    <?php } ?>

    <div class="entry-summary has-colorful-summary">
        <div class="content-quote">
            <?php
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
                if (get_the_excerpt()) { ?>
                    <div class="entry-quote">
                        <blockquote><?php the_excerpt(); ?></blockquote>
                    </div><!-- .entry-quote -->
                <?php }
            } ?>

        </div>
    </div>

</article>
