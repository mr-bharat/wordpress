<?php
/**
 * Blogtwist Dashboard
 *
 * @package Blogtwist
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Blogtwist_Admin_Dashboard')) :
    /**
     * Class Blogtwist_Admin_Main
     */
    class Blogtwist_Admin_Dashboard
    {
        public $theme_name;
        public $theme_slug;
        public $theme_author_uri;
        public $theme_author_name;
        public $free_plugins;
        /**
         * Blogtwist_Admin_Dashboard constructor.
         */
        public function __construct()
        {
            global $admin_main_class;
            add_action('admin_menu', array($this, 'blogtwist_admin_menu'));
            //theme details
            $theme = wp_get_theme();
            $this->theme_name = $theme->get('Name');
            $this->theme_slug = $theme->get('TextDomain');
            $this->theme_author_uri = $theme->get('AuthorURI');
            $this->theme_author_name = $theme->get('Author');
        }
        /**
         * Add admin menu.
         */
        public function blogtwist_admin_menu()
        {
            add_theme_page(sprintf(esc_html__('%1$s Dashboard', 'blogtwist'), $this->theme_name), sprintf(esc_html__('%1$s Dashboard', 'blogtwist'), $this->theme_name), 'edit_theme_options', 'blogtwist-dashboard', array($this, 'blogtwist_get_started_screen'));
        }
        public function blogtwist_get_started_screen()
        {
            $current_tab = empty($_GET['tab']) ? 'blogtwist_welcome' : sanitize_title($_GET['tab']);
            // Look for a {$current_tab}_screen method.
            if (is_callable(array($this, $current_tab . '_screen'))) {
                return $this->{$current_tab . '_screen'}();
            }
            // Fallback to about screen.
            return $this->blogtwist_welcome_screen();
        }
        /**
         * Dashboard header
         *
         * @access private
         */
        private function blogtwist_dashboard_header()
        {
            $theme = wp_get_theme(get_template());
            ?>
            <div class="dashboard-header">
                <div class="blogtwist-container">
                    <div class="header-top">
                        <h1 class="heading"><?php printf(esc_html__('%1$s Dashboard', 'blogtwist'), $this->theme_name); ?></h1>
                        <span class="theme-version"><?php printf(esc_html__('Version: %1$s', 'blogtwist'), $theme->get('Version')); ?></span>
                        <span class="author-link"><?php printf(wp_kses_post('By <a href="%1$s" target="_blank">%2$s</a>', 'blogtwist'), $this->theme_author_uri, $this->theme_author_name); ?></span>
                    </div><!-- .header-top -->
                    <div class="header-nav">
                        <nav class="dashboard-nav">
                            <li>
                                <a class="nav-tab <?php if (empty($_GET['tab']) && $_GET['page'] == 'blogtwist-dashboard') echo 'active'; ?>" href="<?php echo esc_url(admin_url(add_query_arg(array('page' => 'blogtwist-dashboard'), 'themes.php'))); ?>">
                                    <span class="dashicons dashicons-admin-appearance"></span>
                                    <span class="nav-tab-label"><?php esc_html_e('Welcome', 'blogtwist'); ?></span>
                                </a>
                            </li>
                            <li>
                                <a class="nav-tab <?php if (isset($_GET['tab']) && $_GET['tab'] == 'blogtwist_plugin') echo 'active'; ?>" href="<?php echo esc_url(admin_url(add_query_arg(array('page' => 'blogtwist-dashboard', 'tab' => 'blogtwist_plugin'), 'themes.php'))); ?>">
                                    <span class="dashicons dashicons-admin-plugins"></span>
                                    <span class="nav-tab-label"><?php esc_html_e('Plugin', 'blogtwist'); ?></span>
                                </a>
                            </li>
                            <li>
                                <a class="nav-tab <?php if (isset($_GET['tab']) && $_GET['tab'] == 'blogtwist_free_pro') echo 'active'; ?>" href="<?php echo esc_url(admin_url(add_query_arg(array('page' => 'blogtwist-dashboard', 'tab' => 'blogtwist_free_pro'), 'themes.php'))); ?>">
                                    <span class="dashicons dashicons-dashboard"></span>
                                    <span class="nav-tab-label"><?php esc_html_e('Free Vs Pro', 'blogtwist'); ?></span>
                                </a>
                            </li>
                            <li>
                                <a class="nav-tab <?php if (isset($_GET['tab']) && $_GET['tab'] == 'blogtwist_changelog') echo 'active'; ?>" href="<?php echo esc_url(admin_url(add_query_arg(array('page' => 'blogtwist-dashboard', 'tab' => 'blogtwist_changelog'), 'themes.php'))); ?>">
                                    <span class="dashicons dashicons-flag"></span>
                                    <span class="nav-tab-label"><?php esc_html_e('Changelog', 'blogtwist'); ?></span>
                                </a>
                            </li>
                        </nav>
                        <div class="upgrade-pro">
                            <a href="<?php echo esc_url('https://wpmotif.com/theme/blogtwist/#product-pricing'); ?>"
                               target="_blank"
                               class="button button-primary button-dashboard button-primary-dashboard"><?php esc_html_e('Unlock More Features with Pro', 'blogtwist'); ?></a>
                        </div><!-- .upgrade-pro -->
                    </div><!-- .header-nav -->
                </div><!-- .blogtwist-container -->
            </div><!-- .dashboard-header -->
            <?php
        }
        /**
         * Dashboard sidebar
         *
         * @access private
         */
        private function blogtwist_dashboard_sidebar()
        {
            $review_url = 'https://wordpress.org/support/theme/' . $this->theme_slug . '/reviews/?filter=5#new-post';
            $hosting_insight_url = 'https://wpmotif.com/hosting/';
            ?>
            <div class="sidebar-wrapper">
                <div class="admin-panel-area sidebar-block ask-review">
                    <div class="block-header">
                        <img class="block-icon" width="35" height="35"
                             src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/assets/img/feedback.svg'); ?>"
                             alt="icon">
                        <h3 class="block-title"><?php esc_html_e('Share Your Experience', 'blogtwist'); ?></h3>
                    </div>
                    <p>
                        <?php
                        printf(
                            wp_kses(
                                __('Are you enjoying <b>%1$s</b>? Your feedback helps us improve and inspires others to join!', 'blogtwist'),
                                array('b' => array())
                            ),
                            esc_html($this->theme_name)
                        );
                        ?>
                    </p>
                    <a class="button button-primary button-dashboard button-secondary-dashboard"
                       href="<?php echo esc_url($review_url); ?>"
                       target="_blank"
                       rel="external noopener noreferrer">
                        <?php esc_html_e('Leave a Review', 'blogtwist'); ?>
                        <span class="dashicons dashicons-arrow-right-alt"></span>
                    </a>
                </div>

                <div class="admin-panel-area sidebar-block insights-1">
                    <div class="block-header">
                        <img class="block-icon" width="35" height="35"
                             src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/assets/img/hosting.svg'); ?>"
                             alt="icon">
                        <h3 class="block-title"><?php esc_html_e('WordPress Hosting', 'blogtwist'); ?></h3>
                    </div>

                            <p><?php esc_html_e('A slow host can really put a drag on your site’s performance, even an optimized one. Make sure you get a host that won’t hold you back by reading our review, complete with real speed tests, of the fastest WordPress hosts.', 'blogtwist'); ?></p>
                            <a class="button button-primary button-dashboard button-secondary-dashboard" href="<?php echo esc_url($hosting_insight_url); ?>" target="_blank">
                                <?php esc_html_e('Read More', 'blogtwist'); ?>
                                <span class="dashicons dashicons-arrow-right-alt"></span>
                            </a>
                </div>
            </div><!-- .sidebar-wrapper -->
            <?php
        }
        /**
         * render the welcome screen.
         */
        public function blogtwist_welcome_screen()
        {
            $doc_url = 'https://support.wpmotif.com/docs/blogtwist/';
            $support_url = 'https://wordpress.org/support/theme/' . $this->theme_slug;
            ?>
            <div id="blogtwist-dashboard">
                <?php $this->blogtwist_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="blogtwist-container">
                        <div class="main-content welcome-content-wrapper">
                            <div class="admin-panel-area welcome-block quick-links">
                                <div class="block-header">
                                    <img class="block-icon" width="25" height="25"
                                         src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/assets/img/quick-link.svg'); ?>"
                                         alt="icon">
                                    <h3 class="block-title"><?php esc_html_e('Customizer quick link', 'blogtwist'); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content">
                                    <ul class="reset-list-style">
                                        <li>
                                            <a href="<?php echo esc_url(admin_url('customize.php') . '?autofocus[section]=title_tagline'); ?>"
                                               target="_blank" class="welcome-icon"><span
                                                        class="dashicons dashicons-visibility"></span><?php esc_html_e('Manage Site Identity', 'blogtwist'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url(admin_url('customize.php') . '?autofocus[section]=blogtwist_banner_section'); ?>"
                                               target="_blank" class="welcome-icon"><span
                                                        class="dashicons dashicons-admin-page"></span><?php esc_html_e('Homepage Banner', 'blogtwist'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url(admin_url('customize.php') . '?autofocus[section]=blogtwist_header_section'); ?>"
                                               target="_blank" class="welcome-icon"><span
                                                        class="dashicons dashicons-editor-kitchensink"></span><?php esc_html_e('Manage Header', 'blogtwist'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url(admin_url('customize.php') . '?autofocus[section]=blogtwist_section_social_icons'); ?>"
                                               target="_blank" class="welcome-icon"> <span
                                                        class="dashicons dashicons-networking"> </span><?php esc_html_e('Social Icons', 'blogtwist'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                    <ul class="reset-list-style">
                                        <li>
                                            <a href="<?php echo esc_url(admin_url('customize.php') . '?autofocus[panel]=blogtwist_footer_panel'); ?>"
                                               target="_blank" class="welcome-icon"> <span
                                                        class="dashicons dashicons-slides"> </span> <?php esc_html_e('Footer Setting', 'blogtwist'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" target="_blank"
                                               class="welcome-icon"> <span
                                                        class="dashicons dashicons-menu-alt"> </span> <?php esc_html_e('Manage Menus', 'blogtwist'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url(admin_url('widgets.php')); ?>" target="_blank"
                                               class="welcome-icon"> <span
                                                        class="dashicons dashicons-menu-alt"> </span> <?php esc_html_e('Manage Widgets', 'blogtwist'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block.quick-links -->
                            <div class="admin-panel-area welcome-block documentation">
                                <div class="block-header">
                                    <img class="block-icon" width="35" height="35"
                                         src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/assets/img/docs.svg'); ?>"
                                         alt="icon">
                                    <h3 class="block-title"><?php esc_html_e('Theme Documentation', 'blogtwist'); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content">
                                    <p>
                                        <?php printf(wp_kses_post('Need more details? Please check our full documentation for detailed information on how to use <b>%1$s</b>.', 'blogtwist'), $this->theme_name); ?>
                                    </p>
                                    <a href="<?php echo esc_url($doc_url); ?>"
                                       target="_blank"><?php esc_html_e('Go to doc', 'blogtwist'); ?><span
                                                class="dashicons dashicons-arrow-right-alt"></span></a>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block documentation -->
                            <div class="admin-panel-area welcome-block support">
                                <div class="block-header">
                                    <img class="block-icon" width="35" height="35"
                                         src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/assets/img/support.svg'); ?>"
                                         alt="icon">
                                    <h3 class="block-title"><?php esc_html_e('Contact Support', 'blogtwist'); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content">
                                    <p>
                                        <?php printf(wp_kses_post('Our goal is to ensure you have the best experience with <b>%1$s</b>, which is why we’ve compiled all the essential information here for you. We hope you enjoy using <b>%1$s</b> as much as we enjoy crafting exceptional products.', 'blogtwist'), $this->theme_name); ?>
                                    </p>
                                    <a href="<?php echo esc_url($support_url); ?>"
                                       target="_blank"><?php esc_html_e('Contact Support', 'blogtwist'); ?><span
                                                class="dashicons dashicons-arrow-right-alt"></span></a>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block support -->
                            <div class="admin-panel-area welcome-block tutorial">
                                <div class="block-header">
                                    <img class="block-icon" width="35" height="35"
                                         src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/assets/img/tutorial.svg'); ?>"
                                         alt="icon">
                                    <h3 class="block-title"><?php esc_html_e('Tutorial', 'blogtwist'); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content">
                                    <p>
                                        <?php printf(wp_kses_post('This tutorial is designed for individuals with a basic understanding of HTML and CSS who are eager to learn website development. By the end of this tutorial, you will achieve a moderate level of expertise in creating websites or blogs using WordPress.', 'blogtwist'), $this->theme_name); ?>
                                    </p>
                                    <a href="<?php echo esc_url('https://support.wpmotif.com/'); ?>"
                                       target="_blank"><?php esc_html_e('WP Tutorials', 'blogtwist'); ?><span
                                                class="dashicons dashicons-arrow-right-alt"></span></a>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block tutorial -->
                            <div class="return-to-dashboard">
                                <?php if (current_user_can('update_core') && isset($_GET['updated'])) : ?>
                                    <a href="<?php echo esc_url(self_admin_url('update-core.php')); ?>">
                                        <?php is_multisite() ? esc_html_e('Return to Updates', 'blogtwist') : esc_html_e('Return to Dashboard &rarr; Updates', 'blogtwist'); ?>
                                    </a> |
                                <?php endif; ?>
                                <a href="<?php echo esc_url(self_admin_url()); ?>"><?php is_blog_admin() ? esc_html_e('Go to Dashboard &rarr; Home', 'blogtwist') : esc_html_e('Go to Dashboard', 'blogtwist'); ?></a>
                            </div><!-- .return-to-dashboard -->
                        </div><!-- .welcome-content-wrapper -->
                        <?php $this->blogtwist_dashboard_sidebar(); ?>
                    </div><!-- .blogtwist-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #blogtwist-dashboard -->
            <?php
        }
        /**
         * render the free vs pro screen
         */
        public function blogtwist_free_pro_screen()
        {
            ?>
            <div id="blogtwist-dashboard">
                <?php $this->blogtwist_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="blogtwist-container">
                        <div class="main-content free-pro-content-wrapper">
                            <table class="compare-table">
                                <thead>
                                <tr>
                                    <th class="table-feature-title"><h3><?php esc_html_e('Features', 'blogtwist'); ?></h3>
                                    </th>
                                    <th><h3><?php echo esc_html($this->theme_name); ?></h3></th>
                                    <th><h3><?php esc_html_e('BlogTwist Pro', 'blogtwist'); ?></h3></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><h3><?php esc_html_e('Price', 'blogtwist'); ?></h3></td>
                                    <td><?php esc_html_e('Free', 'blogtwist'); ?></td>
                                    <td><?php esc_html_e('$59', 'blogtwist'); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Import Demo Data', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Multiple Layouts', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('WooCommerce Plugin Compatible', 'blogtwist'); ?></h3></td>
                                    <td><?php esc_html_e('Basic', 'blogtwist'); ?></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Pre Loader Layouts', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Header Layouts', 'blogtwist'); ?></h3></td>
                                    <td><?php esc_html_e('1', 'blogtwist'); ?></td>
                                    <td><?php esc_html_e('3', 'blogtwist'); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Google Fonts', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><?php esc_html_e('1000+', 'blogtwist'); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Typography Options', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Dark Mode', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Article Ticker', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Footer Background Option', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Banner Option', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Slider Layouts', 'blogtwist'); ?></h3></td>
                                    <td><?php esc_html_e('1', 'blogtwist'); ?></td>
                                    <td><?php esc_html_e('3', 'blogtwist'); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Footer Widget Area', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Social Share Icons', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Newsletter Popup Model Box', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('WordPress Page Builder Compatible', 'blogtwist'); ?></h3>
                                    </td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('GDPR Compatible', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Sticky Sidebar', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Author Info Area', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Fixed Next/Prev Posts', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e('Remove Copyright Theme Credit', 'blogtwist'); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e('Get access to all Pro features and power-up your website', 'blogtwist'); ?></td>
                                    <td></td>
                                    <td class="btn-wrapper">
                                        <a href="<?php echo esc_url(apply_filters('blogtwist_pro_theme_url', 'https://wpmotif.com/theme/blogtwist/#product-pricing')); ?>" class="button button-primary button-dashboard button-primary-dashboard" target="_blank">
                                            <?php esc_html_e('Buy Pro', 'blogtwist'); ?>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- .free-pro-content-wrapper -->
                        <?php $this->blogtwist_dashboard_sidebar(); ?>
                    </div><!-- .blogtwist-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #blogtwist-dashboard -->
            <?php
        }
        /**
         * render the changelog screen
         */
        public function blogtwist_changelog_screen()
        {
            global $admin_main_class;
            if (!is_child_theme()) {
                $changelogFilePath = get_template_directory() . '/changelog.txt';
            } else {
                $changelogFilePath = get_stylesheet_directory() . '/changelog.txt';
            }
            $get_changelog = $admin_main_class->get_changelog($changelogFilePath);
            ?>
            <div id="blogtwist-dashboard">
                <?php $this->blogtwist_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="changelog-top-wrapper">
                        <ul class="blogtwist-container">
                            <li>
                                <span class="new"><?php esc_html_e('N', 'blogtwist'); ?></span>
                                <?php esc_html_e('New', 'blogtwist'); ?>
                            </li>
                            <li>
                                <span class="improvement"><?php esc_html_e('I', 'blogtwist'); ?></span>
                                <?php esc_html_e('Improvement', 'blogtwist'); ?>
                            </li>
                            <li>
                                <span class="fixed"><?php esc_html_e('F', 'blogtwist'); ?></span>
                                <?php esc_html_e('Fixed', 'blogtwist'); ?>
                            </li>
                            <li>
                                <span class="tweak"><?php esc_html_e('T', 'blogtwist'); ?></span>
                                <?php esc_html_e('Tweak', 'blogtwist'); ?>
                            </li>
                        </ul>
                    </div><!-- .changelog-top-wrapper -->
                    <div class="blogtwist-container">
                        <div class="changelog-content-wrapper">
                            <?php
                            foreach ($get_changelog as $log) {
                                $version = isset($log['version']) ? esc_html($log['version']) : __('N/A', 'blogtwist');
                                $date = isset($log['date']) ? esc_html($log['date']) : __('Unknown Date', 'blogtwist');
                                ?>
                                <section class="changelog-block">
                                    <div class="block-top">
                                        <span class="block-version"><?php printf(esc_html__('Version: %1$s', 'blogtwist'), $log['version']); ?></span>
                                        <span class="block-date"><?php printf(esc_html__('Released on: %1$s', 'blogtwist'), $log['date']); ?></span>
                                    </div><!-- .block-top -->
                                    <div class="block-content">
                                        <ul>
                                            <?php
                                            // loop for new
                                            if (!empty($log['new'])) {
                                                foreach ($log['new'] as $note) {
                                                    echo '<li><span class="new" title="New">N</span>' . esc_html($note) . '</li>';
                                                }
                                            }
                                            // loop for improvement
                                            if (!empty($log['imp'])) {
                                                foreach ($log['imp'] as $note) {
                                                    echo '<li><span class="improvement" title="Improvement">I</span>' . esc_html($note) . '</li>';
                                                }
                                            }
                                            // loop for fixed
                                            if (!empty($log['fix'])) {
                                                foreach ($log['fix'] as $note) {
                                                    echo '<li><span class="fixed" title="Fixed">F</span>' . esc_html($note) . '</li>';
                                                }
                                            }
                                            // loop for tweak
                                            if (!empty($log['tweak'])) {
                                                foreach ($log['tweak'] as $note) {
                                                    echo '<li><span class="tweak" title="Tweak">T</span>' . esc_html($note) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div><!-- .block-content -->
                                </section><!-- .changelog-block -->
                                <?php
                            }
                            ?>
                        </div><!-- .changelog-content-wrapper -->
                        <?php $this->blogtwist_dashboard_sidebar(); ?>
                    </div><!-- .blogtwist-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #blogtwist-dashboard -->
            <?php
        }
        /**
         * render the plugin screen
         */
        public function blogtwist_plugin_screen()
        {
            global $admin_main_class;
            ?>
            <div id="blogtwist-dashboard">
                <?php $this->blogtwist_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="blogtwist-container">
                        <div class="plugin-content-wrapper">
                            <div class="header-content">
                                <h3><?php esc_html_e('Recommended Free Plugins', 'blogtwist'); ?></h3>
                                <p><?php esc_html_e('These Free Plugins might be handy for you.', 'blogtwist'); ?></p>
                            </div><!-- .header-content -->
                            <div class="plugin-listing">

                                    <?php
                                    if (!function_exists('plugins_api')) {
                                        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
                                    }
                                    $plugins = array(
                                       array(
                                            'slug' => 'contact-form-7',
                                            'file' => 'contact-form-7/wp-contact-form-7.php',
                                        ),
                                        array(
                                            'slug' => 'one-click-demo-import',
                                            'file' => 'one-click-demo-import/one-click-demo-import.php',
                                        ),
                                    );

                                    // Loop through the plugins
                                    foreach ($plugins as $plugin) {
                                        $plugin_slug = $plugin['slug'];
                                        $plugin_file = $plugin['file'];

                                        // Check internet connection
                                        $internet_connection = wp_remote_get('https://www.google.com', array('timeout' => 5));
                                        if (is_wp_error($internet_connection)) {
                                            echo '<p>' . esc_html__('No internet connection. Cannot fetch plugin information.', 'blogtwist') . '</p>';
                                            break; // Exit loop if no internet
                                        }
                                        // Fetch plugin information from WordPress.org Plugin API
                                        $args = array(
                                            'slug'   => $plugin_slug,
                                            'fields' => array(
                                                'short_description' => true,
                                                'icons'             => true,
                                            ),
                                        );

                                        $plugin_info = plugins_api('plugin_information', $args);

                                        // Check for errors in fetching plugin information
                                        if (is_wp_error($plugin_info)) {
                                            echo '<p>' . esc_html__('Error fetching plugin information.', 'blogtwist') . '</p>';
                                            continue;
                                        }
                                        // Extract plugin data
                                        $plugin_name = $plugin_info->name;
                                        $plugin_description = $plugin_info->short_description;
                                        $plugin_author = $plugin_info->author;
                                        $plugin_image = $plugin_info->icons['1x'];

                                        $is_plugin_installed = function_exists('blogtwist_is_plugin_installed') && blogtwist_is_plugin_installed($plugin_file);
                                        $is_plugin_activated = function_exists('is_plugin_active') && is_plugin_active($plugin_file);
                                        ?>

                                        <div class="single-plugin-wrap">
                                            <div class="plugin-thumb-wrap">
                                                <div class="plugin-thumb">
                                                    <img class="<?php echo esc_attr($plugin_slug); ?>-logo" src="<?php echo esc_url($plugin_image); ?>" width="100" height="100" alt="<?php echo esc_attr($plugin_name); ?>">
                                                </div>

                                            </div>
                                            <div class="plugin-content-wrap">
                                                <h2><?php echo esc_html($plugin_name); ?></h2>
                                                <h3><?php echo wp_kses_post($plugin_author); ?></h3>
                                                <p><?php echo esc_html($plugin_description); ?></p>
                                            <?php if ($is_plugin_installed) : ?>
                                                <?php if ($is_plugin_activated) : ?>
                                                    <div class="recommended-plugin-action"><?php esc_html_e('Activated', 'blogtwist'); ?></div>
                                                <?php else : ?>
                                                    <div class="recommended-plugin-action">
                                                        <a href="#" class="activate-plugin"
                                                           data-plugin="<?php echo esc_attr($plugin_file); ?>"
                                                           data-slug="<?php echo esc_attr($plugin_slug); ?>">
                                                            <?php esc_html_e('Activate', 'blogtwist'); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <div class="recommended-plugin-action">
                                                    <a href="#" class="install-plugin"
                                                       data-plugin="<?php echo esc_attr($plugin_file); ?>"
                                                       data-slug="<?php echo esc_attr($plugin_slug); ?>">
                                                        <?php esc_html_e('Install', 'blogtwist'); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    ?>
                                </div>



                        </div><!-- .plugin-content-wrapper -->
                        <?php $this->blogtwist_dashboard_sidebar(); ?>
                    </div><!-- .blogtwist-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #blogtwist-dashboard -->
            <?php
        }
    }
    new Blogtwist_Admin_Dashboard();
endif;
