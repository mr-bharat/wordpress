<?php
/**
 * Custom functions that act independently of the theme templates
 * Eventually, some of the functionality here could be replaced by core features
 * @package BlogTwist 1.0.0
 */
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function blogtwist_page_menu_args($args)
{
    $args['show_home'] = true;
    return $args;
}

add_filter('wp_page_menu_args', 'blogtwist_page_menu_args');
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function blogtwist_body_classes($classes)
{
    // Adds a class of group-blog to blogs with more than 1 published author.
    if (is_multi_author()) {
        $classes[] = 'group-blog';
    }
    $blogtwist_enable_cursor = get_theme_mod('blogtwist_enable_cursor', false);
    $blogtwist_sticky_sidebar_section = get_theme_mod('blogtwist_sticky_sidebar_section', true);

    if ($blogtwist_enable_cursor) {
        $classes[] = 'has-custom-cursor';
    }
    if ($blogtwist_sticky_sidebar_section) {
        $classes[] = 'has-sticky-sidebar';
    }
    // Adds a class of no_sidebar when there is no sidebar present.
    $page_layout = is_active_sidebar('sidebar') ? blogtwist_get_page_layout() : 'no_sidebar';

    $classes[] = ($page_layout === 'no_sidebar') ? 'no_sidebar' : esc_attr($page_layout);

    if (is_active_sidebar('sidebar')) {
        $sidebars_widgets = wp_get_sidebars_widgets();
        if (1 < count($sidebars_widgets['sidebar']) || (!empty($sidebars_widgets['sidebar']) && false === strpos(reset($sidebars_widgets['sidebar']), 'eu_cookie_law'))) {
            if ($page_layout != 'no_sidebar') {
                $classes[] = 'has_sidebar';
            }
        }
    }
    return $classes;
}

add_filter('body_class', 'blogtwist_body_classes');
/**
 * Extend the default WordPress post classes.
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 * @since BlogTwist 1.0
 *
 */
function blogtwist_post_classes($classes)
{
    if (is_archive() || is_home() || is_search()) {
        $classes[] = 'grid__item';
    }
    return $classes;
}

add_filter('post_class', 'blogtwist_post_classes');
/**
 * Filter wp_link_pages to wrap current page in span.
 *
 * @param $link
 *
 * @return string
 */
function blogtwist_link_pages($link)
{
    if (is_numeric($link)) {
        return '<span class="current">' . $link . '</span>';
    }
    return $link;
}

add_filter('wp_link_pages_link', 'blogtwist_link_pages');
function blogtwist_excerpt_length($length)
{
    return 30;
}

add_filter('excerpt_length', 'blogtwist_excerpt_length', 999);
function blogtwist_validate_gravatar($email)
{
    if (!empty($email)) {
        // Craft a potential url and test the response
        $email_hash = md5(strtolower(trim($email)));
        if (is_ssl()) {
            $host = 'https://secure.gravatar.com';
        } else {
            $host = sprintf("http://%d.gravatar.com", (hexdec($email_hash[0]) % 2));
        }
        $uri = $host . '/avatar/' . $email_hash . '?d=404';
        //make request and test response
        if (404 === wp_remote_retrieve_response_code(wp_remote_get($uri))) {
            $has_valid_avatar = false;
        } else {
            $has_valid_avatar = true;
        }
        return $has_valid_avatar;
    }
    return false;
}

/**
 * Wrap more link
 */
function blogtwist_read_more_link($link)
{
    return '<div class="more-link-wrapper">' . $link . '</div>';
}

add_filter('the_content_more_link', 'blogtwist_read_more_link');

/**
 * Add "Styles" drop-down
 */
function blogtwist_mce_editor_buttons($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}

add_filter('mce_buttons_2', 'blogtwist_mce_editor_buttons');
/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function blogtwist_skip_link_focus_fix()
{
    // The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
    ?>
    <script>
        /(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function () {
            var t, e = location.hash.substring(1);
            /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
        }, !1);
    </script>
    <?php
}

