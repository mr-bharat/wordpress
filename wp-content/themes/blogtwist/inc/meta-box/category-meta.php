<?php
/**
 * Add custom fields to post categories.
 *
 * @package BlogTwist
 * @since BlogTwist 1.0.0
 */
if (!function_exists('blogtwist_placeholder_image')) :
    /**
     * Get placeholder image
     *
     * @since 1.0.0
     */
    function blogtwist_placeholder_image()
    {
        $src = get_template_directory_uri() . '/assets/images/placeholder.webp';
        return apply_filters('blogtwist_placeholder_image', $src);
    }
endif;

// Add custom fields to the category add form
function blogtwist_add_category_fields() {
    ?>
    <div class="form-field term-thumbnail-wrap">
        <label><?php esc_html_e( 'Thumbnail', 'blogtwist' ); ?></label>
        <div id="post_cat_thumbnail" style="float: left; margin-right: 10px;">
            <img src="<?php echo esc_url( blogtwist_placeholder_image() ); ?>" width="60px" height="60px" />
        </div>
        <div style="line-height: 60px;">
            <input type="hidden" id="post_cat_thumbnail_id" name="post_cat_thumbnail_id" />
            <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'blogtwist' ); ?></button>
            <button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'blogtwist' ); ?></button>
        </div>
        <div class="clear"></div>
    </div>
    <div class="form-field term-color-wrap">
        <label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'blogtwist' ); ?></label>
        <input name="category_color" class="colorpicker" id="term-colorpicker" />
        <p><?php esc_html_e( 'Select color for this category that will be displayed on the front end in many sections.', 'blogtwist' ); ?></p>
    </div>
    <?php
}
add_action( 'category_add_form_fields', 'blogtwist_add_category_fields' );

// Add custom fields to the category edit form
function blogtwist_edit_category_fields( $term ) {
    $color = get_term_meta( $term->term_id, 'category_color', true );
    $thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
    $image = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : blogtwist_placeholder_image();
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'blogtwist' ); ?></label></th>
        <td>
            <div id="post_cat_thumbnail" style="float: left; margin-right: 10px;">
                <img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" />
            </div>
            <div style="line-height: 60px;">
                <input type="hidden" id="post_cat_thumbnail_id" name="post_cat_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
                <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'blogtwist' ); ?></button>
                <button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'blogtwist' ); ?></button>
            </div>
            <div class="clear"></div>
        </td>
    </tr>
    <tr class="form-field term-color-wrap">
        <th scope="row"><label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'blogtwist' ); ?></label></th>
        <td>
            <input name="category_color" value="<?php echo esc_attr( $color ); ?>" class="colorpicker" id="term-colorpicker" />
            <p class="description"><?php esc_html_e( 'Select color for this category that will be displayed on the front end in many sections.', 'blogtwist' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'category_edit_form_fields', 'blogtwist_edit_category_fields', 10 );

// Save custom category fields
function blogtwist_save_category_fields( $term_id ) {
    if ( isset( $_POST['post_cat_thumbnail_id'] ) ) {
        update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['post_cat_thumbnail_id'] ) );
    }

    if ( isset( $_POST['category_color'] ) && ! empty( $_POST['category_color'] ) ) {
        update_term_meta( $term_id, 'category_color', sanitize_hex_color( $_POST['category_color'] ) );
    } else {
        delete_term_meta( $term_id, 'category_color' );
    }
}
add_action( 'created_category', 'blogtwist_save_category_fields', 10, 3 );
add_action( 'edited_category', 'blogtwist_save_category_fields', 10, 3 );

// Enqueue scripts and styles for category fields
function blogtwist_admin_category_meta_js( $hook ) {
    if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) ) {
        return;
    }

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_media();
    wp_enqueue_script( 'blogtwist_post_cat', get_template_directory_uri() . '/inc/meta-box/assets/js/category-meta-box.js', array( 'wp-color-picker' ), '', true );

    wp_localize_script(
        'blogtwist_post_cat',
        'BlogTwistCatScript',
        array(
            'title'   => __( 'Choose an image', 'blogtwist' ),
            'btn_txt' => __( 'Use image', 'blogtwist' ),
            'img'     => esc_js( blogtwist_placeholder_image() ),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'blogtwist_admin_category_meta_js' );