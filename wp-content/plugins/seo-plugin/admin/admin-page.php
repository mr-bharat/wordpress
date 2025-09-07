<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1>News SEO Settings</h1>
    
    <?php if (isset($_GET['sitemap_regenerated'])): ?>
        <div class="notice notice-success">
            <p>News sitemap has been regenerated successfully!</p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['rules_flushed'])): ?>
        <div class="notice notice-success">
            <p>Rewrite rules have been flushed successfully! Sitemaps should now be accessible.</p>
        </div>
    <?php endif; ?>
    
    <form method="post" action="">
        <?php wp_nonce_field('news_seo_settings', 'news_seo_settings_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">Default Open Graph Image</th>
                <td>
                    <input type="url" name="default_og_image" value="<?php echo esc_attr($options['default_og_image']); ?>" class="regular-text" id="default_og_image">
                    <button type="button" class="button" id="upload_og_image">Upload Image</button>
                    <p class="description">Default image to use when posts don't have featured images.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Twitter Site Handle</th>
                <td>
                    <input type="text" name="twitter_site" value="<?php echo esc_attr($options['twitter_site']); ?>" class="regular-text" placeholder="yoursitename">
                    <p class="description">Your Twitter handle without the @ symbol.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Apple Mobile App Title</th>
                <td>
                    <input type="text" name="apple_mobile_app_title" value="<?php echo esc_attr($options['apple_mobile_app_title']); ?>" class="regular-text" placeholder="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <p class="description">Title shown when users add your site to their mobile home screen. Leave blank to use site name.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Theme Color</th>
                <td>
                    <input type="color" name="theme_color" value="<?php echo esc_attr($options['theme_color']); ?>" class="regular-text">
                    <p class="description">Theme color for mobile browsers and PWAs (hex color code).</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Site Rating</th>
                <td>
                    <select name="site_rating">
                        <option value="General" <?php selected($options['site_rating'], 'General'); ?>>General</option>
                        <option value="Mature" <?php selected($options['site_rating'], 'Mature'); ?>>Mature</option>
                        <option value="Restricted" <?php selected($options['site_rating'], 'Restricted'); ?>>Restricted</option>
                        <option value="14 years" <?php selected($options['site_rating'], '14 years'); ?>>14 years</option>
                        <option value="Safe for kids" <?php selected($options['site_rating'], 'Safe for kids'); ?>>Safe for kids</option>
                    </select>
                    <p class="description">Content rating for your website.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Copyright</th>
                <td>
                    <input type="text" name="site_copyright" value="<?php echo esc_attr($options['site_copyright']); ?>" class="regular-text" placeholder="© 2024 Your Site Name">
                    <p class="description">Copyright notice. Leave blank to auto-generate: "© [Year] [Site Name]".</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Default Keywords</th>
                <td>
                    <textarea name="default_keywords" class="large-text" rows="3"><?php echo esc_textarea($options['default_keywords']); ?></textarea>
                    <p class="description">Default keywords for pages without specific keywords (comma-separated).</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Home Page Meta Description</th>
                <td>
                    <textarea name="home_page_meta_description" class="large-text" rows="3" maxlength="160"><?php echo esc_textarea($options['home_page_meta_description']); ?></textarea>
                    <p class="description">Custom meta description for the home page when it shows your latest posts. Leave blank to use the site tagline. (Max 160 characters)</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Custom Head Code</th>
                <td>
                    <textarea name="custom_head_code" class="large-text" rows="8" placeholder="Enter custom HTML/CSS/JS code here..."><?php echo esc_textarea($options['custom_head_code']); ?></textarea>
                    <p class="description">Add custom code to the &lt;head&gt; section. Supports HTML, CSS (&lt;style&gt;), JavaScript (&lt;script&gt;), and meta tags. Use for analytics, verification codes, custom fonts, etc.</p>
                    <p class="description"><strong>Security Note:</strong> Only administrators should have access to this field as it allows adding JavaScript code.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">News Keywords</th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_news_keywords" value="1" <?php checked(1, $options['enable_news_keywords']); ?>>
                        Enable news_keywords meta tag
                    </label>
                    <p class="description">Adds news_keywords meta tag for Google News.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Remove WordPress Generator</th>
                <td>
                    <label>
                        <input type="checkbox" name="remove_wp_generator" value="1" <?php checked(1, $options['remove_wp_generator']); ?>>
                        Remove WordPress version meta tag
                    </label>
                    <p class="description">Removes the &lt;meta name="generator" content="WordPress X.X"&gt; tag for security reasons.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">News XML Sitemap</th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_news_sitemap" value="1" <?php checked(1, $options['enable_news_sitemap']); ?>>
                        Enable News XML Sitemap
                    </label>
                    <p class="description">Generates a Google News compatible XML sitemap at <code><?php echo home_url('/news-sitemap.xml'); ?></code></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Include Categories Sitemap</th>
                <td>
                    <input type="checkbox" name="enable_categories_sitemap" value="1" <?php checked($options['enable_categories_sitemap']); ?>>
                    <p class="description">Generate categories sitemap (categories-sitemap.xml).</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Include Tags Sitemap</th>
                <td>
                    <input type="checkbox" name="enable_tags_sitemap" value="1" <?php checked($options['enable_tags_sitemap']); ?>>
                    <p class="description">Generate tags sitemap (tags-sitemap.xml).</p>
                </td>
            </tr>
            
            <tr class="tags-minimum-posts-setting" style="display: <?php echo !empty($options['enable_tags_sitemap']) ? 'table-row' : 'none'; ?>;">
                <th scope="row">Minimum Posts for Tags</th>
                <td>
                    <input type="number" name="tags_minimum_posts" value="<?php echo esc_attr($options['tags_minimum_posts']); ?>" min="0" max="999" class="small-text">
                    <p class="description">Minimum number of posts required for a tag to be included in the tags sitemap. Set to 0 to include all tags.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Sitemap Limit</th>
                <td>
                    <input type="number" name="news_sitemap_limit" value="<?php echo esc_attr($options['news_sitemap_limit']); ?>" min="1" max="50000" class="small-text">
                    <p class="description">Maximum number of posts to include in the news sitemap (Google recommends max 1000).</p>
                </td>
            </tr>
        </table>
        
        <h3>Sitemap Pagination Settings</h3>
        <table class="form-table">
            <tr>
                <th scope="row">Enable Pagination</th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_sitemap_pagination" value="1" <?php checked(1, $options['enable_sitemap_pagination']); ?>>
                        Enable sitemap pagination for large websites
                    </label>
                    <p class="description">Split sitemaps into smaller chunks. Google allows up to 50,000 URLs per sitemap. Enable for sites with 5000+ URLs.</p>
                </td>
            </tr>
            
            <tr class="pagination-setting">
                <th scope="row">Posts per Sitemap</th>
                <td>
                    <input type="number" name="sitemap_posts_per_page" value="<?php echo esc_attr($options['sitemap_posts_per_page']); ?>" min="100" max="50000" class="small-text">
                    <p class="description">Number of posts per sitemap page (min: 100, max: 50000, recommended: 5000-10000).</p>
                </td>
            </tr>
            
            <tr class="pagination-setting">
                <th scope="row">Pages per Sitemap</th>
                <td>
                    <input type="number" name="sitemap_pages_per_page" value="<?php echo esc_attr($options['sitemap_pages_per_page']); ?>" min="100" max="50000" class="small-text">
                    <p class="description">Number of pages per sitemap page (min: 100, max: 50000, recommended: 5000).</p>
                </td>
            </tr>
            
            <tr class="pagination-setting">
                <th scope="row">Terms per Sitemap</th>
                <td>
                    <input type="number" name="sitemap_terms_per_page" value="<?php echo esc_attr($options['sitemap_terms_per_page']); ?>" min="100" max="50000" class="small-text">
                    <p class="description">Number of categories/tags per sitemap page (min: 100, max: 50000, recommended: 2500-5000).</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    
    <h2>XML Sitemaps</h2>
    <table class="form-table">
        <tr>
            <th scope="row">Master Sitemap Index</th>
            <td>
                <p>
                    <a href="<?php echo home_url('/sitemap.xml'); ?>" target="_blank" class="button button-primary">View Sitemap Index</a>
                    <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php?action=flush_sitemap_rules'), 'flush_rules'); ?>" class="button">Fix Sitemap URLs</a>
                </p>
                <p class="description"><strong>Main sitemap</strong> - Lists all individual sitemaps. Submit this URL to search engines.</p>
                <p class="description"><strong>Troubleshooting:</strong> If sitemaps don't work, click "Fix Sitemap URLs" or go to Settings → Permalinks and save.</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Individual Sitemaps</th>
            <td>
                <p>
                    <a href="<?php echo home_url('/posts-sitemap.xml'); ?>" target="_blank" class="button">Posts Sitemap</a>
                    <a href="<?php echo home_url('/pages-sitemap.xml'); ?>" target="_blank" class="button">Pages Sitemap</a>
                    <a href="<?php echo home_url('/categories-sitemap.xml'); ?>" target="_blank" class="button">Categories Sitemap</a>
                    <a href="<?php echo home_url('/tags-sitemap.xml'); ?>" target="_blank" class="button">Tags Sitemap</a>
                </p>
                <p class="description">Individual content type sitemaps. These are automatically included in the master sitemap index.</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Google News Sitemap</th>
            <td>
                <p>
                    <a href="<?php echo home_url('/news-sitemap.xml'); ?>" target="_blank" class="button">View News Sitemap</a>
                    <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php?action=regenerate_news_sitemap'), 'regenerate_sitemap'); ?>" class="button">Regenerate</a>
                </p>
                <p class="description">Google News compatible sitemap (last 2 days only). Submit to Google News Publisher Center.</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Content Coverage</th>
            <td>
                <ul>
                    <li><strong>Posts Sitemap:</strong> All blog posts and news articles</li>
                    <li><strong>Pages Sitemap:</strong> All pages + homepage (priority 1.0)</li>
                    <li><strong>Categories Sitemap:</strong> All category archive pages</li>
                    <li><strong>Tags Sitemap:</strong> All tag archive pages</li>
                    <li><strong>News Sitemap:</strong> Recent posts (2 days) for Google News</li>
                </ul>
                <p class="description">All sitemaps automatically update when content is published or modified.</p>
                <p><strong>Debug Info:</strong></p>
                <ul style="margin-top: 10px; font-family: monospace; font-size: 12px; background: #f1f1f1; padding: 10px; border-radius: 4px;">
                    <li>Site URL: <code><?php echo home_url(); ?></code></li>
                    <li>Permalink Structure: <code><?php echo get_option('permalink_structure') ?: 'Plain (may cause issues)'; ?></code></li>
                    <li>Plugin Version: <code><?php echo NEWS_SEO_VERSION; ?></code></li>
                    <li>WordPress Version: <code><?php echo get_bloginfo('version'); ?></code></li>
                </ul>
            </td>
        </tr>
    </table>
    
    <h2>Plugin Information</h2>
    <table class="form-table">
        <tr>
            <th scope="row">Features</th>
            <td>
                <ul>
                    <li><strong>Meta Description:</strong> Automatic and custom meta descriptions (always enabled)</li>
                    <li><strong>Open Graph:</strong> Complete Facebook and social media optimization with image alt/type (always enabled)</li>
                    <li><strong>Twitter Cards:</strong> Enhanced Twitter sharing with image alt and URL (always enabled)</li>
                    <li><strong>General Meta Tags:</strong> Apple mobile app title, theme color, website, rating, copyright, author, keywords (always enabled)</li>
                    <li><strong>News Keywords:</strong> Google News optimization</li>
                    <li><strong>Comprehensive XML Sitemaps:</strong> Posts, pages, categories, tags + Google News sitemap</li>
                    <li><strong>Sitemap Pagination:</strong> Split large sitemaps into manageable chunks (configurable)</li>
                    <li><strong>Sitemap Index:</strong> Master sitemap listing all individual sitemaps</li>
                    <li><strong>Article Meta:</strong> Publication and modification dates</li>
                </ul>
            </td>
        </tr>
        <tr>
            <th scope="row">Version</th>
            <td><?php echo esc_html(NEWS_SEO_VERSION); ?></td>
        </tr>
    </table>
</div>