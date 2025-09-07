<?php
/**
 * BlogTwist main admin class
 *
 * @package BlogTwist
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('wp_ajax_install_plugin', 'blogtwist_plugin_action_callback');
add_action('wp_ajax_activate_plugin', 'blogtwist_plugin_action_callback');

function blogtwist_plugin_action_callback()
{
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'blogtwist_demo_import_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed.'));
    }
    if (!current_user_can('install_plugins')) {
        wp_send_json_error(array('message' => 'You are not allowed to perform this action.'));
    }

    $plugin = sanitize_text_field($_POST['plugin']);
    $plugin_slug = sanitize_text_field($_POST['slug']);

    if (blogtwist_is_plugin_installed($plugin)) {
        if (is_plugin_active($plugin)) {
            wp_send_json_success(array('message' => 'Plugin is already activated.'));
        } else {
            // Activate the plugin
            $result = activate_plugin($plugin);

            if (is_wp_error($result)) {
                wp_send_json_error(array('message' => 'Error activating the plugin.'));
            } else {
                wp_send_json_success(array('message' => 'Plugin activated successfully!'));
            }
        }
    } else {
        // Install and activate the plugin
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        $plugin_info = plugins_api('plugin_information', array('slug' => $plugin_slug));
        $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
        $result = $upgrader->install($plugin_info->download_link);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => 'Error installing the plugin.'));
        }

        $result = activate_plugin($plugin);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => 'Error activating the plugin.'));
        } else {
            wp_send_json_success(array('message' => 'Plugin installed and activated successfully!'));
        }
    }
}

function blogtwist_is_plugin_installed($plugin_path)
{
    $plugins = get_plugins();
    return isset($plugins[$plugin_path]);
}

if ( ! class_exists( 'Blogtwist_Admin_Dashboard' ) ) :
    
    /**
     * Class Blogtwist_Admin_Main
     */
    class Blogtwist_Admin_Main {

     /**
         * Get the parsed changelog.
         *
         * @param string $changelog_path the changelog path.
         *
         * @return array
         */
        public function get_changelog( $changelog_path ) {

            if ( ! is_file( $changelog_path ) ) {
                return [];
            }

            if ( ! WP_Filesystem() ) {
                return [];
            }

            return $this->parse_changelog( $changelog_path );
        }

        /**
         * Return the releases changes array.
         *
         * @param string $changelog_path the changelog path.
         *
         * @return array $releases - changelog.
         */
        private function parse_changelog( $changelog_path ) {
            WP_Filesystem();
            global $wp_filesystem;
            $changelog = $wp_filesystem->get_contents( $changelog_path );
            if ( is_wp_error( $changelog ) ) {
                $changelog = '';
            }
            $changelog     = explode( PHP_EOL, $changelog );
            $releases      = [];
            $release_count = 0;   

            foreach ( $changelog as $changelog_line ) {
                
                if ( empty( $changelog_line ) ) {
                    continue;
                }

                if (substr(ltrim($changelog_line), 0, 2) === '==') {
                    $release_count++;
                    preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $changelog_line, $found_v);
                    preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $changelog_line, $found_d);
                    $version = $found_v[0] ?? null;
                    $date = $found_d[0] ?? null;
                    if ($version && $date) {
                        $releases[$release_count] = array(
                            'version' => $version,
                            'date'    => $date,
                        );
                    } else {
                        error_log("Invalid changelog line: $changelog_line");
                    }
                    continue;
                }


                if ( preg_match( '/[*|-]?\s?(\NEW|\New|new)[:]?\s?(\b|(?=\[))/', $changelog_line ) ) {
                    $changelog_line = preg_replace( '/[*|-]?\s?(\NEW|\New|new)[:]?\s?(\b|(?=\[))/', '', $changelog_line );
                    $releases[ $release_count ]['new'][] = $this->blogtwist_parse_md_and_clean( $changelog_line );
                    continue;
                }

                if ( preg_match( '/[*|-]?\s?(IMP|Imp|imp)[:]?\s?(\b|(?=\[))/', $changelog_line ) ) {
                    $changelog_line = preg_replace( '/[*|-]?\s?(IMP|Imp|imp)[:]?\s?(\b|(?=\[))/', '', $changelog_line );
                    $releases[ $release_count ]['imp'][] = $this->blogtwist_parse_md_and_clean( $changelog_line );
                    continue;
                }

                if ( preg_match( '/[*|-]?\s?(FIX|Fix|fix)[:]?\s?(\b|(?=\[))/', $changelog_line ) ) {
                    $changelog_line = preg_replace( '/[*|-]?\s?(FIX|Fix|fix)[:]?\s?(\b|(?=\[))/', '', $changelog_line );
                    $releases[ $release_count ]['fix'][] = $this->blogtwist_parse_md_and_clean( $changelog_line );
                    continue;
                }


                $changelog_line = $this->blogtwist_parse_md_and_clean( $changelog_line );

                if ( empty( $changelog_line ) ) {
                    continue;
                }

                $releases[ $release_count ]['tweak'][] = $changelog_line;
            }

            return array_values( $releases );
        }

        /**
         * Parse markdown links and cleanup string.
         *
         * @param string $string changelog line.
         *
         * @return string
         */
        private function blogtwist_parse_md_and_clean( $string ) {

            // Drop spaces, starting lines | asterisks.
            $string = trim( $string );
            $string = ltrim( $string, '*' );
            $string = ltrim( $string, '-' );

            // Replace markdown links with <a> tags.
            $string = preg_replace_callback(
                '/\[(.*?)]\((.*?)\)/',
                function ( $matches ) {
                    return '<a href="' . $matches[2] . '" target="_blank" rel="noopener"><i class="dashicons dashicons-arrow-right-alt"></i>' . $matches[1] . '</a>';
                },
                htmlspecialchars( $string )
            );

            return $string;
        }
    }

    $admin_main_class =new Blogtwist_Admin_Main();

endif;