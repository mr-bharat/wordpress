<?php
/**
 * The template for displaying Comments.
 *
 * @package BlogTwist 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

if (post_password_required()) {
    return;
} ?>

<aside>
    <div id="comments" class="comments-area  <?php if (!have_comments()) { echo 'no-comments'; } ?>">
        <div class="comments-area-title">
            <h2 class="comments-title"><?php
                if (have_comments()) {
                    printf(
                    /* translators: %1$s: The number of comments.  */
                        esc_html(_nx('%1$s Comment', '%1$s Comments', get_comments_number(), 'comments title', 'blogtwist')),
                        esc_html(number_format_i18n(get_comments_number()))
                    );
                } else {
                    echo '<span class="comment-number  no-comments">i</span>' . esc_html__('There are no comments', 'blogtwist');
                } ?></h2>
            <?php echo '<a class="comments_add-comment" href="#reply-title">' . esc_html__('Add yours', 'blogtwist') . '</a>'; ?>
        </div>
        <?php
        // You can start editing here -- including this comment!
        if (have_comments()) {
            if (get_comment_pages_count() > 1 && get_option('page_comments')) {
                // are there comments to navigate through
                ?>
                <nav role="navigation" id="comment-nav-above" class="site-navigation comment-navigation">
                    <span class="comment-number comment-number--dark">&hellip;</span>

                    <h3 class="assistive-text"><?php esc_html_e('Comment navigation', 'blogtwist'); ?></h3>

                    <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'blogtwist')); ?></div>
                    <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'blogtwist')); ?></div>
                </nav><!-- #comment-nav-before .site-navigation .comment-navigation -->
            <?php } // check for comment navigation ?>

            <ol class="reset-list-style commentlist">
                <?php
                /* Loop through and list the comments. Tell wp_list_comments()
                 * to use blogtwist_comment() to format the comments.
                 * See blogtwist_comment() in inc/extras.php for more.
                 */
                wp_list_comments(); ?>
            </ol><!-- .commentlist -->

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) { // are there comments to navigate through 
                ?>
                <nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
                    <span class="comment-number comment-number--dark">&hellip;</span>

                    <h3 class="assistive-text"><?php esc_html_e('Comment navigation', 'blogtwist'); ?></h3>

                    <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'blogtwist')); ?></div>
                    <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'blogtwist')); ?></div>
                </nav><!-- #comment-nav-below .site-navigation .comment-navigation -->
                <?php
            }
        } ?>

    </div><!-- #comments .comments-area -->
    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="nocomments">
            <span class="comment-number comment-number--dark  no-comments-box">&middot;</span><span><?php esc_html_e('Comments are closed.', 'blogtwist'); ?></span>
        </p>
    <?php endif;

    comment_form();

    ?>
</aside>