// We will put this script inline since it is so small.
add_action('wp_print_footer_scripts', 'blogtwist_skip_link_focus_fix');
/**
 * Filters classes of wp_list_pages items to match menu items.
 *
 * Filter the class applied to wp_list_pages() items with children to match the menu class, to simplify.
 * styling of sub levels in the fallback. Only applied if the match_menu_classes argument is set.
 *
 * @param string[] $css_class An array of CSS classes to be applied to each list item.
 * @param WP_Post $page Page data object.
 * @param int $depth Depth of page, used for padding.
 * @param array $args An array of arguments.
 * @return array CSS class names.
 * @since BlogTwist 1.0.0
 *
 */
function blogtwist_filter_wp_list_pages_item_classes($css_class, $page, $depth, $args)
{
    // Only apply to wp_list_pages() calls with match_menu_classes set to true.
    $match_menu_classes = isset($args['match_menu_classes']);
    if (!$match_menu_classes) {
        return $css_class;
    }
    // Add current menu item class.
    if (in_array('current_page_item', $css_class, true)) {
        $css_class[] = 'current-menu-item';
    }
    // Add menu item has children class.
    if (in_array('page_item_has_children', $css_class, true)) {
        $css_class[] = 'menu-item-has-children';
    }
    return $css_class;
}

add_filter('page_css_class', 'blogtwist_filter_wp_list_pages_item_classes', 10, 4);
/**
 * Adds a Sub Nav Toggle to the Expanded Menu and Mobile Menu.
 *
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param WP_Post $item Menu item data object.
 * @return stdClass An object of wp_nav_menu() arguments.
 * @since BlogTwist 1.0
 *
 */
function blogtwist_add_sub_toggles_to_main_menu($args, $item)
{
    // Add sub menu toggles to the Expanded Menu with toggles.
    if (isset($args->show_toggles) && $args->show_toggles) {
        // Wrap the menu item link contents in a div, used for positioning.
        $args->before = '<div class="ancestor-wrapper">';
        $args->after = '';
        // Add a toggle to items with children.
        if (in_array('menu-item-has-children', $item->classes, true)) {
            $toggle_target_string = '.menu-modal .menu-item-' . $item->ID . ' > .sub-menu';
            $toggle_duration = blogtwist_toggle_duration();
            // Add the sub menu toggle.
            $args->after .= '<button class="toggle sub-menu-toggle" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="' . absint($toggle_duration) . '" aria-expanded="false"><span class="screen-reader-text">' .
                /* translators: Hidden accessibility text. */
                __('Show sub menu', 'blogtwist') .
                '</span>' . blogtwist_get_theme_svg('chevron-down') . '</button>';
        }
        // Close the wrapper.
        $args->after .= '</div><!-- .ancestor-wrapper -->';
        // Add sub menu icons to the primary menu without toggles.
    } elseif ('primary' === $args->theme_location) {
        if (in_array('menu-item-has-children', $item->classes, true)) {
            $args->link_after = '<span class="icon">' . blogtwist_get_theme_svg('chevron-down') . '</span>';
        } else {
            $args->link_after = '';
        }
    }
    return $args;
}

add_filter('nav_menu_item_args', 'blogtwist_add_sub_toggles_to_main_menu', 10, 2);

/**
 * Displays SVG icons in social links menu.
 *
 * @param string $item_output The menu item's starting HTML output.
 * @param WP_Post $item Menu item data object.
 * @param int $depth Depth of the menu. Used for padding.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @return string The menu item output with social icon.
 * @since BlogTwist 1.0
 *
 */
function blogtwist_nav_menu_social_icons($item_output, $item, $depth, $args)
{
    // Change SVG icon inside social links menu if there is supported URL.
    if ('social' === $args->theme_location) {
        $svg = BlogTwist_SVG_Icons::get_social_link_svg($item->url);
        if (empty($svg)) {
            $svg = blogtwist_get_theme_svg('link');
        }
        $item_output = str_replace($args->link_after, '</span>' . $svg, $item_output);
    }
    return $item_output;
}

