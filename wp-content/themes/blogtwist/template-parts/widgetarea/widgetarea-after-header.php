<?php
if (is_active_sidebar('after-header')) {
    do_action('blogtwist_after_header_widgets_top');
    ?>
    <div class="wpmotif-element-widgets fullwidth-widget-area wpmotif-header-widgets">
        <div class="site-wrapper">
            <?php dynamic_sidebar('after-header'); ?>
        </div>
    </div>
    <?php
    do_action('blogtwist_after_header_widgets_bottom');
}
?>
