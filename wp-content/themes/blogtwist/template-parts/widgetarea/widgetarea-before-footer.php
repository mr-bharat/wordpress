<?php
if (is_active_sidebar('before-footer-widgetarea')) {
    do_action('blogtwist_before_footer_widgets_top');
    ?>
    <div class="wpmotif-element-widgets fullwidth-widget-area footer-widgetarea-top">
        <div class="site-wrapper">
            <?php dynamic_sidebar('before-footer-widgetarea'); ?>
        </div>
    </div>
    <?php do_action('blogtwist_before_footer_widgets_bottom');
} ?>
