<?php
if (!defined('ABSPATH')) {
    exit;
}

class Blogtwist_Tab_Post extends Blogtwist_Widget_Base
{
    public $display_style = '';
    private static $counter = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->widget_cssclass = 'blogtwist-tabbed-widget';
        $this->widget_description = __('Displays posts in tab', 'blogtwist');
        $this->widget_id = 'blogtwist_tab_posts';
        $this->widget_name = __('BlogTwist: Tab Posts', 'blogtwist');
        $this->settings = $this->get_widget_settings();
        parent::__construct();
    }

    /**
     * Define widget settings.
     */
    protected function get_widget_settings()
    {
        return array(
            'popular_post_settings' => array(
                'type' => 'heading',
                'label' => __('Popular Post Settings', 'blogtwist'),
            ),
            'show_popular_posts' => array(
                'type' => 'checkbox',
                'label' => __('Show Tab', 'blogtwist'),
                'std' => true,
            ),
            'popular_post_desc' => array(
                'type' => 'subtitle',
                'label' => __('Will display post based on comments count', 'blogtwist'),
            ),
            'popular_posts_title' => array(
                'type' => 'text',
                'label' => __('Title', 'blogtwist'),
                'std' => __('Popular', 'blogtwist'),
                'desc' => __('Leave as it is to show default title or leave blank to only show icon', 'blogtwist'),
            ),
            'popular_post_cat' => array(
                'type' => 'dropdown-taxonomies',
                'label' => __('Select Category', 'blogtwist'),
                'desc' => __('Leave empty if you don\'t want the posts to be category specific', 'blogtwist'),
                'args' => array(
                    'taxonomy' => 'category',
                    'class' => 'widefat',
                    'hierarchical' => true,
                    'show_count' => 1,
                    'show_option_all' => __('&mdash; Select &mdash;', 'blogtwist'),
                ),
            ),
            'popular_post_offset' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 0,
                'max' => '',
                'std' => '',
                'label' => __('Offset', 'blogtwist'),
                'desc' => __('Can be useful if you want to skip certain number of posts. Leave as 0 if you do not want to use it.', 'blogtwist'),
            ),
            'popular_post_orderby' => array(
                'type' => 'select',
                'std' => 'date',
                'label' => __('Order By', 'blogtwist'),
                'options' => array(
                    'date' => __('Date', 'blogtwist'),
                    'ID' => __('ID', 'blogtwist'),
                    'title' => __('Title', 'blogtwist'),
                    'rand' => __('Random', 'blogtwist'),
                ),
            ),
            'popular_post_order' => array(
                'type' => 'select',
                'std' => 'desc',
                'label' => __('Order', 'blogtwist'),
                'options' => array(
                    'asc' => __('ASC', 'blogtwist'),
                    'desc' => __('DESC', 'blogtwist'),
                ),
            ),
            'hot_post_settings' => array(
                'type' => 'heading',
                'label' => __('Hot Post Settings', 'blogtwist'),
            ),
            'show_hot_posts' => array(
                'type' => 'checkbox',
                'label' => __('Show Tab', 'blogtwist'),
                'std' => true,
            ),
            'hot_posts_title' => array(
                'type' => 'text',
                'label' => __('Title', 'blogtwist'),
                'std' => __('Hot', 'blogtwist'),
                'desc' => __('Leave as it is to show default title or leave blank to only show icon', 'blogtwist'),
            ),
            'hot_post_cat' => array(
                'type' => 'dropdown-taxonomies',
                'label' => __('Select Category', 'blogtwist'),
                'desc' => __('Leave empty if you don\'t want the posts to be category specific', 'blogtwist'),
                'args' => array(
                    'taxonomy' => 'category',
                    'class' => 'widefat',
                    'hierarchical' => true,
                    'show_count' => 1,
                    'show_option_all' => __('&mdash; Select &mdash;', 'blogtwist'),
                ),
            ),
            'hot_post_offset' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 0,
                'max' => '',
                'std' => '',
                'label' => __('Offset', 'blogtwist'),
                'desc' => __('Can be useful if you want to skip certain number of posts. Leave as 0 if you do not want to use it.', 'blogtwist'),
            ),
            'hot_post_orderby' => array(
                'type' => 'select',
                'std' => 'date',
                'label' => __('Order By', 'blogtwist'),
                'options' => array(
                    'date' => __('Date', 'blogtwist'),
                    'ID' => __('ID', 'blogtwist'),
                    'title' => __('Title', 'blogtwist'),
                    'rand' => __('Random', 'blogtwist'),
                ),
            ),
            'hot_post_order' => array(
                'type' => 'select',
                'std' => 'desc',
                'label' => __('Order', 'blogtwist'),
                'options' => array(
                    'asc' => __('ASC', 'blogtwist'),
                    'desc' => __('DESC', 'blogtwist'),
                ),
            ),
            'latest_post_settings' => array(
                'type' => 'heading',
                'label' => __('Latest Post Settings', 'blogtwist'),
            ),
            'show_latest_posts' => array(
                'type' => 'checkbox',
                'label' => __('Show Tab', 'blogtwist'),
                'std' => true,
            ),
            'latest_posts_title' => array(
                'type' => 'text',
                'label' => __('Title', 'blogtwist'),
                'std' => __('Latest', 'blogtwist'),
                'desc' => __('Leave as it is to show default title or leave blank to only show icon', 'blogtwist'),
            ),
            'latest_post_cat' => array(
                'type' => 'dropdown-taxonomies',
                'label' => __('Select Category', 'blogtwist'),
                'desc' => __('Leave empty if you don\'t want the posts to be category specific', 'blogtwist'),
                'args' => array(
                    'taxonomy' => 'category',
                    'class' => 'widefat',
                    'hierarchical' => true,
                    'show_count' => 1,
                    'show_option_all' => __('&mdash; Select &mdash;', 'blogtwist'),
                ),
            ),
            'latest_post_offset' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 0,
                'max' => '',
                'std' => '',
                'label' => __('Offset', 'blogtwist'),
                'desc' => __('Can be useful if you want to skip certain number of posts. Leave as 0 if you do not want to use it.', 'blogtwist'),
            ),
            'latest_post_orderby' => array(
                'type' => 'select',
                'std' => 'date',
                'label' => __('Order By', 'blogtwist'),
                'options' => array(
                    'date' => __('Date', 'blogtwist'),
                    'ID' => __('ID', 'blogtwist'),
                    'title' => __('Title', 'blogtwist'),
                    'rand' => __('Random', 'blogtwist'),
                ),
            ),
            'latest_post_order' => array(
                'type' => 'select',
                'std' => 'desc',
                'label' => __('Order', 'blogtwist'),
                'options' => array(
                    'asc' => __('ASC', 'blogtwist'),
                    'desc' => __('DESC', 'blogtwist'),
                ),
            ),
            'general_settings' => array(
                'type' => 'heading',
                'label' => __('General Settings', 'blogtwist'),
            ),
            'number' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 1,
                'max' => '',
                'std' => 5,
                'label' => __('Number of posts to show', 'blogtwist'),
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
            'tab_display_style' => array(
                'type' => 'select',
                'label' => __('Display Style', 'blogtwist'),
                'options' => array(
                    'wpmotif-default-post' => __('Regular View', 'blogtwist'),
                    'wpmotif-list-post' => __('List View', 'blogtwist'),
                    'wpmotif-card-post' => __('Card View', 'blogtwist'),
                ),
                'std' => 'wpmotif-list-post',
            ),
            'show_post_counter' => array(
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
                ),
                'std' => 'thumbnail',
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
        );
    }

    /**
     * Outputs the tab Content
     *
     * @param array $instance
     * @param string $block The block to display.
     */
    public function render_tab_title($instance, $block, $is_active = false)
    {
        if (!$block) {
            return;
        }
        $enabled = isset($instance["show_{$block}_posts"]) ? $instance["show_{$block}_posts"] : $this->settings["show_{$block}_posts"]['std'];
        if ($enabled) :
            $title = isset($instance["{$block}_posts_title"]) ? $instance["{$block}_posts_title"] : $this->settings["{$block}_posts_title"]['std'];
            ?>
            <li tab-data="tab-<?php echo $block; ?>"
                class="tab-<?php echo $block; ?> tabbed-header-item<?php echo ($is_active) ? ' active' : ''; ?>">
                <a href="javascript:void(0)"
                   aria-controls="<?php echo esc_attr($block); ?>-posts-<?php echo $this->widget_id; ?>-block"
                   role="tab">
                    <?php if ($title) : ?>
                        <?php echo $title; ?>
                    <?php endif; ?>
                </a>
            </li>
        <?php
        endif;
    }

    /**
     * Outputs the tab Content
     *
     * @param array $instance
     * @param string $block The block to display.
     */
    public function render_tab_content($instance, $block, $is_active = false)
    {
        $counter_class = '';
        if (!$block) {
            return;
        }
        $enabled = isset($instance["show_{$block}_posts"]) ? $instance["show_{$block}_posts"] : $this->settings["show_{$block}_posts"]['std'];
        if ($enabled) :
            $number = !empty($instance['number']) ? absint($instance['number']) : $this->settings['number']['std'];
            if ('popular' == $block) {
                $orderby = 'comment_count';
            } else {
                $orderby = !empty($instance["{$block}_post_orderby"]) ? sanitize_text_field($instance["{$block}_post_orderby"]) : $this->settings["{$block}_post_orderby"]['std'];
            }
            $order = !empty($instance["{$block}_post_order"]) ? sanitize_text_field($instance["{$block}_post_order"]) : $this->settings["{$block}_post_order"]['std'];
            $offset = !empty($instance["{$block}_post_offset"]) ? sanitize_text_field($instance["{$block}_post_offset"]) : $this->settings["{$block}_post_offset"]['std'];
            $query_args = array(
                'post_type' => 'post',
                'posts_per_page' => $number,
                'post_status' => 'publish',
                'no_found_rows' => 1,
                'orderby' => $orderby,
                'order' => $order,
                'ignore_sticky_posts' => 1,
            );
            if ($offset && 0 != $offset) {
                $query_args['offset'] = absint($offset);
            }
            if (!empty($instance["{$block}_post_cat"]) && -1 != $instance["{$block}_post_cat"] && 0 != $instance["{$block}_post_cat"]) {
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $instance["{$block}_post_cat"],
                );
            }
            $posts = new WP_Query($query_args);
            if ($posts->have_posts()) :
                $this->display_style = isset($instance['tab_display_style']) ? $instance['tab_display_style'] : $this->settings['tab_display_style']['std'];
                $widget_class = $this->display_style;
                $image_size = !empty($instance['image_size']) ? $instance['image_size'] : $this->settings['image_size']['std'];
                $font_size = !empty($instance['font_size']) ? $instance['font_size'] : $this->settings['font_size']['std'];
                $font_style = !empty($instance['font_style']) ? $instance['font_style'] : $this->settings['font_style']['std'];
                $show_post_counter = !empty($instance['show_post_counter']) ? $instance['show_post_counter'] : $this->settings['show_post_counter']['std'];

                $show_author = !empty($instance['show_author']) ? $instance['show_author'] : $this->settings['show_author']['std'];
                $author_text = !empty($instance['author_text']) ? $instance['author_text'] : $this->settings['author_text']['std'];
                $display_author_option = !empty($instance['display_author_option']) ? $instance['display_author_option'] : $this->settings['display_author_option']['std'];
                $category_text = !empty($instance['category_text']) ? $instance['category_text'] : '';
                $display_category_option = !empty($instance['display_category_option']) ? $instance['display_category_option'] : $this->settings['display_category_option']['std'];
                $number_of_cat = !empty($instance['number_of_cat']) ? absint($instance['number_of_cat']) : $this->settings['number_of_cat']['std'];
                $date_format = !empty($instance['date_format']) ? $instance['date_format'] : $this->settings['date_format']['std'];
                $display_date_option = !empty($instance['display_date_option']) ? $instance['display_date_option'] : $this->settings['display_date_option']['std'];
                $date_text = !empty($instance['date_text']) ? $instance['date_text'] : $this->settings['date_text']['std'];

                if ($show_post_counter) {
                    $counter_class = 'has-post-counter';
                }
                $post_count = 1;
                ?>
                <div id="<?php echo esc_attr($block); ?>-posts-<?php echo $this->widget_id; ?>-block" class="wpmotif-custom-widget content-tab-<?php echo $block; ?> tabbed-content-item <?php echo ($is_active) ? ' active' : ''; ?>" role="tabpanel">
                    <?php
                    while ($posts->have_posts()) :
                        $posts->the_post();
                        ?>
                        <article id="tabs-widget-<?php the_ID(); ?>" <?php post_class('wpmotif-post ' . esc_attr($counter_class) . ' ' . esc_attr($widget_class)); ?>>
                            <?php if (has_post_thumbnail() && !empty($instance['show_image'])) : ?>
                                <?php if (!empty($instance['show_post_counter'])) { ?>
                                    <div class="wpmotif-post-counter">
                                        <span><?php echo $post_count++; ?></span>
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
                                <header class="entry-header">
                                    <?php the_title('<h3 class="entry-title ' . $font_size . ' ' . $font_style . '"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
                                </header>


                                <?php if (!empty($instance['show_author']) && $instance['show_author']) {
                                    blogtwist_posted_by($display_author_option, $author_text);
                                } ?>
                                <?php 
                                if (!empty($instance['show_date']) && $instance['show_date']) {
                                    blogtwist_posted_on($date_format, $date_text, $display_date_option);
                                } ?>

                            </div>
                        </article>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php
            endif;
        endif;
    }

    /**
     * Output widget.
     *
     * @param array $args
     * @param array $instance
     * @see WP_Widget
     *
     */
    public function widget($args, $instance)
    {
        ob_start();
        $before_widget = $args['before_widget'];
        $after_widget = $args['after_widget'];
        echo wp_kses_post($before_widget);
        do_action('blogtwist_before_tab_posts');
        ++self::$counter;
        $this->unique_id = 'blogtwist-tab-' . self::$counter;
        ?>
        <div class="wpmotif-tabbed-widget">
            <ul class="tabbed-widget-header reset-list-style" role="tablist" aria-label="<?php esc_attr_e('Tab Navigation', 'blogtwist'); ?>">
                <?php $this->render_tab_title($instance, 'popular', true); ?>
                <?php $this->render_tab_title($instance, 'hot'); ?>
                <?php $this->render_tab_title($instance, 'latest'); ?>
            </ul>
            <div class="tabbed-widget-content">
                <?php $this->render_tab_content($instance, 'popular', true); ?>
                <?php $this->render_tab_content($instance, 'hot'); ?>
                <?php $this->render_tab_content($instance, 'latest'); ?>
            </div>
        </div>
        <?php
        do_action('blogtwist_after_tab_posts');
        echo wp_kses_post($after_widget);
        echo ob_get_clean();
    }
}
