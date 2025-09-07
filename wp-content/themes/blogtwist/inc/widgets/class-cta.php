<?php
if (!defined('ABSPATH')) {
    exit;
}
class Blogtwist_CTA extends Blogtwist_Widget_Base
{
    public function __construct()
    {
        $this->widget_cssclass = 'widget_blogtwist_cat';
        $this->widget_description = __("Displays call to action button and text with background", 'blogtwist');
        $this->widget_id = 'blogtwist_cat';
        $this->widget_name = __('BlogTwist: CTA', 'blogtwist');
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
                'label' => __('CTA Title', 'blogtwist'),
            ),
            'font_size' => array(
                'type' => 'select',
                'label' => __('Heading font size', 'blogtwist'),
                'options' => array(
                    'entry-title-small' => __('Small', 'blogtwist'),
                    'entry-title-medium' => __('Medium', 'blogtwist'),
                    'entry-title-big' => __('Big', 'blogtwist'),
                    'entry-title-large' => __('Large', 'blogtwist'),
                ),
                'std' => 'entry-title-large',
            ),
            'font_style' => array(
                'type' => 'select',
                'label' => __('Heading font style', 'blogtwist'),
                'options' => array(
                    'entry-title-normal' => __('Normal', 'blogtwist'),
                    'entry-title-italic' => __('Italic', 'blogtwist'),
                ),
                'std' => 'entry-title-normal',
            ),
            'description' => array(
                'type' => 'textarea',
                'label' => __('CTA Description', 'blogtwist'),
                'rows' => 10,
            ),
            'button_text_1' => array(
                'type' => 'text',
                'label' => __('Button Text - 1', 'blogtwist'),
            ),
            'button_link_1' => array(
                'type' => 'url',
                'label' => __('Link to url - 1', 'blogtwist'),
                'desc' => __('Enter a proper url with http: OR https:', 'blogtwist'),
            ),
            'link_target_1' => array(
                'type' => 'checkbox',
                'label' => __('Open Link in new Tab - 1', 'blogtwist'),
                'std' => true,
            ),
            'button_text_2' => array(
                'type' => 'text',
                'label' => __('Button Text - 2', 'blogtwist'),
            ),
            'button_link_2' => array(
                'type' => 'url',
                'label' => __('Link to url - 2', 'blogtwist'),
                'desc' => __('Enter a proper url with http: OR https:', 'blogtwist'),
            ),
            'link_target_2' => array(
                'type' => 'checkbox',
                'label' => __('Open Link in new Tab - 2', 'blogtwist'),
                'std' => true,
            ),
            'widget_settings' => array(
                'type' => 'heading',
                'label' => __('Widget Settings', 'blogtwist'),
            ),
            'display_layout' => array(
                'type' => 'select',
                'label' => __('Display Layout', 'blogtwist'),
                'options' => array(
                    'display-regular-layout' => __('Regular layout', 'blogtwist'),
                    'display-fullwidth-layout' => __('Full Width Layout', 'blogtwist'),
                ),
                'std' => 'display-regular-layout',
            ),
            'text_alignment' => array(
                'type' => 'select',
                'label' => __('Text Alignment', 'blogtwist'),
                'options' => array(
                    'align-flex-start' => __('Left', 'blogtwist'),
                    'align-flex-center' => __('Center', 'blogtwist'),
                    'align-flex-end' => __('Right', 'blogtwist'),
                ),
                'std' => 'align-flex-start',
            ),
            'vertical_alignment' => array(
                'type' => 'select',
                'label' => __('Vertical Alignment', 'blogtwist'),
                'options' => array(
                    'justify-content-center' => __('Middle', 'blogtwist'),
                    'justify-content-left' => __('Top', 'blogtwist'),
                    'justify-content-right' => __('Bottom', 'blogtwist'),
                ),
                'std' => 'justify-content-right',
            ),
            'text_color' => array(
                'type' => 'color',
                'label' => __('Text Color', 'blogtwist'),
                'std' => '#ffffff',
            ),
            'bg_color' => array(
                'type' => 'color',
                'label' => __('Background Color', 'blogtwist'),
                'std' => '#000000',
                'desc' => __('Will be overridden if used background image.', 'blogtwist'),
            ),
            'bg_image' => array(
                'type' => 'image',
                'label' => __('Background Image', 'blogtwist'),
            ),
            'bg_overlay_color' => array(
                'type' => 'color',
                'label' => __('Overlay Color', 'blogtwist'),
                'std' => '#000000',
            ),
            'overlay_opacity' => array(
                'type' => 'number',
                'step' => 10,
                'min' => 0,
                'max' => 100,
                'std' => 50,
                'label' => __('Overlay Opacity', 'blogtwist'),
            ),
            'height' => array(
                'type' => 'number',
                'step' => 1,
                'min' => 150,
                'max' => '',
                'std' => 680,
                'label' => __('Height (px)', 'blogtwist'),
                'desc' => __('Works when there is either a background color or image.', 'blogtwist'),
            ),
        );
    }
    /**
     * Output widget.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        ob_start();
        $style = '';
        // Default settings fallback
        $font_size = $instance['font_size'] ?? $this->settings['font_size']['std'];
        $font_style = $instance['font_style'] ?? $this->settings['font_style']['std'];
        $text_alignment = $instance['text_alignment'] ?? $this->settings['text_alignment']['std'];
        $vertical_alignment = $instance['vertical_alignment'] ?? $this->settings['vertical_alignment']['std'];
        $class = $instance['display_layout'] ?? $this->settings['display_layout']['std'];
        echo $args['before_widget'];
        $bg_color = $instance['bg_color'] ?? $this->settings['bg_color']['std'];
        $text_color = $instance['text_color'] ?? $this->settings['text_color']['std'];
        $style = 'background-color:' . esc_attr($bg_color) . ';';
        $style .= '--cta-text-color:' . esc_attr($text_color) . ';';
        $height = $instance['height'] ?? $this->settings['height']['std'];
        $style .= 'height:' . esc_attr($height) . 'px;';
        $image_enabled = !empty($instance['bg_image']);
        do_action('blogtwist_before_cta');
        ?>
        <div class="wpmotif-custom-widget wpmotif-cta-widget <?php echo esc_attr($class); ?>"
             style="<?php echo esc_attr($style); ?>">
            <?php if ($image_enabled):
                $overlay_color = $instance['bg_overlay_color'] ?? $this->settings['bg_overlay_color']['std'];
                $overlay_opacity = isset($instance['overlay_opacity']) ? ($instance['overlay_opacity'] / 100) : ($this->settings['overlay_opacity']['std'] / 100);
                $overlay_style = 'background-color:' . esc_attr($overlay_color) . ';opacity:' . esc_attr($overlay_opacity) . ';';
                ?>
                <span aria-hidden="true" class="background-image-overlay"
                      style="<?php echo esc_attr($overlay_style); ?>"></span>
                <?php echo wp_get_attachment_image($instance['bg_image'], 'full'); ?>
            <?php endif; ?>
            <div class="widget-wrapper <?php echo esc_attr("{$text_alignment} {$vertical_alignment}"); ?>">
                <div class="row-group">
                    <div class="column-sm-12 column-md-10 column-lg-9">
                        <?php if (!empty($instance['title'])) : ?>
                            <h2 class="widget-title">
                                <?php echo esc_html($instance['title']); ?>
                            </h2>
                        <?php endif; ?>
                        <?php if (!empty($instance['title_text'])) : ?>
                            <h3 class="entry-title <?php echo esc_attr($font_size . ' ' . $font_style); ?>">
                                <?php echo esc_html($instance['title_text']); ?>
                            </h3>
                        <?php endif; ?>
                        <?php if (!empty($instance['description'])) : ?>
                            <?php echo wpautop(wp_kses_post($instance['description'])); ?>
                        <?php endif; ?>
                        <?php if (!empty($instance['button_text_1']) || !empty($instance['button_text_2'])): ?>
                            <div class="wpmotif-button-group">
                                <?php if (!empty($instance['button_text_1'])): ?>
                                    <a href="<?php echo esc_url($instance['button_link_1'] ?? ''); ?>" target="<?php echo !empty($instance['link_target_1']) ? '_blank' : '_self'; ?>" class="wpmotif-button wpmotif-button-secondary">
                                        <?php echo esc_html($instance['button_text_1']); ?>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($instance['button_text_2'])): ?>
                                    <a href="<?php echo esc_url($instance['button_link_2'] ?? ''); ?>" target="<?php echo !empty($instance['link_target_2']) ? '_blank' : '_self'; ?>" class="wpmotif-button wpmotif-button-outline">
                                        <?php echo esc_html($instance['button_text_2']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        do_action('blogtwist_after_cta');
        echo $args['after_widget'];
        echo ob_get_clean();
    }
}