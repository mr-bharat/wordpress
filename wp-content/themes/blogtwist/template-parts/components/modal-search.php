<?php
/**
 * Displays the search icon and modal
 *
 * @package BlogTwist 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<?php do_action('blogtwist_before_model_search'); ?>
<div class="search-modal cover-modal" data-modal-target-string=".search-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Search', 'blogtwist'); ?>">
    <div class="search-modal-inner modal-inner">
        <div class="site-wrapper">
            <div class="search-modal-panel">
                <h2><?php esc_html_e('What are You Looking For?', 'blogtwist'); ?></h2>
                <div class="search-modal-form">
                    <?php
                    get_search_form(
                        array(
                            'aria_label' => __('Search for:', 'blogtwist'),
                        )
                    );
                    ?>
                    <p class="search-modal-help">
                        <?php
                        _e('Begin typing your search above and press return to search. Press Esc to cancel.', 'blogtwist');
                        ?>
                    </p>
                </div>

                <?php get_template_part('template-parts/header/trending-post'); ?>

                <button class="toggle search-untoggle close-search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field">
                    <span class="screen-reader-text"><?php _e('Close search', 'blogtwist'); ?></span>
                    <?php blogtwist_the_theme_svg('cross'); ?>
                </button>
            </div>
        </div>
    </div>
</div><!-- .menu-modal -->
<?php do_action('blogtwist_after_model_search'); ?>
