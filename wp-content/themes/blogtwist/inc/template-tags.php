<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 * @package BlogTwist 1.0.0
 */
if (!function_exists('blogtwist_paging_nav')) :
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function blogtwist_paging_nav()
    {
        global $wp_query, $wp_rewrite;
        if ($wp_query->max_num_pages < 2) {
            return;
        }
        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $query_args = array();
        $url_parts = explode('?', $pagenum_link);
        if (isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }
        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
        $pagenum_link = trailingslashit($pagenum_link) . '%_%';
        $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%'; ?>
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        the_posts_pagination(array(
            'base' => $pagenum_link,
            'format' => $format,
            'total' => $wp_query->max_num_pages,
            'current' => $paged,
            'prev_next' => true,
            'prev_text' => esc_html__('« Previous', 'blogtwist'),
            'next_text' => esc_html__('Next »', 'blogtwist'),
            'add_args' => array_map('urlencode', $query_args),
        ));
        ?>
        <?php
    }
endif;

if (!function_exists('blogtwist_post_nav')) :
    /**
     * Display navigation to next/previous post when applicable.
     */
    function blogtwist_post_nav()
    {
        if (is_single() || is_attachment()) {
            $previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
            $next = get_adjacent_post(false, '', false);
            if (!$next && !$previous) {
                return;
            }
            ?>
            <nav class="navigation post-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'blogtwist'); ?></h2>
                <div class="nav-links">
                    <?php
                    // Previous Post Link
                    $prev_link = get_previous_post_link(
                        '<div class="nav-previous"><h2 class="entry-title entry-title-small">%link</h2></div>',
                        blogtwist_get_theme_svg("arrow-left") . '<span>%title</span>'
                    );
                    if (!empty($prev_link)) {
                        echo $prev_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    } else {
                        echo '<div class="nav-previous disabled">' . blogtwist_get_theme_svg("arrow-left") . '</div>';
                    }

                    // Home Link (if single post view)
                    if (is_single()) {
                        $static_posts_page = get_option('page_for_posts');
                        $home_url = $static_posts_page ? esc_url(get_permalink($static_posts_page)) : esc_url(home_url());
                        echo '<div class="nav-home"><a href="' . $home_url . '">' . blogtwist_get_theme_svg("home") . '</a></div>';
                    }

                    // Next Post Link
                    $next_link = get_next_post_link(
                        '<div class="nav-next"><h2 class="entry-title entry-title-small">%link</h2></div>',
                        '<span>%title</span>' . blogtwist_get_theme_svg("arrow-right")
                    );
                    if (!empty($next_link)) {
                        echo $next_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    } else {
                        echo '<div class="nav-next disabled">' . blogtwist_the_theme_svg("arrow-right") . '</div>';
                    }
                    ?>
                </div>
            </nav>
            <?php
        }
    }
endif;


if (!function_exists('blogtwist_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     * @param $date_format format 'time_ago' or null.
     * @param $date_label Show Label or Not.
     * @param $date_meta Show Icon or Not.
     */
    function blogtwist_posted_on($date_format = null, $date_label = null, $date_meta = null)
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );
        ?>
        <span class="entry-meta posted-on">
            <?php

            if ($date_label && $date_meta == 'with_label') : ?>
                <span class="entry-meta-label date-label"><?php echo esc_html($date_label); ?></span>
            <?php else : ?>
                <span class="screen-reader-text"><?php echo esc_html($date_label); ?></span>
            <?php endif;

            if ($date_meta == 'with_icon') :
                blogtwist_the_theme_svg('calendar');
            endif;

            if ($date_format == 'time_ago') {
                echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'blogtwist'));
            } else {
                $posted_on = sprintf(
                /* translators: %s: post date. */
                    esc_html_x('%s', 'post date', 'blogtwist'),
                    '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
                );


                echo $posted_on; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            ?>
        </span>
        <?php

    }
endif;

if (!function_exists('blogtwist_posted_by')) :
    /**
     * Prints HTML with meta information for the post author.
     *
     * @param string|null $author_meta Specifies whether to show the label, icon, or avatar image.
     * @param string|null $meta_text Text to display as the label (if applicable).
     */
    function blogtwist_posted_by($author_meta = null, $meta_text = null)
    {
        // Get author details.
        $author_name = get_the_author_meta('display_name');
        $author_url = get_author_posts_url(get_the_author_meta('ID'));

        ?>
        <span class="entry-meta byline">
            <?php if ($author_meta === 'with_label' && $meta_text) : ?>
                <span class="entry-meta-label author-label">
                    <?php echo esc_html($meta_text); ?>
                </span>
            <?php else : ?>
                <span class="screen-reader-text">
                    <?php echo esc_html_x('Author', 'Used before post author name.', 'blogtwist'); ?>
                </span>
            <?php endif; ?>

            <?php if ($author_meta === 'with_icon') : ?>
                <?php blogtwist_the_theme_svg('user'); ?>
            <?php endif; ?>

            <?php if ($author_meta === 'with_avatar_image') : ?>
                <?php echo get_avatar(
                    get_the_author_meta('ID'), 60, '', '', ['class' => 'byline-img']
                ); ?>
                <a href="<?php echo esc_url($author_url); ?>" class="text-decoration-reset">
                    <?php echo esc_html($author_name); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url($author_url); ?>" class="text-decoration-reset">
                    <?php echo esc_html($author_name); ?>
                </a>
            <?php endif; ?>
        </span>
        <?php
    }