add_filter('walker_nav_menu_start_el', 'blogtwist_nav_menu_social_icons', 10, 4);
if (!function_exists('blogtwist_get_social_links_styles')) :
    /**
     * Returns social links styles options.
     *
     * @return array Options array.
     * @since 1.0.0
     *
     */
    function blogtwist_get_social_links_styles()
    {
        $options = apply_filters(
            'blogtwist_social_links_styles',
            array(
                'style_1' => __('Style 1', 'blogtwist'),
                'style_2' => __('Style 2', 'blogtwist'),
                'style_3' => __('Style 3', 'blogtwist'),
                'style_4' => __('Style 4', 'blogtwist'),
            )
        );
        return $options;
    }
endif;
/**
 * Toggles animation duration in milliseconds.
 *
 * @return int Duration in milliseconds
 * @since BlogTwist 1.0
 *
 */
function blogtwist_toggle_duration()
{
    /**
     * Filters the animation duration/speed used usually for submenu toggles.
     *
     * @param int $duration Duration in milliseconds.
     * @since BlogTwist 1.0
     *
     */
    $duration = apply_filters('blogtwist_toggle_duration', 250);
    return $duration;
}

if (!function_exists('blogtwist_archive_post_count')) {
    /**
     * Post Count in Archive Pages
     */
    function blogtwist_archive_post_count()
    {
        global $wp_query;
        $found_posts = $wp_query->found_posts;
        if ($found_posts > 0) {
            ?>
            <div class="wpmotif-archive-post-count">
                <?php
                /* translators: 1: Singular, 2: Plural. */
                $found_posts_count = sprintf(_n('%s post', '%s posts', $found_posts, 'blogtwist'), $found_posts);
                /**
                 * The blogtwist_article_full_count hook.
                 *
                 * @since 1.0.0
                 */
                echo esc_html(apply_filters('blogtwist_article_full_count', $found_posts_count, $found_posts));
                ?>
            </div>
            <?php
        }
    }
}


if (!function_exists('blogtwist_estimated_read_time')) :
    /**
     * Estimated reading time in minutes
     *
     * @param $content
     * @param $with_gutenberg
     *
     * @return int estimated time in minutes
     */
    function blogtwist_estimated_read_time($content = '', $with_gutenberg = false)
    {
        // In case if content is build with gutenberg parse blocks.
        if ($with_gutenberg) {
            $blocks = parse_blocks($content);
            $contentHtml = '';
            foreach ($blocks as $block) {
                $contentHtml .= render_block($block);
            }
            $content = $contentHtml;
        }
        // Remove HTML tags from string.
        $content = wp_strip_all_tags($content);
        // When content is empty return 0.
        if (!$content) {
            return 0;
        }
        // Count words containing string.
        $words_count = str_word_count($content);
        // Words per minute.
        $words_per_minute = 200;
        // Calculate time for read all words and round.
        $minutes = ceil($words_count / $words_per_minute);
        return $minutes;
    }
endif;

if (!function_exists('blogtwist_get_readtime')) :
    /**
     * Print archive excerpt
     *
     * @return string Page ID.
     * @since 1.0.0
     *
     */
    function blogtwist_get_readtime()
    { ?>
        <span class="entry-meta entry-read-time">
            <span class="screen-reader-text"><?php esc_html_e('Estimated read time', 'blogtwist'); ?></span>
           <?php
            blogtwist_the_theme_svg('hourglass');
            ?>
            <?php
                $read_time = blogtwist_estimated_read_time(get_the_content());
                printf( /* translators: %s: Read Time. */
                    esc_html__('%s min read', 'blogtwist'),
                    number_format_i18n($read_time)
                );
            ?>
        </span>
    <?php }
endif;