<?php if (!defined('ABSPATH')) exit; ?>

<table class="form-table">
    <tr>
        <th scope="row">
            <label for="news_seo_meta_description">Description</label>
        </th>
        <td>
            <textarea name="_news_seo_meta_description" id="news_seo_meta_description" rows="3" class="large-text" maxlength="160"><?php echo esc_textarea($meta_description); ?></textarea>
            <p class="description">Custom meta description for this post/page (max 160 characters). Leave blank to auto-generate.</p>
            <p class="character-count">Characters: <span id="meta-desc-count"><?php echo strlen($meta_description); ?></span>/160</p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="news_seo_keywords">Keywords</label>
        </th>
        <td>
            <input type="text" name="_news_seo_keywords" id="news_seo_keywords" value="<?php echo esc_attr($keywords); ?>" class="large-text">
            <p class="description">Comma-separated keywords for this post/page. Leave blank to auto-generate from categories and tags.</p>
            <button type="button" class="button button-small" id="generate-keywords" style="margin-top: 5px;">Generate from Categories & Tags</button>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="news_seo_og_title">Open Graph Title</label>
        </th>
        <td>
            <input type="text" name="_news_seo_og_title" id="news_seo_og_title" value="<?php echo esc_attr($og_title); ?>" class="large-text" maxlength="60">
            <p class="description">Custom Open Graph title (max 60 characters). Leave blank to use post title.</p>
            <p class="character-count">Characters: <span id="og-title-count"><?php echo strlen($og_title); ?></span>/60</p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="news_seo_og_description">Open Graph Description</label>
        </th>
        <td>
            <textarea name="_news_seo_og_description" id="news_seo_og_description" rows="3" class="large-text" maxlength="160"><?php echo esc_textarea($og_description); ?></textarea>
            <p class="description">Custom Open Graph description (max 160 characters). Leave blank to auto-generate.</p>
            <p class="character-count">Characters: <span id="og-desc-count"><?php echo strlen($og_description); ?></span>/160</p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="news_seo_twitter_title">Twitter Title</label>
        </th>
        <td>
            <input type="text" name="_news_seo_twitter_title" id="news_seo_twitter_title" value="<?php echo esc_attr($twitter_title); ?>" class="large-text" maxlength="70">
            <p class="description">Custom Twitter title (max 70 characters). Leave blank to use post title.</p>
            <p class="character-count">Characters: <span id="twitter-title-count"><?php echo strlen($twitter_title); ?></span>/70</p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="news_seo_twitter_description">Twitter Description</label>
        </th>
        <td>
            <textarea name="_news_seo_twitter_description" id="news_seo_twitter_description" rows="3" class="large-text" maxlength="200"><?php echo esc_textarea($twitter_description); ?></textarea>
            <p class="description">Custom Twitter description (max 200 characters). Leave blank to auto-generate.</p>
            <p class="character-count">Characters: <span id="twitter-desc-count"><?php echo strlen($twitter_description); ?></span>/200</p>
        </td>
    </tr>
    
    <?php if ($post->post_type === 'post'): ?>
    <tr>
        <th scope="row">
            <label for="news_seo_news_keywords">News Keywords</label>
        </th>
        <td>
            <input type="text" name="_news_seo_news_keywords" id="news_seo_news_keywords" value="<?php echo esc_attr($news_keywords); ?>" class="large-text">
            <p class="description">Comma-separated keywords for Google News (max 10 keywords recommended).</p>
        </td>
    </tr>
    <?php endif; ?>
    
    <tr>
        <th scope="row">SEO Preview</th>
        <td>
            <div id="seo-preview">
                <h4>Google Search Result Preview:</h4>
                <div class="google-preview">
                    <div class="google-title" id="preview-title"><?php echo esc_html($post->post_title); ?></div>
                    <div class="google-url"><?php echo esc_url(get_permalink($post->ID) ?: home_url('/sample-post/')); ?></div>
                    <div class="google-description" id="preview-description">
                        <?php echo esc_html($meta_description ?: wp_trim_words(strip_tags($post->post_content ?: 'Your post content will appear here...'), 25)); ?>
                    </div>
                </div>
                
                <h4 style="margin-top: 20px;">Social Media Preview:</h4>
                <div class="social-preview">
                    <div class="social-title" id="social-preview-title"><?php echo esc_html($og_title ?: $post->post_title); ?></div>
                    <div class="social-description" id="social-preview-description">
                        <?php echo esc_html($og_description ?: wp_trim_words(strip_tags($post->post_content ?: 'Your post content will appear here...'), 25)); ?>
                    </div>
                    <div class="social-url"><?php echo parse_url(home_url(), PHP_URL_HOST); ?></div>
                </div>
            </div>
        </td>
    </tr>
</table>