endif;


if (!function_exists('blogtwist_post_category')) :
    /**
     * Display post categories.
     *
     * @param $show_color Show Category Color.
     * @param $meta_text Show Label or Not.
     *
     * @since 1.0.0
     */
    function blogtwist_post_category($show_color = null, $meta_text = null, $limit = 0)
    {

        $categories = get_the_category(get_the_ID());

        if (empty($categories)) {
            return;
        }

        if (0 != $limit) {
            $limit = absint($limit);
            if (count($categories) > $limit) {
                $categories = array_slice($categories, 0, $limit);
            }
        }
        if (null == $show_color) {
            $show_color = 'none';
        }

        $wrapper_class = ' categories-' . $show_color;

        ?>
        <div class="entry-meta entry-categories cat-links<?php echo esc_attr($wrapper_class); ?>">
            <?php
            if ($meta_text) : ?>
                <span class="entry-meta-label author-label"><?php echo esc_html($meta_text); ?></span>
            <?php else : ?>
                <span class="screen-reader-text"><?php esc_html_e('Posted in', 'blogtwist'); ?></span>
            <?php endif;
            ?>
            <?php
            $style_attr = '';

            if ('none' != $show_color) :
                if ('has-text-color' == $show_color) :
                    $style_attr = ' style="color:value;"';
                else :
                    $style_attr = ' style="background-color:value;"';
                endif;
            endif;

            global $wp_rewrite;

            $rel = (is_object($wp_rewrite) && $wp_rewrite->using_permalinks()) ? 'rel="category tag"' : 'rel="category"';

            $separator = ' ';

            $cat_list = '';
            $i = 0;

            foreach ($categories as $category) {

                $class = '';

                if (0 < $i) {
                    $cat_list .= $separator;
                }

                $build_style_attr = '';
                if ('none' != $show_color) {
                    $color = get_term_meta($category->term_id, 'category_color', true);
                    if ($color) {
                        $build_style_attr = str_replace('value', $color, $style_attr);
                    } else {
                        $build_style_attr = '';
                    }
                    if ('has-text-color' == $show_color) :
                        $class = ' class="has-text-color"';
                    endif;
                    if ('has-background' == $show_color) :
                        $class = ' class="has-background-color"';
                    endif;
                }

                $cat_list .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" ' . $rel . $class . $build_style_attr . '>' . $category->name . '</a>';
                ++$i;
            }
            echo $cat_list;
            ?>
        </div>
        <?php
    }
endif;


if (!function_exists('blogtwist_post_tag')) :
    /**
     * Prints HTML with meta information for the tags.
     *
     * @param string $tags_label The label for the tags section.
     */
    function blogtwist_post_tag($tags_label)
    {
        // Ensure this function only processes posts.
        if ('post' === get_post_type()) {
            $tags_content = '';

            // Generate the tags list if available.
            $tags_list = get_the_tag_list('', esc_html_x(' ', 'list item separator', 'blogtwist'));
            if ($tags_list) {
                // Add a hash '#' to each tag link text.
                $tags_list = preg_replace('/<a\s([^>]+)>([^<]+)<\/a>/', '<a $1>#\2</a>', $tags_list);
                $tags_content .= $tags_list;
            }

            // Output the tags with a wrapper and label.
            echo '<div class="entry-meta entry-tags tags-links">';
            echo '<span class="entry-meta-label tag-label">' . esc_html($tags_label) . '</span>';
            echo wp_kses_post($tags_content);
            echo '</div>';
        }
    }
endif;

/**
 * Flush out the transients used in blogtwist_categorized_blog.
 */
function blogtwist_category_transient_flusher()
{
    // Like, beat it. Dig?
    delete_transient('blogtwist_categories');
}

add_action('edit_category', 'blogtwist_category_transient_flusher');
add_action('save_post', 'blogtwist_category_transient_flusher');
if (!function_exists('blogtwist_get_rendered_content')) :
    /**
     * Return the rendered post content.
     *
     * This is the same as the_content() except for the fact that it doesn't display the content, but returns it.
     * Do make sure not to use this function twice for a post inside the loop, because it would defeat the purpose.
     *
     * @param string $more_link_text Optional. Content for when there is more text.
     * @param bool $strip_teaser Optional. Strip teaser content before the more text. Default is false.
     * @return string
     */
    function blogtwist_get_rendered_content($more_link_text = null, $strip_teaser = false)
    {
        $content = get_the_content($more_link_text, $strip_teaser);
        /**
         * Filters the post content.
         *
         * @param string $content Content of the current post.
         * @since 0.71
         *
         */
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        return $content;
    }
