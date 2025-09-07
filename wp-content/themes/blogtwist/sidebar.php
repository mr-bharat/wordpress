<?php
/**
 * The sidebar containing the main widget area.
 * @package BlogTwist 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! is_active_sidebar( 'sidebar' ) ) {
	return;
} 
$page_layout = blogtwist_get_page_layout();

if ($page_layout == 'no_sidebar') {
	return;
}


?>

<div id="secondary" class="sidebar wpmotif-element-widgets regular-widget-area" role="complementary">
    <div class="site-sticky-components">
        <?php dynamic_sidebar('sidebar'); ?>
    </div>
</div><!-- #secondary -->
