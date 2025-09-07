<?php
/**
 * The template for the featured image hover on archives.
 * @package BlogTwist 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<a class="hover" href="<?php the_permalink(); ?>" data-cursor-class="cursor-link">
	<span class="hover__bg"></span>

	<div class="flexbox">

			<span class="hover__line  hover__line--top"></span>
			<span class="hover__more"><?php esc_html_e( 'Read More', 'blogtwist' ) ?></span>
			<span class="hover__line  hover__line--bottom"></span>

	</div>
</a>
