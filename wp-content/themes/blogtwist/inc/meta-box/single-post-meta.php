<?php
/**
 * Implement posts metabox.
 *
 * @package BlogTwist
 */
if ( ! function_exists( 'blogtwist_get_sidebar_layouts' ) ) :
    /**
     * Returns general layout options.
     *
     * @since 1.0.0
     *
     * @return array Options array.
     */
    function blogtwist_get_sidebar_layouts() {
        $options = apply_filters(
            'blogtwist_sidebar_layouts',
            array(
                'left_sidebar'      => array(
                    'url'   => get_template_directory_uri() . '/assets/images/left-sidebar.webp',
                    'label' => esc_html__( 'Left Sidebar', 'blogtwist' ),
                ),
                'right_sidebar'     => array(
                    'url'   => get_template_directory_uri() . '/assets/images/right-sidebar.webp',
                    'label' => esc_html__( 'Right Sidebar', 'blogtwist' ),
                ),
                'no_sidebar'        => array(
                    'url'   => get_template_directory_uri() . '/assets/images/no-sidebar.webp',
                    'label' => esc_html__( 'No Sidebar - Wide', 'blogtwist' ),
                ),
            )
        );
        return $options;
    }
endif;
if ( ! function_exists( 'blogtwist_add_theme_meta_box' ) ) :

    /**
     * Add the Meta Box
     *
     * @since 1.0.0
     */
    function blogtwist_add_theme_meta_box() {

        $post_types = array( 'post', 'page' );

        foreach ( $post_types as $post_type ) {
            add_meta_box(
                'wpmotif-metabox-panel',
                sprintf(
                    /* translators: %s: Post Type. */
                    esc_html__( '%s Settings', 'blogtwist' ),
                    ucwords( $post_type )
                ),
                'blogtwist_meta_box_html',
                $post_type,
                'normal',
                'high'
            );
        }
    }

endif;
add_action( 'add_meta_boxes', 'blogtwist_add_theme_meta_box' );

if ( ! function_exists( 'blogtwist_meta_box_html' ) ) :

    /**
     * Render theme settings meta box.
     *
     * @param mixed $post Post Object.
     * @since 1.0.0
     */
    function blogtwist_meta_box_html( $post ) {

        global $post_type;

        wp_nonce_field( basename( __FILE__ ), 'blogtwist_meta_box_nonce' );
        $page_layout             = get_post_meta( $post->ID, 'blogtwist_page_layout', true );
        $layouts                 = blogtwist_get_sidebar_layouts();
        if (empty($single_post_featured_post)) {
            $single_post_featured_post = 0;
        }
        ?>
        <div id="blogtwist-settings-metabox-container" class="inside be-meta-box">
            <p class="wpmotif-meta-info"><?php esc_html_e( 'This action overrides the global settings from the theme customizer. Leave it unchanged if you want it to remain aligned with the global settings.', 'blogtwist' ); ?>
            <div class="wpmotif-meta-wrapper">

                <div class="wpmotif-meta-header">
                    <a href="#" class="wpmotif-meta-label is-active" data-tab="section-page-layout">
                        <h3><?php esc_html_e( 'Layout Options', 'blogtwist' ); ?></h3>
                    </a>
                </div>

            <div class="wpmotif-meta-content">
                <div class="wpmotif-meta-details is-active" id="blogtwist-tab-section-page-layout">
                    <div class="wpmotif-meta-card">
                        <h4><label for="page-layout"><?php esc_html_e( 'Layout Options', 'blogtwist' ); ?></label></h4>
                        <div class="wpmotif-input-radio">
                            <?php
                            if ( ! empty( $layouts ) && is_array( $layouts ) ) {
                                foreach ( $layouts as $value => $option ) :
                                    ?>
                                    <input class="image-select" type="radio" id="<?php echo esc_attr( $value ); ?>" name="blogtwist_page_layout" value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, $page_layout ); ?>>
                                    <label for="<?php echo esc_attr( $value ); ?>">
                                        <img src="<?php echo esc_html( $option['url'] ); ?>" alt="<?php echo esc_attr( $option['label'] ); ?>" title="<?php echo esc_attr( $option['label'] ); ?>">
                                    </label>
                                    <?php
                                endforeach;
                            }
                            ?>
                        </div>
                    </div>

                </div>
                </div>


            </div>
        </div>
        <?php
    }

endif;


if ( ! function_exists( 'blogtwist_save_postdata' ) ) :

    /**
     * Save posts meta box value.
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID.
     */
    function blogtwist_save_postdata( $post_id ) {

        // Verify nonce.
        if ( ! isset( $_POST['blogtwist_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['blogtwist_meta_box_nonce'], basename( __FILE__ ) ) ) {
            return;
        }

        // Bail if auto save or revision.
        if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post_id ) ) || is_int( wp_is_post_autosave( $post_id ) ) ) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
        if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
            return;
        }

        // Check permission.
        if ( 'page' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['blogtwist_page_layout'] ) ) {

            $valid_layout_values = array_keys( blogtwist_get_sidebar_layouts() );
            $layout_value        = sanitize_text_field( $_POST['blogtwist_page_layout'] );
            if ( in_array( $layout_value, $valid_layout_values ) ) {
                update_post_meta( $post_id, 'blogtwist_page_layout', $layout_value );
            } else {
                delete_post_meta( $post_id, 'blogtwist_page_layout' );
            }
        }

    }

endif;
add_action( 'save_post', 'blogtwist_save_postdata' );

// Enqueue scripts and styles for category fields
function blogtwist_admin_single_post_meta_css($hook)
{
    if (!in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
    }

   wp_enqueue_style('blogtwist_single_post_css', get_template_directory_uri() . '/inc/meta-box/assets/css/single-post-meta.css');

}

add_action('admin_enqueue_scripts', 'blogtwist_admin_single_post_meta_css');
