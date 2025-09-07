<?php
if (!defined('ABSPATH')) {
    exit;
}
class Blogtwist_Recent_Posts extends Blogtwist_Widget_Base
{
    public function __construct()
    {
        $this->widget_cssclass = 'blogtwist-recent-widget';
        $this->widget_description = __("Displays recent posts with an image", 'blogtwist');
        $this->widget_id = 'blogtwist_recent_posts';
        $this->widget_name = __('BlogTwist: Recent Posts', 'blogtwist');
        $this->settings = $this->get_widget_settings();
        parent::__construct();
    }
    /**
     * Define widget settings.
     */
    protected function get_widget_settings()
    {
        return array(
            'title' => array(
                'type' => 'text',
                'label' => __('Title', 'blogtwist'),
                'std' => __('Recent Posts', 'blogtwist'),
            ),
            'style' => array(
                'type' => 'select',
                'label' => __('Display Style', 'blogtwist'),
                'options' => array(
                    'wpmotif-default-post' => __('Regular View', 'blogtwist'),
                    'wpmotif-list-post' => __('List View', 'blogtwist'),
                    'wpmotif-card-post' => __('Card View', 'blogtwist'),
                ),
                'std' => 'wpmotif-list-post',
            ),
            'font_size' => array(
                'type' => 'select',
                'label' => __('Entry title font size', 'blogtwist'),
                'options' => array(
                    'entry-title-xs' => __('Extra Small', 'blogtwist'),
                    'entry-title-small' => __('Small', 'blogtwist'),
                    'entry-title-medium' => __('Medium', 'blogtwist'),
                    'entry-title-big' => __('Big', 'blogtwist'),
                ),
                'std' => 'entry-title-xs',
            ),
            'font_style' => array(
                'type' => 'select',
                'label' => __('Entry title font style', 'blogtwist'),
                'options' => array(
                    'entry-title-normal' => __('Normal', 'blogtwist'),
                    'entry-title-italic' => __('Italic', 'blogtwist'),
                ),
                'std' => 'entry-title-normal',
            ),
            'category' => array(
                'type' => 'dropdown-taxonomies',
                'label' => __('Select Category', 'blogtwist'),
                'args' => array(
                    'taxonomy' => 'category',
                    'class' => 'widefat',
                    'hierarchical' => true,
                    'show_count' => 1,
                    'show_option_all' => __('&mdash; Select &mdash;', 'blogtwist'),
                ),
            ),
            'number' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 1,
                'std' => 5,
                'label' => __('Number of posts to show', 'blogtwist'),
            ),
            'offset' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 0,
                'label' => __('Offset', 'blogtwist'),
                'desc' => __('Skips a certain number of posts. Set 0 if not used.', 'blogtwist'),
            ),
            'orderby' => array(
                'type' => 'select',
                'std' => 'date',
                'label' => __('Order by', 'blogtwist'),
                'options' => array(
                    'date' => __('Date', 'blogtwist'),
                    'ID' => __('ID', 'blogtwist'),
                    'title' => __('Title', 'blogtwist'),
                    'rand' => __('Random', 'blogtwist'),
                ),
            ),
            'order' => array(
                'type' => 'select',
                'std' => 'desc',
                'label' => __('Order', 'blogtwist'),
                'options' => array(
                    'asc' => __('ASC', 'blogtwist'),
                    'desc' => __('DESC', 'blogtwist'),
                ),
            ),
            'show_counter' => array(
                'type' => 'checkbox',
                'label' => __('Show Counter', 'blogtwist'),
                'std' => true,
            ),
            'show_image' => array(
                'type' => 'checkbox',
                'label' => __('Show Image', 'blogtwist'),
                'std' => true,
            ),
            'image_size' => array(
                'type' => 'select',
                'label' => __('Image size', 'blogtwist'),
                'options' => array(
                    'thumbnail' => __('Thumbnail', 'blogtwist'),
                    'medium' => __('Medium', 'blogtwist'),
                    'medium_large' => __('Medium Large', 'blogtwist'),
                    'large' => __('Large', 'blogtwist'),
                    'full' => __('Full', 'blogtwist'),
                ),
                'std' => 'medium',
            ),
            'show_date' => array(
                'type' => 'checkbox',
                'label' => __('Show Date', 'blogtwist'),
                'std' => true,
            ),
            'date_format' => array(
                'type' => 'select',
                'label' => __('Date Format', 'blogtwist'),
                'options' => array(
                    'time_ago' => __('Format 1', 'blogtwist'),
                    'classic' => __('Format 2', 'blogtwist'),
                ),
                'std' => 'time_ago',
            ),
            'display_date_option' => array(
                'type' => 'select',
                'label' => __('Date Option', 'blogtwist'),
                'options' => array(
                    'with_label'   => __( 'With Label', 'blogtwist' ),
                    'with_icon'   => __( 'With Icon', 'blogtwist' ),
                ),
                'std' => 'with_icon',
            ),
            'date_text' => array(
                'type' => 'text',
                'label' => __('Date Text', 'blogtwist'),
                'std' => __('By:', 'blogtwist'),
                'desc' => __('This only works when the "With Label" option is selected under "Date Option"', 'blogtwist'),
            ),
            'show_author' => array(
                'type' => 'checkbox',
                'label' => __('Show Author', 'blogtwist'),
                'std' => false,
            ),
            'display_author_option' => array(
                'type' => 'select',
                'label' => __('Author Option', 'blogtwist'),
                'options' => array(
                    'with_label' => __('With Label', 'blogtwist'),
                    'with_icon' => __('With Icon', 'blogtwist'),
                    'with_avatar_image' => __('With Avatar Image', 'blogtwist'),
                ),
                'std' => 'with_icon',
            ),
            'author_text' => array(
                'type' => 'text',
                'label' => __('Author Text', 'blogtwist'),
                'std' => __('By:', 'blogtwist'),
                'desc' => __('This only works when the "With Label" option is selected under "Author Option"', 'blogtwist'),
            ),
            'show_category' => array(
                'type' => 'checkbox',
                'label' => __('Show Category', 'blogtwist'),
                'std' => false,
            ),
            'category_text' => array(
                'type' => 'text',
                'label' => __('Category Text', 'blogtwist'),
            ),
            'display_category_option' => array(
                'type' => 'select',
                'label' => __('Category Option', 'blogtwist'),
                'options' => array(
                    'none' => __('None', 'blogtwist'),
                    'has-background' => __('Has background', 'blogtwist'),
                    'has-text-color' => __('Has text color', 'blogtwist'),
                ),
                'std' => 'has-text-color',
            ),
            'number_of_cat' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 1,
                'std' => 1,
                'label' => __('Number of Category to show', 'blogtwist'),
            ),
        );
    }
    /**
     * Query the posts and return them.
     */
    protected function get_posts($args, $instance)
    {
        $query_args = array(
            'posts_per_page' => !empty($instance['number']) ? absint($instance['number']) : $this->settings['number']['std'],
            'post_status' => 'publish',
            'no_found_rows' => 1,
            'orderby' => !empty($instance['orderby']) ? sanitize_text_field($instance['orderby']) : $this->settings['orderby']['std'],
            'order' => !empty($instance['order']) ? sanitize_text_field($instance['order']) : $this->settings['order']['std'],
            'offset' => !empty($instance['offset']) ? absint($instance['offset']) : (isset($this->settings['offset']['std']) ? absint($this->settings['offset']['std']) : 0),
            'ignore_sticky_posts' => 1
        );
        if (isset($instance['offset']) && absint($instance['offset']) != 0) {
            $query_args['offset'] = absint($instance['offset']);
        }
        if (!empty($instance['category']) && -1 != $instance['category'] && 0 != $instance['category']) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $instance['category'],
            );
        }
        return new WP_Query(apply_filters('blogtwist_recent_posts_query_args', $query_args));
    }
    /**
     * Output widget content.
     */
    public function widget($args, $instance)
    {
        $posts = $this->get_posts($args, $instance);
        if (!$posts->have_posts()) {
            return;
        }
        echo $args['before_widget'];
        do_action('blogtwist_before_recent_posts_with_image');
        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
        }
        echo '<div class="wpmotif-custom-widget wpmotif-recent-widget">';
        $counter = 1;
        while ($posts->have_posts()) {
            $posts->the_post();
            $this->render_post($instance, $counter);
            $counter++;
        }
        wp_reset_postdata();
        echo '</div>';
        do_action('blogtwist_after_recent_posts_with_image');
        echo $args['after_widget'];
    }
    /**
     * Render a single post item.
     */
    protected function render_post($instance, $counter)
    {
        $counter_class = '';
        $style = !empty($instance['style']) ? $instance['style'] : $this->settings['style']['std'];
        $font_size = !empty($instance['font_size']) ? $instance['font_size'] : $this->settings['font_size']['std'];
        $font_style = !empty($instance['font_style']) ? $instance['font_style'] : $this->settings['font_style']['std'];
        $image_size = !empty($instance['image_size']) ? $instance['image_size'] : $this->settings['image_size']['std'];
        $show_counter = isset($instance['show_counter']) ? (bool)$instance['show_counter'] : $this->settings['show_counter']['std'];
        if ($show_counter) {
            $counter_class = 'has-post-counter';
        }

        $show_author = !empty($instance['show_author']) ? $instance['show_author'] : $this->settings['show_author']['std'];
        $author_text = !empty($instance['author_text']) ? $instance['author_text'] : $this->settings['author_text']['std'];
        $display_author_option = !empty($instance['display_author_option']) ? $instance['display_author_option'] : $this->settings['display_author_option']['std'];
        $category_text = !empty($instance['category_text']) ? $instance['category_text'] : '';
        $display_category_option = !empty($instance['display_category_option']) ? $instance['display_category_option'] : $this->settings['display_category_option']['std'];
        $number_of_cat = !empty($instance['number_of_cat']) ? absint($instance['number_of_cat']) : $this->settings['number_of_cat']['std'];
        $date_format = !empty($instance['date_format']) ? $instance['date_format'] : $this->settings['date_format']['std'];
        $display_date_option = !empty($instance['display_date_option']) ? $instance['display_date_option'] : $this->settings['display_date_option']['std'];
        $date_text = !empty($instance['date_text']) ? $instance['date_text'] : $this->settings['date_text']['std'];

        ?>
        <article id="recent-post-<?php echo the_ID(); ?>" <?php post_class('wpmotif-post  ' . esc_attr($counter_class) . ' ' . esc_attr($style) . ' '); ?>>

            <?php if (has_post_thumbnail() && !empty($instance['show_image'])) : ?>
                <?php if (!empty($instance['show_counter']) && $instance['show_counter']) { ?>
                    <div class="wpmotif-post-counter">
                        <span><?php echo $counter; ?></span>
                    </div>
                <?php } ?>
                <div class="entry-thumbnail">
                    <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                        <?php
                        the_post_thumbnail(
                            $image_size,
                            array(
                                'alt' => the_title_attribute(
                                    array(
                                        'echo' => false,
                                    )
                                ),
                            )
                        );
                        ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="entry-details">
                <?php
                if (!empty($instance['show_category']) && $instance['show_category']) {
                    blogtwist_post_category($display_category_option, $category_text, $number_of_cat);
                }
                ?>
                <?php if (!empty($instance['show_author']) && $instance['show_author']) {
                    blogtwist_posted_by($display_author_option, $author_text);
                } ?>
                <header class="entry-header">
                    <?php the_title('<h3 class="entry-title ' . $font_size . ' ' . $font_style . '"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
                </header>
                <?php 
                if (!empty($instance['show_date']) && $instance['show_date']) {
                    blogtwist_posted_on($date_format, $date_text, $display_date_option);
                } ?>

            </div>
        </article>
        <?php
    }
}