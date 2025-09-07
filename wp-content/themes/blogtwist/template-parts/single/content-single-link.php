<?php
/**
 * The template for displaying single link post format posts.
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$enable_single_author_meta = get_theme_mod('enable_single_author_meta',true);
$select_author_meta = get_theme_mod('select_author_meta','with_icon');
$single_author_meta_label = get_theme_mod('single_author_meta_label');

$enable_single_date_meta = get_theme_mod('enable_single_date_meta',true);
$select_single_date_meta = get_theme_mod('select_single_date_meta','with_icon');
$single_date_meta_label = get_theme_mod('single_date_meta_label');
$select_date_format = get_theme_mod('select_date_format');

$enable_single_meta_category = get_theme_mod('enable_single_meta_category',true);
$select_category_color_style = get_theme_mod('select_category_color_style','none');
$single_category_label = get_theme_mod('single_category_label');
$single_category_number = get_theme_mod('single_category_number','2');
$single_tag_meta_label = get_theme_mod('single_tag_meta_label', '');
$enable_tag_meta = get_theme_mod('enable_tag_meta',true);
$enable_read_time = get_theme_mod('enable_read_time',true);
?>
<article id="single-post-<?php the_ID(); ?>" <?php post_class('wpmotif-post wpmotif-post-single wpmotif-default-post'); ?>>

    <header class="entry-header">
         <div class="entry-meta-wrapper">
        <?php
        if ($enable_single_date_meta) { ?>
            <?php blogtwist_posted_on($select_date_format, $single_date_meta_label, $select_single_date_meta); 
        } ?>

        <?php
        if ($enable_single_author_meta) {
            blogtwist_posted_by($select_author_meta, $single_author_meta_label);
        }
        ?>
        <?php
        if ($enable_read_time) { ?>
            <?php blogtwist_get_readtime(); ?>
            <?php
        } ?>
            <?php
            if ($enable_single_meta_category) {
                blogtwist_post_category($select_category_color_style, $single_category_label,$single_category_number);
            }
            ?>
        </div>
        <?php the_title('<h1 class="entry-title entry-title-large">', '</h1>'); ?>
    </header><!-- .entry-header -->
    <?php if (has_post_thumbnail()) { ?>
        <div class="entry-featured entry-thumbnail has-hover-effects">
            <?php
            the_post_thumbnail('medium_large');
            ?>
            <a class="hover" href="<?php echo esc_url(blogtwist_get_post_format_link_url()); ?>" title="<?php echo get_the_title(); ?>" rel="bookmark">
                <span class="hover__bg"></span>
                <div class="flexbox">
                    <span class="hover__line  hover__line--top"></span>
                    <span class="hover__more">
                             <?php blogtwist_the_theme_svg('link'); ?>
                        </span>
                    <span class="hover__line  hover__line--bottom"></span>
                </div>
            </a>
        </div>
    <?php } ?>

        <footer class="entry-footer single-entry-footer">
        <?php             
            if ($enable_tag_meta) {
                blogtwist_post_tag($single_tag_meta_label);
            }
            echo edit_post_link( esc_html__( 'Edit', 'blogtwist' ), '<span class="entry-meta edit-link">', '</span>' ); ?>
        </footer><!-- .entry-footer -->

</article>