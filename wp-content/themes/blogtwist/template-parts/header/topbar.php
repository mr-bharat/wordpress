<?php
/**
 * Displays TopBar
 *
 * @package BlogTwist 1.0.0
 */
?>
<?php do_action('blogtwist_before_topbar'); ?>
    <div class="site-topbar">
        <div class="site-wrapper header-wrapper">
            <div class="header-components header-components-left">
                <?php $blogtwist_enable_mobile_menu = get_theme_mod('blogtwist_enable_mobile_menu', false);?>
                <button class="toggle nav-toggle <?php if ($blogtwist_enable_mobile_menu != 1){ echo 'hide-on-desktop'; } ?>" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
                    <span class="toggle-text screen-reader-text"><?php _e('Menu', 'blogtwist'); ?></span>
                    <span class="wpmotif-menu-icon">
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <?php
                $enable_time = get_theme_mod('blogtwist_enable_header_time', true);
                $enable_date = get_theme_mod('blogtwist_enable_header_date', true);
                $date_label = get_theme_mod('blogtwist_date_label_text', '');
                $date_format = get_theme_mod('blogtwist_header_date_format', 'F j, Y');

                if ($enable_date) :
                    ?>
                    <div class="site-topbar-component topbar-component-date hide-on-mobile">

                            <span><?php blogtwist_the_theme_svg('calendar'); ?>  <?php if ($date_label) : ?><?php echo esc_html($date_label); ?><?php endif; ?></span>

                        <span><?php echo date_i18n(esc_html($date_format), current_time('timestamp')); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($enable_time) : ?>
                    <div class="site-topbar-component topbar-component-clock hide-on-mobile">
                        <span><?php blogtwist_the_theme_svg('clock'); ?></span>
                        <span class="wpmotif-display-clock"></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="header-components header-components-right">
                <?php if (has_nav_menu('social')) : ?>
                    <nav aria-label="<?php esc_attr_e('Social links', 'blogtwist'); ?>">
                        <ul class="social-menu reset-list-style social-icons">
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'social',
                                'container'      => '',
                                'items_wrap'     => '%3$s',
                                'depth'          => 1,
                                'link_before'    => '<span class="social-menu-text hide-on-mobile hide-on-tablet">',
                                'link_after'     => '</span>',
                            ]);
                            ?>
                        </ul>
                    </nav>
                <?php endif; ?>
                <button class="toggle search-toggle desktop-search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
                    <span class="toggle-text screen-reader-text"><?php _e('Search', 'blogtwist'); ?></span>
                    <?php blogtwist_the_theme_svg('search'); ?>
                </button>
            </div>
        </div>
    </div>
<?php do_action('blogtwist_after_topbar'); ?>