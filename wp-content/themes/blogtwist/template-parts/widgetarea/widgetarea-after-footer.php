<?php
if (is_active_sidebar('after-footer-widgetarea')) {
    do_action('blogtwist_after_footer_widgets_top');
    ?>
    <div class="wpmotif-element-widgets fullwidth-widget-area footer-widgetarea-bottom">
        <div class="site-wrapper">
            <?php dynamic_sidebar('after-footer-widgetarea'); ?>
        </div>
    </div>
    <?php
    do_action('blogtwist_after_footer_widgets_bottom');
}
?>
