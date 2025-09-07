<?php
/**
 * Displays the footer widget area.
 *
 * @package BlogTwist
 */

// Return early if no footer widget areas are active
if (
    !is_active_sidebar('footer-1') &&
    !is_active_sidebar('footer-2') &&
    !is_active_sidebar('footer-3') &&
    !is_active_sidebar('footer-4')
) {
    return;
}

// Retrieve settings from the customizer
$blogtwist_footer_widget_layout = get_theme_mod('blogtwist_footer_widget_layout','footer_layout_2');
$enable_footer_widget = get_theme_mod('enable_footer_widget', true);

// Exit if footer widgets are disabled
if (empty($enable_footer_widget)) {
    return;
}

// Default footer configuration
$footer_column = 4;
$footer_class = 'column-md-6 column-lg-3';

// Match column layout to appropriate classes
switch ($blogtwist_footer_widget_layout) {
    case 'footer_layout_1':
        $footer_column = 4;
        $footer_class = 'column-md-6 column-lg-3';
        break;
    case 'footer_layout_2':
        $footer_column = 3;
        $footer_class = 'column-md-6 column-lg-4';
        break;
    case 'footer_layout_3':
        $footer_column = 2;
        $footer_class = 'column-md-6 column-lg-6';
        break;
    default:
        $footer_column = 1;
        $footer_class = 'column-md-12 column-lg-12';
}

// Allow filters to modify the number of footer columns
$cols = intval(apply_filters('blogtwist_footer_widget_columns', $footer_column));

// Determine the number of active columns
$columns = 0;
for ($j = $cols; $j > 0; $j--) {
    if (is_active_sidebar('footer-' . $j)) {
        $columns = $j;
        break;
    }
}

// If there are active columns, display the footer widget area
if ($columns > 0) :
    do_action('blogtwist_top_footer_widgets');
    ?>
    <div class="wpmotif-element-widgets regular-widget-area footer-widget-area">
        <div class="site-wrapper">
            <div class="row-group">
                <?php
                for ($column = 1; $column <= $columns; $column++) :
                    if (is_active_sidebar('footer-' . $column)) :
                        // Apply column class
                        $footer_display_class = is_array($footer_class) ? $footer_class[$column - 1] : $footer_class;
                        ?>
                        <div class="column-sm-12 <?php echo esc_attr($footer_display_class); ?> footer-widget-<?php echo esc_attr($column); ?>">
                            <?php dynamic_sidebar('footer-' . $column); ?>
                        </div>
                    <?php
                    endif;
                endfor;
                ?>
            </div>
        </div>
    </div>
    <?php
    do_action('blogtwist_bottom_footer_widgets');
endif;