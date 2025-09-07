<?php
if (!defined('ABSPATH')) {
    exit;
}

class Blogtwist_Category_Grid extends Blogtwist_Widget_Base
{
    public function __construct()
    {
        $this->widget_cssclass = 'blogtwist-categories-widget';
        $this->widget_description = __('Displays post categories with image in grid', 'blogtwist');
        $this->widget_id = 'blogtwist_categories';
        $this->widget_name = __('BlogTwist: Categories', 'blogtwist');

        $this->settings = $this->get_widget_settings();

        parent::__construct();
    }

    protected function get_widget_settings()
    {
        $post_categories = array();
        $categories = get_categories(array(
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $post_categories[$cat->term_id] = $cat->name;
            }
        }

        return array(
            'title' => array(
                'type' => 'text',
                'label' => __('Title', 'blogtwist'),
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
                'std' => 'entry-title-small',
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
            'categories' => array(
                'type' => 'multi-checkbox',
                'label' => __('Select Categories', 'blogtwist'),
                'desc'  => sprintf(
                    __('To be displayed, a category must have a "Thumbnail" image. %s', 'blogtwist'),
                    '<a href="' . esc_url(admin_url('edit-tags.php?taxonomy=category&post_type=post')) . '" target="_blank">'
                    . __('Click here', 'blogtwist') . '</a> ' . __('to add one.', 'blogtwist')
                ),
                'options' => $post_categories,
            ),
            'no_of_column' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 1,
                'max' => 4,
                'std' => 1,
                'label' => __('Number of Column', 'blogtwist'),
            ),
            'display_style' => array(
                'type' => 'select',
                'label' => __('Display Style', 'blogtwist'),
                'options' => array(
                    'categories-layout-1' => __('Style 1', 'blogtwist'),
                    'categories-layout-2' => __('Style 2', 'blogtwist'),
                    'categories-layout-3' => __('Style 3', 'blogtwist'),
                ),
                'std' => 'categories-layout-1',
            ),
            'show_post_count' => array(
                'type' => 'checkbox',
                'label' => __('Show Post Count', 'blogtwist'),
                'std' => false,
            ),
            'image_size' => array(
                'type' => 'select',
                'label' => __('Image size', 'blogtwist'),
                'options' => array(
                    'entry-image-large' => __('Large', 'blogtwist'),
                    'entry-image-big' => __('Big', 'blogtwist'),
                    'entry-image-medium' => __('Medium', 'blogtwist'),
                    'entry-image-small' => __('Small', 'blogtwist'),
                ),
                'std' => 'entry-image-medium',
            ),
        );
    }

    public function widget($args, $instance)
    {
        ob_start();

        if (!empty($instance['categories'])) {
            echo $args['before_widget'];

            if (!empty($instance['title'])) {
                echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
            }

            $font_size = !empty($instance['font_size']) ? $instance['font_size'] : $this->settings['font_size']['std'];
            $font_style = !empty($instance['font_style']) ? $instance['font_style'] : $this->settings['font_style']['std'];

            $column = isset($instance['no_of_column']) ? $instance['no_of_column'] : $this->settings['no_of_column']['std'];
            $show_post_count = isset($instance['show_post_count']) ? $instance['show_post_count'] : $this->settings['show_post_count']['std'];
            $display_style = isset($instance['display_style']) ? $instance['display_style'] : $this->settings['display_style']['std'];

            $col_class = $this->get_column_class($column);
            $img_size = 'medium_large';
            $wrapper_class = $display_style;
            if ($show_post_count) {
                $wrapper_class .= ' blogtwist-cat-post-count-active';
            }

            $style_attr = ' style="background-color:value;"';
            ?>

            <div class="wpmotif-categories-widget <?php echo esc_attr($wrapper_class); ?>">
                <div class="row-group">
                    <?php foreach ($instance['categories'] as $category) {
                        $cat_info = get_category($category);
                        if ($cat_info && !is_wp_error($cat_info)) {
                            $thumbnail_id = get_term_meta($category, 'thumbnail_id', true);
                            $image_tag = wp_get_attachment_image($thumbnail_id, $img_size);

                            if ($image_tag) {
                                $term_link = get_category_link($category);
                                $post_count = $cat_info->count;
                                $color = get_term_meta($cat_info->term_id, 'category_color', true);
                                $build_style_attr = $color ? str_replace('value', $color, $style_attr) : '';

                                ?>
                                <div class="<?php echo esc_attr($col_class); ?>">
                                    <div class="wpmotif-post">

                                        <div class="entry-thumbnail <?php echo esc_attr($instance['image_size']); ?>">
                                            <a href="<?php echo esc_url($term_link); ?>" class="post-thumbnail" aria-hidden="true" tabindex="-1">
                                                <?php echo $image_tag; ?>
                                            </a>
                                        </div>

                                        <div class="entry-details">
                                            <h3 class="wpmotif-categories-title">
                                                <a href="<?php echo esc_url($term_link); ?>">
                                                    <?php echo esc_html($cat_info->name); ?>
                                                </a>
                                                <?php if ($show_post_count) : ?>
                                                    <span class="blogtwist-cat-post-count" <?php echo $build_style_attr; ?>><?php echo esc_html($post_count); ?></span>
                                                <?php endif; ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } ?>
                </div>
            </div>
            <?php

            echo $args['after_widget'];
        }

        echo ob_get_clean();
    }

    protected function get_column_class($column)
    {
        switch ($column) {
            case 2:
                return 'column-sm-12 column-md-6 column-lg-6';
            case 3:
                return 'column-sm-12 column-md-6 column-lg-4';
            case 4:
                return 'column-sm-12 column-md-6 column-lg-3';
            default:
                return 'column-sm-12';
        }
    }
}
