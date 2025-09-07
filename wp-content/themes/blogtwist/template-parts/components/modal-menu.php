<?php
/**
 * Displays the menu icon and modal
 *
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$blogtwist_enable_mobile_menu = get_theme_mod('blogtwist_enable_mobile_menu', false);
?>
<?php do_action('blogtwist_before_model_menu'); ?>
<div class="menu-modal cover-modal" data-modal-target-string=".menu-modal">
    <div class="menu-modal-inner modal-inner">
        <div class="menu-wrapper">
            <div class="menu-top">
                <button class="toggle close-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" data-set-focus=".menu-modal">
                    <?php blogtwist_the_theme_svg('cross'); ?>
                </button>
                <nav class="mobile-menu"  aria-label="<?php echo esc_attr_x('Mobile', 'menu', 'blogtwist'); ?>">
                    <ul class="modal-menu reset-list-style">
                        <?php
                        if (has_nav_menu('primary')) {
                            wp_nav_menu([
                                'container'      => '',
                                'items_wrap'     => '%3$s',
                                'show_toggles'   => true,
                                'theme_location' => 'primary',
                            ]);
                        } else {
                            wp_list_pages([
                                'match_menu_classes' => true,
                                'show_toggles'       => true,
                                'title_li'           => false,
                                'walker'             => new BlogTwist_Walker_Page(),
                            ]);
                        }
                        ?>
                    </ul>
                </nav>
            </div>

            <div class="menu-bottom">
                <?php if (has_nav_menu('social')) : ?>
                    <nav aria-label="<?php esc_attr_e('Expanded Social links', 'blogtwist'); ?>">
                        <ul class="social-menu reset-list-style social-icons">
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'social',
                                'container'      => '',
                                'items_wrap'     => '%3$s',
                                'depth'          => 1,
                                'link_before'    => '<span class="screen-reader-text">',
                                'link_after'     => '</span>',
                            ]);
                            ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php do_action('blogtwist_after_model_menu'); ?>