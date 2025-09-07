<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
</div><!-- #content -->

<?php
$blogtwist_enable_footer_latest_on_homepage = get_theme_mod('blogtwist_enable_footer_latest_on_homepage', false);
if ($blogtwist_enable_footer_latest_on_homepage == 1) {
    if (!is_paged() && is_front_page()) {
        get_template_part('template-parts/components/footer-recommended');
    }
} else {
    get_template_part('template-parts/components/footer-recommended');
}
?>

<?php get_template_part('template-parts/widgetarea/widgetarea-before-footer'); ?>

<footer id="colophon" class="site-footer" role="contentinfo">

    <div class="footer-animation-overlay">
        <div class="animation-overlay-wrapper">
            <div class="animation-overlay-fade"></div>
            <div class="animation-overlay-patterns"></div>
        </div>
    </div>
    <div class="site-footer-content">
        <?php get_template_part('template-parts/widgetarea/widgetarea-footer'); ?>
        <?php do_action('blogtwist_before_footer_credit'); ?>
        <div class="footer-site-credit">
            <div class="site-wrapper">

                <div class="site-branding">
                    <div class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <span class="hide-on-desktop"><?php bloginfo( 'name' ); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="site-branding-svg hide-on-mobile hide-on-tablet">
                                <text x="50%" y="0.82em" stroke="var(--theme-background-color)" fill="currentColor" text-anchor="middle" stroke-width="<?php echo esc_attr( get_theme_mod( 'blogtwist_site_title_outline', '3' ) ); ?>">
                                    <?php bloginfo( 'name' ); ?>
                                </text>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'container' => false, // Avoid unnecessary div wrapping
                            'depth' => 1,
                            'menu_class' => 'footer-menu reset-list-style',
                        ]);
                        ?>
                    </nav>
                <?php endif; ?>

                <div class="site-info">
                    <?php
                    /* translators: %s: WordPress */
                    printf(
                        esc_html__('Proudly powered by %s', 'blogtwist'),
                        '<a href="' . esc_url(__('https://wordpress.org/', 'blogtwist')) . '">WordPress</a>.'
                    );
                    ?>
                    <?php
                    /* translators: %1$s: The theme name, %2$s: The theme author name. */
                    printf(
                        esc_html__('Theme: %1$s by %2$s.', 'blogtwist'),
                        'BlogTwist',
                        '<a href="https://wpmotif.com/" title="' . esc_html__('Modern WordPress Themes by WPMotif - Free & Premium Designs', 'blogtwist') . '">WPMotif</a>'
                    );
                    ?>
                </div>

                <button id="scrollToTop">
                    <?php blogtwist_the_theme_svg('chevron-up'); ?>
                </button>

            </div>
        </div>
        <?php do_action('blogtwist_after_footer_credit'); ?>
    </div>
</footer><!-- #colophon -->

<?php get_template_part('template-parts/widgetarea/widgetarea-after-footer'); ?>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
