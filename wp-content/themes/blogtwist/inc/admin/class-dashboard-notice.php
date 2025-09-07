<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

class Blogtwist_Welcome_Notice
{
    private $theme_url = 'https://wpmotif.com/theme/blogtwist/';
    private $doc_url = 'https://docs.wpmotif.com/blogtwist/';
    private $theme_name;
    private $theme_version;
    private $theme_slug;
    private $theme_screenshot;

    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'blogtwist_enqueue_scripts'));
        add_action('wp_loaded', [$this, 'show_welcome_notice'], 20);
        add_action('wp_loaded', [$this, 'handle_hide_notice'], 15);
        add_action('wp_ajax_import_button', [$this, 'handle_import_button']);
    }

    /**
     * Localize array for import button AJAX request.
     */
    public function blogtwist_enqueue_scripts()
    {
        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        $file_name = is_rtl() ? 'admin-rtl' . $suffix . '.css' : 'admin' . $suffix . '.css';

        wp_enqueue_style(
            'blogtwist-dashboard-style',
            get_template_directory_uri() . '/inc/admin/assets/css/' . $file_name,
            array(),
            BLOGTWIST_VERSION
        );

        wp_enqueue_script(
            'blogtwist-plugin-install-helper',
            get_template_directory_uri() . '/inc/admin/assets/js/plugin-handle.js',
            array('jquery'),
            BLOGTWIST_VERSION,
            true
        );

        $welcome_data = array(
            'uri' => esc_url(admin_url('/themes.php?page=blogtwist&tab=starter-templates')),
            'btn_text' => esc_html__('Processing...', 'blogtwist'),
            'nonce' => wp_create_nonce('blogtwist_demo_import_nonce'),
            'admin_url' => esc_url(admin_url()),
            'ajaxurl' => admin_url('admin-ajax.php'), // Include this line for using admin-ajax.php
        );
        wp_localize_script('blogtwist-plugin-install-helper', 'ogAdminObject', $welcome_data);

    }

    public function show_welcome_notice()
    {
        if (!get_option('blogtwist_admin_notice_welcome')) {
            add_action('admin_notices', function () {
                $screen = get_current_screen();
                if (in_array($screen->id, ['dashboard', 'plugins', 'themes'], true)) {
                    remove_all_actions('admin_notices');
                    $this->render_welcome_notice();
                }
            });
        }
    }

    private function get_import_button_html()
    {
        return sprintf(
            '<a class="btn-get-started blogtwist-get-started button button-primary button-hero dashboard-button dashboard-button-primary" href="#" data-name="%s" data-slug="%s" aria-label="%s">%s</a>',
            esc_attr('mailchimp-for-wp, contact-form-7, one-click-demo-import'),
            esc_attr('mailchimp-for-wp, contact-form-7, one-click-demo-import'),
            esc_attr__('Start with ', 'blogtwist') . esc_html($this->theme_name),
            esc_html__('Start with ', 'blogtwist') . esc_html($this->theme_name),
        );
    }

    public function render_welcome_notice()
    {
        $dismiss_url = wp_nonce_url(
            remove_query_arg(['activated'], add_query_arg('blogtwist-hide-notice', 'welcome')),
            'blogtwist_hide_notices_nonce',
            '_blogtwist_notice_nonce'
        );
        $current_user = wp_get_current_user();
        $theme = wp_get_theme();
        $this->theme_name = $theme->get('Name');
        $this->theme_version = $theme->get('Version');
        $this->theme_slug = $theme->get_template();

        if (!is_child_theme()) {
            $this->theme_screenshot = get_template_directory_uri() . "/screenshot.png";
        } else {
            $this->theme_screenshot = get_stylesheet_directory_uri() . "/screenshot.png";
        }

        if (is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php') && is_plugin_active('contact-form-7/wp-contact-form-7.php') && is_plugin_active('one-click-demo-import/one-click-demo-import.php')) {
            return;
        }

        ?>
        <div id="blogtwist-welcome-notice" class="blogtwist-welcome-panel updated notice">
            <a class="blogtwist-message-close notice-dismiss"
               href="<?php echo esc_url($dismiss_url); ?>">
                <span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.', 'blogtwist'); ?>
            </a>
            <header class="blogtwist-welcome-header">
                <h2 class="notice-title">
                    <?php
                    printf(
                        esc_html__('🎉 Welcome to %1$s – Thank You for Choosing Us!', 'blogtwist'),
                        esc_html($this->theme_name)
                    );
                    ?>
                </h2>



                <p class="notice-description">
                    <?php
                    // Ensure that your printf calls are properly structured and use the right escaping.
                    printf(
                        '<strong>' . esc_html__(
                            'Hello %1$s!',
                            'blogtwist'
                        ) . '</strong> ' . esc_html__(
                            'Thank you for activating %2$s! We’re thrilled to have you on board and can’t wait to see how you bring your website to life with our theme.',
                            'blogtwist'
                        ),
                        esc_html($current_user->user_login),
                        esc_html($this->theme_name)
                    );
                    ?>
                </p>
            </header><!-- .blogtwist-welcome-header -->
            <div class="blogtwist-welcome-content">

                <div class="blogtwist-welcome-column welcome-column-screenshot">
                    <img src="<?php echo esc_url($this->theme_screenshot); ?>" width="300" height="225">
                </div>

                <div class="blogtwist-welcome-column welcome-column-general">
                    <div class="general-info-links">

                            <div class="blogtwist-notice-item blogtwist-notice-template-import">
                                <h2 class="blogtwist-info-title">
                                    <span class="dashicons dashicons-admin-plugins"></span><?php esc_html_e( 'Recommended Plugins', 'blogtwist' ); ?>
                                </h2>
                                <p><?php esc_html_e('Begin by installing and activating the One Click Demo Import plugin and MC4WP: Mailchimp for WordPress.', 'blogtwist'); ?></p>

                            </div>
                            <div class="button-group">
                                <?php echo $this->get_import_button_html(); ?>
                                <a class="button button-hero" href="<?php echo esc_url(wp_customize_url()); ?>">
                                    <?php esc_html_e('Customize Your Website', 'blogtwist'); ?>
                                </a>
                            </div>

                    </div><!-- .general-info-links -->
                </div>

                <div class="blogtwist-welcome-column welcome-column-resources">
                    <div class="resource-info-wrap">
                        <div class="support-wrap">
                            <h2 class="blogtwist-info-title">
                                <span class="dashicons dashicons-superhero"></span><?php esc_html_e('Need Help?', 'blogtwist'); ?>
                            </h2>
                            <p>
                            <?php printf(
                                wp_kses_post(
                                    'Visit our %1$s for assistance and answers to your questions.',
                                    'blogtwist'
                                ),
                                '<a target="_blank" rel="external noopener noreferrer" href="https://support.wpmotif.com/open-support-ticket/"><span class="screen-reader-text"><?php esc_html_e("opens in a new tab", "blogtwist"); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" viewBox="0 0 512 512" width="12" height="12" style="margin-right: 5px;">
                                        <path fill="currentColor" d="M432 320H400a16 16 0 0 0-16 16V448H64V128H208a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16H48A48 48 0 0 0 0 112V464a48 48 0 0 0 48 48H400a48 48 0 0 0 48-48V336A16 16 0 0 0 432 320ZM488 0h-128c-21.4 0-32 25.9-17 41l35.7 35.7L135 320.4a24 24 0 0 0 0 34L157.7 377a24 24 0 0 0 34 0L435.3 133.3 471 169c15 15 41 4.5 41-17V24A24 24 0 0 0 488 0Z"></path>
                                    </svg>'
                                . esc_html__('Help and Support Center', 'blogtwist')
                                . '</a>'
                            ); ?>
                            </p>
                        </div><!-- .support-wrap -->

                        <div class="document-wrap">
                            <h2 class="blogtwist-info-title">
                                <span class="dashicons dashicons-editor-help"></span><?php esc_html_e('Documentation', 'blogtwist'); ?>
                            </h2>
                            <p>
                                <?php
                                printf(wp_kses_post('Need detailed information? Here’s the full guide on using<b> %1$s </b>and its features to clear any doubts.', 'blogtwist'), $this->theme_name);
                                ?>
                                <a target="_blank" rel="external noopener noreferrer"
                                   href="https://support.wpmotif.com/docs/blogtwist/"><span
                                            class="screen-reader-text"><?php esc_html_e('opens in a new tab', 'blogtwist'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" focusable="false" role="img"
                                         viewBox="0 0 512 512" width="12" height="12" style="margin-right: 5px;">
                                        <path fill="currentColor"
                                              d="M432 320H400a16 16 0 0 0-16 16V448H64V128H208a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16H48A48 48 0 0 0 0 112V464a48 48 0 0 0 48 48H400a48 48 0 0 0 48-48V336A16 16 0 0 0 432 320ZM488 0h-128c-21.4 0-32 25.9-17 41l35.7 35.7L135 320.4a24 24 0 0 0 0 34L157.7 377a24 24 0 0 0 34 0L435.3 133.3 471 169c15 15 41 4.5 41-17V24A24 24 0 0 0 488 0Z"></path>
                                    </svg><?php esc_html_e('Read full documentation', 'blogtwist'); ?></a>
                            </p>
                        </div><!-- .document-wrap -->
                    </div><!-- .resource-info-wrap -->
                </div>

            </div><!-- .blogtwist-welcome-content -->
        </div><!-- .blogtwist-welcome-panel -->
        <?php
    }

    public function handle_hide_notice()
    {
        remove_all_actions('admin_notices');
        if (isset($_GET['blogtwist-hide-notice']) && isset($_GET['_blogtwist_notice_nonce'])) {
            if (!wp_verify_nonce(wp_unslash($_GET['_blogtwist_notice_nonce']), 'blogtwist_hide_notices_nonce')) {
                wp_die(__('Action failed. Please refresh the page and retry.', 'blogtwist'));
            }
            if (!current_user_can('manage_options')) {
                wp_die(__('Cheatin&#8217; huh?', 'blogtwist'));
            }
            $hide_notice = sanitize_text_field(wp_unslash($_GET['blogtwist-hide-notice']));
            update_option('blogtwist_admin_notice_' . $hide_notice, 1);
        }
    }

    public function handle_import_button()
    {
        check_ajax_referer('blogtwist_demo_import_nonce', 'security');
        $state = [];
        if (class_exists('OCDI_Plugin')) {
            $state['one-click-demo-import'] = 'activated';
        } elseif (file_exists(WP_PLUGIN_DIR . '/one-click-demo-import/one-click-demo-import.php')) {
            $state['one-click-demo-import'] = 'installed';
        }
        if (class_exists('MC4WP_MailChimp')) {
            $state['mailchimp-for-wp'] = 'activated';
        } elseif (file_exists(WP_PLUGIN_DIR . '/mailchimp-for-wp/mailchimp-for-wp.php')) {
            $state['mailchimp-for-wp'] = 'installed';
        }
        if (class_exists('WPCF7')) {
            $state['contact-form-7'] = 'activated';
        } elseif (file_exists(WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php')) {
            $state['contact-form-7'] = 'installed';
        }

        $response = ['redirect' => admin_url('/themes.php?page=blogtwist-dashboard&tab=dashboard')];

        foreach (['mailchimp-for-wp','contact-form-7' ,'one-click-demo-import'] as $plugin) {
            if (isset($state[$plugin]) && ('activated' === $state[$plugin] || 'installed' === $state[$plugin])) {
                if (current_user_can('activate_plugin')) {
                    $result = activate_plugin($plugin . '/' . $plugin . '.php');
                    if (is_wp_error($result)) {
                        $response['errorCode'] = $result->get_error_code();
                        $response['errorMessage'] = $result->get_error_message();
                    }
                }
            } else {
                wp_enqueue_style('plugin-install');
                wp_enqueue_script('plugin-install');
                include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

                $api = plugins_api('plugin_information', ['slug' => $plugin, 'fields' => ['sections' => false]]);
                $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
                $result = $upgrader->install($api->download_link);

                if ($result && current_user_can('activate_plugin')) {
                    $result = activate_plugin($plugin . '/' . $plugin . '.php');
                    if (is_wp_error($result)) {
                        $response['errorCode'] = $result->get_error_code();
                        $response['errorMessage'] = $result->get_error_message();
                    }
                }
            }
        }

        wp_send_json($response);
    }
}

new Blogtwist_Welcome_Notice();