<?php
if (!defined('ABSPATH')) {
    exit;
}

class Blogtwist_Image_Widget extends Blogtwist_Widget_Base
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->widget_cssclass = 'blogtwist-image-widget';
        $this->widget_description = __("The Image Widget offers versatile design with two style variations. Effortlessly incorporate images, titles, descriptions, and links for dynamic and engaging content presentation in a single widget.", 'blogtwist');
        $this->widget_id = 'blogtwist_image_widget';
        $this->widget_name = __('BlogTwist: Image Widget', 'blogtwist');
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
                'label' => __('Widget Title', 'blogtwist'),
            ),
            'title_text' => array(
                'type' => 'text',
                'label' => __('Widget Description', 'blogtwist'),
            ),
            'bg_image' => array(
                'type' => 'image',
                'label' => __('Background Image', 'blogtwist'),
            ),
            'btn_text' => array(
                'type' => 'text',
                'label' => __('Button Text', 'blogtwist'),
            ),
            'btn_link' => array(
                'type' => 'url',
                'label' => __('Link to URL', 'blogtwist'),
                'desc' => __('Please make sure to provide a complete URL that includes either "http://" or "https://" to ensure the widget operates correctly.', 'blogtwist'),
            ),
            'link_target' => array(
                'type' => 'checkbox',
                'label' => __('Open Link in new Tab', 'blogtwist'),
                'std' => true,
            ),
            'text_alignment' => array(
                'type' => 'select',
                'label' => __('Text Alignment', 'blogtwist'),
                'options' => array(
                    'align-text-center' => __('Center', 'blogtwist'),
                    'align-text-left' => __('Left', 'blogtwist'),
                    'align-text-right' => __('Right', 'blogtwist'),
                ),
                'std' => 'align-text-center',
            ),
            'style' => array(
                'type' => 'select',
                'label' => __('Style', 'blogtwist'),
                'options' => array(
                    'style_1' => __('Style 1', 'blogtwist'),
                    'style_2' => __('Style 2', 'blogtwist'),
                ),
                'std' => 'style_1',
            ),
        );
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
        $class = '';
        ob_start();
        echo $args['before_widget'];
        $class .= $instance['style'];
        $class .= ' ' . $instance['text_alignment'];
        do_action('blogtwist_before_image');
        ?>
        <div class="wpmotif-image-widget <?php echo esc_attr($class); ?>">
            <div class="image-widget-background">
                <?php echo wp_get_attachment_image($instance['bg_image'], 'full'); ?>
            </div>
            <div class="image-widget-description">
                <?php if ($instance['title']) : ?>
                    <h3 class="image-widget-title">
                        <?php echo esc_html($instance['title']); ?>
                    </h3>
                <?php endif; ?>
                <?php if ($instance['title_text']) : ?>
                    <div class="image-widget-details">
                        <?php echo esc_html($instance['title_text']); ?>
                    </div>
                <?php endif; ?>
                <?php if ($instance['btn_text']) : ?>
                    <a href="<?php echo ($instance['btn_link']) ? esc_url($instance['btn_link']) : ''; ?>"
                       target="<?php echo ($instance['link_target']) ? "_blank" : '_self'; ?>"
                       class="wpmotif-button wpmotif-button-small wpmotif-button-primary">
                        <?php echo esc_html(($instance['btn_text'])); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        do_action('blogtwist_after_image');
        echo $args['after_widget'];
        echo ob_get_clean();
    }
}