endif;
if (!function_exists('blogtwist_first_content_character')) :
    /**
     * Returns the first UTF-8 character of the content
     * returns empty string if nothing found
     *
     * @param string $content The content to extract the first character from.
     * @return string
     */
    function blogtwist_first_content_character($content = '')
    {
        //no need for this when a password is required
        if (post_password_required()) {
            return '';
        }
        // By default we have no first letter
        $first_letter = '';
        if (empty($content)) {
            // If we haven't been provided with a rendered content (with all shortcodes run, etc),
            // we need to get our own.
            $content = get_the_content();
            // remove [caption] shortcode
            // because if it is the first part of the content we don't need the caption
            $content = trim(preg_replace("/\[caption.*\[\/caption\]/si", '', $content));
            //now apply the regular filters, without the captions
            $content = apply_filters('the_content', $content);
        }
        // Bail if we have no content to work with
        if (empty($content)) {
            return $first_letter;
        }
        // We need to make sure that we don't look at strings inside <figure>s - those are probably captions -
        // or embeds - Twitter (usually inside divs with some embed class)
        // This is why we want to remove the tags and their content
        // We are only interested in the beginning of the the content, not the whole
        // This is why we are using preg_replace, not preg_replace_all
        $content = preg_replace("/<figure.*<\/figure>/siU", '', $content);
        $content = preg_replace("/<div.*embed.*<\/div>/siU", '', $content);
        // Strip all the tags that are left and use what we are left with
        $content = wp_strip_all_tags(html_entity_decode($content));
        // Find the first alphanumeric character - multibyte
        preg_match('/[\p{Xan}]/u', $content, $results);
        if (!empty($results)) {
            $first_letter = reset($results);
        } else {
            // Lets try the old fashion way
            // Find the first alphanumeric character - non-multibyte
            preg_match('/[a-zA-Z\d]/', $content, $results);
            if (!empty($results)) {
                $first_letter = reset($results);
            }
        };
        return $first_letter;
    }
endif;
if (!function_exists('blogtwist_get_post_format_first_image')) :
    function blogtwist_get_post_format_first_image()
    {
        global $post;
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if (empty($matches[0])) {
            return '';
        }
        return $matches[0];
    }
endif;
if (!function_exists('blogtwist_get_post_format_link_url')) :
    /**
     * Returns the URL to use for the link post format.
     *
     * First it tries to get the first URL in the content; if not found it uses the permalink instead
     *
     * @return string URL
     */
    function blogtwist_get_post_format_link_url()
    {
        $content = get_the_content();
        $has_url = get_url_in_content($content);
        return ($has_url) ? $has_url : apply_filters('the_permalink', esc_url(get_permalink()));
    }
endif;
/**
 * Handles the output of the media for audio attachment posts. This should be used within The Loop.
 *
 * @return string
 */
function blogtwist_audio_attachment()
{
    return blogtwist_hybrid_media_grabber(array('type' => 'audio', 'split_media' => true));
}

/**
 * Handles the output of the media for video attachment posts. This should be used within The Loop.
 *
 * @return string
 */
function blogtwist_video_attachment()
{
    return blogtwist_hybrid_media_grabber(array('type' => 'video', 'split_media' => true));
}

if (!function_exists('blogtwist_get_rendered_content')) :
    /**
     * Return the rendered post content.
     *
     * This is the same as the_content() except for the fact that it doesn't display the content, but returns it.
     * Do make sure not to use this function twice for a post inside the loop, because it would defeat the purpose.
     *
     * @param string $more_link_text Optional. Content for when there is more text.
     * @param bool $strip_teaser Optional. Strip teaser content before the more text. Default is false.
     * @return string
     */
    function blogtwist_get_rendered_content($more_link_text = null, $strip_teaser = false)
    {
        $content = get_the_content($more_link_text, $strip_teaser);
        /**
         * Filters the post content.
         *
         * @param string $content Content of the current post.
         * @since 0.71
         *
         */
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        return $content;
    }
endif;
if (!function_exists('wp_body_open')) :
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     * @since BlogTwist 1.0.0
     */
    function wp_body_open()
    {
        /**
         * Triggered after the opening <body> tag.
         *
         * @since BlogTwist 1.0.0
         */
        do_action('wp_body_open');
    }
endif;

function blogtwist_excerpt_more($more) {
    return ' <a href="' . get_permalink() . '">' . __('Continue reading', 'blogtwist') . '</a>';
}
add_filter('excerpt_more', 'blogtwist_excerpt_more');


if (!function_exists('blogtwist_get_page_layout')) :
    /**
     * Get Page Layout based on the post meta or customizer value
     *
     * @return string Page Layout.
     * @since 1.0.0
     *
     */
    function blogtwist_get_page_layout()
    {
        global $post;
        $page_layout = '';
        // Fetch from Post Meta on single posts or pages.
        if ($post && is_singular()) {
            $page_layout = get_post_meta($post->ID, 'blogtwist_page_layout', true);
            if (empty($page_layout) && is_single()) {
                $page_layout = get_theme_mod('select_single_sidebar','right_sidebar');
            }
        }
        // Fetch from customizer if everything else fails.
        if (empty($page_layout)) {
            $page_layout = get_theme_mod('select_single_sidebar','right_sidebar');
        }
        return $page_layout;
    }
endif;