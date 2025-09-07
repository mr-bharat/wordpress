<?php
/**
 * Plugin Name: News SEO Plugin
 * Description: A comprehensive SEO plugin for news websites featuring meta tags, Open Graph, Twitter Cards, and News XML sitemap.
 * Version: 1.1.1
 * Author: Bharat Rawat
 * Author URI: https://www.facebook.com/bharatrawat000
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

define('NEWS_SEO_VERSION', '1.1.1');
define('NEWS_SEO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NEWS_SEO_PLUGIN_PATH', plugin_dir_path(__FILE__));

class NewsSEOPlugin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('init', array($this, 'add_rewrite_rules'));
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_action('template_redirect', array($this, 'handle_news_sitemap'));
        
        add_action('wp_ajax_regenerate_news_sitemap', array($this, 'regenerate_news_sitemap'));
        add_action('wp_ajax_nopriv_regenerate_news_sitemap', array($this, 'regenerate_news_sitemap'));
        add_action('wp_ajax_flush_sitemap_rules', array($this, 'flush_sitemap_rules'));
        
        add_action('show_user_profile', array($this, 'add_twitter_field_to_user_profile'));
        add_action('edit_user_profile', array($this, 'add_twitter_field_to_user_profile'));
        add_action('personal_options_update', array($this, 'save_twitter_field_from_user_profile'));
        add_action('edit_user_profile_update', array($this, 'save_twitter_field_from_user_profile'));
        
        add_action('category_add_form_fields', array($this, 'add_category_keywords_field'));
        add_action('category_edit_form_fields', array($this, 'edit_category_keywords_field'));
        add_action('created_category', array($this, 'save_category_keywords_field'));
        add_action('edited_category', array($this, 'save_category_keywords_field'));
        
        $this->maybe_remove_wp_generator();
        $this->remove_wp_robots_tag();
    }
    
    public function activate() {
        $default_options = array(
            'enable_news_keywords' => 1,
            'enable_news_sitemap' => 1,
            'default_og_image' => '',
            'twitter_site' => '',
            'apple_mobile_app_title' => '',
            'theme_color' => '#ffffff',
            'site_rating' => 'General',
            'site_copyright' => '',
            'default_keywords' => '',
            'home_page_meta_description' => '',
            'custom_head_code' => '',
            'remove_wp_generator' => 1,
            'enable_categories_sitemap' => 1,
            'enable_tags_sitemap' => 1,
            'tags_minimum_posts' => 0,
            'news_sitemap_limit' => 1000,
            'sitemap_posts_per_page' => 5000,
            'sitemap_pages_per_page' => 5000,
            'sitemap_terms_per_page' => 2500,
            'enable_sitemap_pagination' => 0,
        );
        
        add_option('news_seo_options', $default_options);
        $this->add_rewrite_rules();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function add_rewrite_rules() {
        // Sitemap index
        add_rewrite_rule('^sitemap\.xml$', 'index.php?sitemap_index=1', 'top');
        
        // Individual sitemaps with optional pagination
        add_rewrite_rule('^news-sitemap\.xml$', 'index.php?news_sitemap=1', 'top');
        add_rewrite_rule('^posts-sitemap\.xml$', 'index.php?posts_sitemap=1', 'top');
        add_rewrite_rule('^posts-sitemap([0-9]+)\.xml$', 'index.php?posts_sitemap=1&sitemap_page=$matches[1]', 'top');
        add_rewrite_rule('^pages-sitemap\.xml$', 'index.php?pages_sitemap=1', 'top');
        add_rewrite_rule('^pages-sitemap([0-9]+)\.xml$', 'index.php?pages_sitemap=1&sitemap_page=$matches[1]', 'top');
        add_rewrite_rule('^categories-sitemap\.xml$', 'index.php?categories_sitemap=1', 'top');
        add_rewrite_rule('^categories-sitemap([0-9]+)\.xml$', 'index.php?categories_sitemap=1&sitemap_page=$matches[1]', 'top');
        add_rewrite_rule('^tags-sitemap\.xml$', 'index.php?tags_sitemap=1', 'top');
        add_rewrite_rule('^tags-sitemap([0-9]+)\.xml$', 'index.php?tags_sitemap=1&sitemap_page=$matches[1]', 'top');
    }
    
    public function add_query_vars($vars) {
        $vars[] = 'sitemap_index';
        $vars[] = 'news_sitemap';
        $vars[] = 'posts_sitemap';
        $vars[] = 'pages_sitemap';
        $vars[] = 'categories_sitemap';
        $vars[] = 'tags_sitemap';
        $vars[] = 'sitemap_page';
        return $vars;
    }
    
    public function handle_news_sitemap() {
        if (get_query_var('sitemap_index')) {
            $this->generate_sitemap_index();
            exit;
        }
        
        if (get_query_var('news_sitemap')) {
            $this->generate_news_sitemap();
            exit;
        }
        
        if (get_query_var('posts_sitemap')) {
            $this->generate_posts_sitemap();
            exit;
        }
        
        if (get_query_var('pages_sitemap')) {
            $this->generate_pages_sitemap();
            exit;
        }
        
        if (get_query_var('categories_sitemap')) {
            $this->generate_categories_sitemap();
            exit;
        }
        
        if (get_query_var('tags_sitemap')) {
            $this->generate_tags_sitemap();
            exit;
        }
    }
    
    public function add_meta_tags() {
        if (is_admin()) return;
        
        $options = get_option('news_seo_options');

        // 1. Robots meta tags
        $this->add_robots_tags();

        // 2. Canonical tags for missing page types
        $this->add_canonical_tags();
        
        // 3. Default/General meta tags first - keywords, news_keywords, description priority
        $this->add_keywords_and_description();
        $this->add_general_meta_tags();
        
        // 4. Open Graph tags (always enabled)
        $this->add_open_graph_tags();
        
        // 5. Article-specific tags (included in Open Graph for articles)
        // Already handled within add_open_graph_tags() for articles
        
        // 6. Twitter Cards last (always enabled)
        $this->add_twitter_card_tags();
                
        // 7. Custom head code (always last)
        $this->add_custom_head_code();
    }
    
    private function add_keywords_and_description() {
        $options = get_option('news_seo_options');
        $post_id = get_queried_object_id();
        
        // 1. Keywords first
        $keywords = '';
        if (is_singular()) {
            $custom_keywords = get_post_meta($post_id, '_news_seo_keywords', true);
            if ($custom_keywords) {
                $keywords = $custom_keywords;
            } else {
                // Auto-generate from categories and tags
                $categories = get_the_category($post_id);
                $tags = get_the_tags($post_id);
                $keyword_array = array();
                
                if ($categories) {
                    foreach ($categories as $category) {
                        $keyword_array[] = $category->name;
                    }
                }
                
                if ($tags) {
                    foreach ($tags as $tag) {
                        $keyword_array[] = $tag->name;
                    }
                }
                
                $keywords = implode(', ', array_unique($keyword_array));
            }
        } elseif (is_category()) {
            // Use category-specific keywords if available, otherwise use category name
            $category = get_queried_object();
            $category_keywords = get_term_meta($category->term_id, 'news_seo_keywords', true);
            $keywords = $category_keywords ? $category_keywords : $category->name;
        } elseif (is_tag()) {
            // Use tag name as keyword for tag pages
            $tag = get_queried_object();
            $keywords = $tag->name;
        } elseif (!empty($options['default_keywords'])) {
            $keywords = $options['default_keywords'];
        }
        
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // 2. News keywords second (for posts only)
        if (!empty($options['enable_news_keywords']) && is_singular('post')) {
            $news_keywords = get_post_meta($post_id, '_news_seo_news_keywords', true);
            if ($news_keywords) {
                echo '<meta name="news_keywords" content="' . esc_attr($news_keywords) . '">' . "\n";
            }
        }
        
        // 3. Description third (always enabled)
            $description = '';
            
            if (is_singular()) {
                $custom_desc = get_post_meta($post_id, '_news_seo_meta_description', true);
                if ($custom_desc) {
                    $description = $custom_desc;
                } else {
                    $post = get_post($post_id);
                    $description = wp_trim_words(strip_tags($post->post_content), 25);
                }
            } elseif (is_home() || is_front_page()) {
                // For home page showing latest posts, prioritize custom home page meta description
                if (is_home() && !empty($options['home_page_meta_description'])) {
                    $description = $options['home_page_meta_description'];
                } else {
                    $description = get_bloginfo('description');
                }
            } elseif (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                $description = $term->description ? $term->description : $term->name;
            } elseif (is_search()) {
                $search_query = get_search_query();
                $organization_name = get_bloginfo('name');
                $description = 'Search results for "' . $search_query . '" on ' . $organization_name;
            } elseif (is_author()) {
                $author = get_queried_object();
                $author_bio = get_user_meta($author->ID, 'description', true);
                if ($author_bio) {
                    $description = $author_bio;
                } else {
                    $description = 'Posts by ' . $author->display_name . ' on ' . get_bloginfo('name');
                }
            }
            
        if ($description) {
            echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 25)) . '">' . "\n";
        }
    }
    
    private function add_general_meta_tags() {
        $options = get_option('news_seo_options');
        $post_id = get_queried_object_id();
        
        // Apple mobile web app title
        $app_title = !empty($options['apple_mobile_app_title']) ? $options['apple_mobile_app_title'] : get_bloginfo('name');
        echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr($app_title) . '">' . "\n";
        
        // Theme color
        if (!empty($options['theme_color'])) {
            echo '<meta name="theme-color" content="' . esc_attr($options['theme_color']) . '">' . "\n";
        }
        
        // Website meta tag
        echo '<meta name="website" content="' . esc_attr(home_url()) . '">' . "\n";
        
        // Rating
        if (!empty($options['site_rating'])) {
            echo '<meta name="rating" content="' . esc_attr($options['site_rating']) . '">' . "\n";
        }
        
        // Copyright
        $copyright = !empty($options['site_copyright']) ? $options['site_copyright'] : '© ' . date('Y') . ' ' . get_bloginfo('name');
        echo '<meta name="copyright" content="' . esc_attr($copyright) . '">' . "\n";
        
        // Author (for single posts and pages)
        if (is_singular()) {
            $post = get_post($post_id);
            if (get_post_type($post_id) === 'post') {
                // For posts, use actual author name
                $author_name = get_the_author_meta('display_name', $post->post_author);
            } else {
                // For pages and other post types, use organization/site name
                $author_name = get_bloginfo('name');
            }
            echo '<meta name="author" content="' . esc_attr($author_name) . '">' . "\n";
        }
    }
    
    
    private function add_open_graph_tags() {
        $options = get_option('news_seo_options');
        $post_id = get_queried_object_id();
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        if (is_singular()) {
            $post = get_post($post_id);
            $custom_title = get_post_meta($post_id, '_news_seo_og_title', true);
            $title = $custom_title ? $custom_title : $post->post_title;
            
            $custom_desc = get_post_meta($post_id, '_news_seo_og_description', true);
            $description = $custom_desc ? $custom_desc : wp_trim_words(strip_tags($post->post_content), 25);
            
            // Use "article" for posts, "website" for pages and other post types
            $og_type = (get_post_type($post_id) === 'post') ? 'article' : 'website';
            echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink($post_id)) . '">' . "\n";
            
            if (has_post_thumbnail($post_id)) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');
                $image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true);
                $image_mime = get_post_mime_type(get_post_thumbnail_id($post_id));
                
                echo '<meta property="og:image" content="' . esc_url($image[0]) . '">' . "\n";
                echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '">' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '">' . "\n";
                if ($image_alt) {
                    echo '<meta property="og:image:alt" content="' . esc_attr($image_alt) . '">' . "\n";
                }
                if ($image_mime) {
                    echo '<meta property="og:image:type" content="' . esc_attr($image_mime) . '">' . "\n";
                }
            } elseif (!empty($options['default_og_image'])) {
                echo '<meta property="og:image" content="' . esc_url($options['default_og_image']) . '">' . "\n";
                echo '<meta property="og:image:type" content="image/jpeg">' . "\n";
            }
            
            // Article meta tags only for posts (not pages or other post types)
            if (get_post_type($post_id) === 'post') {
                echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c', $post_id)) . '">' . "\n";
                echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c', $post_id)) . '">' . "\n";
                
                $author_id = $post->post_author;
                $author_name = get_the_author_meta('display_name', $author_id);
                echo '<meta property="article:author" content="' . esc_attr($author_name) . '">' . "\n";
                
                $categories = get_the_category($post_id);
                if ($categories) {
                    foreach ($categories as $category) {
                        echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
                    }
                }
                
                $tags = get_the_tags($post_id);
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                    }
                }
            }
        } else {
            // Handle category and tag pages
            if (is_category()) {
                echo '<meta property="og:type" content="website">' . "\n";
                $category = get_queried_object();
                echo '<meta property="og:title" content="' . esc_attr($category->name) . '">' . "\n";
                $description = $category->description ? $category->description : $category->name;
                echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
                echo '<meta property="og:url" content="' . esc_url(get_category_link($category->term_id)) . '">' . "\n";
            } elseif (is_tag()) {
                echo '<meta property="og:type" content="website">' . "\n";
                $tag = get_queried_object();
                echo '<meta property="og:title" content="' . esc_attr($tag->name) . '">' . "\n";
                $description = $tag->description ? $tag->description : $tag->name;
                echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
                echo '<meta property="og:url" content="' . esc_url(get_tag_link($tag->term_id)) . '">' . "\n";
            } elseif (is_search()) {
                echo '<meta property="og:type" content="website">' . "\n";
                $search_query = get_search_query();
                $organization_name = get_bloginfo('name');
                echo '<meta property="og:title" content="Search: ' . esc_attr($search_query) . ' - ' . esc_attr($organization_name) . '">' . "\n";
                echo '<meta property="og:description" content="Search results for &quot;' . esc_attr($search_query) . '&quot; on ' . esc_attr($organization_name) . '">' . "\n";
                echo '<meta property="og:url" content="' . esc_url(get_search_link()) . '">' . "\n";
            } elseif (is_author()) {
                $author = get_queried_object();
                $author_bio = get_user_meta($author->ID, 'description', true);
                echo '<meta property="og:type" content="profile">' . "\n";
                echo '<meta property="og:title" content="' . esc_attr($author->display_name) . ' - ' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                if ($author_bio) {
                    echo '<meta property="og:description" content="' . esc_attr($author_bio) . '">' . "\n";
                } else {
                    echo '<meta property="og:description" content="Posts by ' . esc_attr($author->display_name) . ' on ' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                }
                echo '<meta property="og:url" content="' . esc_url(get_author_posts_url($author->ID)) . '">' . "\n";
            } else {
                echo '<meta property="og:type" content="website">' . "\n";
                echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                
                // For home page showing latest posts, use custom description if available
                $og_description = get_bloginfo('description');
                if (is_home() && !empty($options['home_page_meta_description'])) {
                    $og_description = $options['home_page_meta_description'];
                }
                echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
                echo '<meta property="og:url" content="' . esc_url(home_url()) . '">' . "\n";
            }
            
            if (!empty($options['default_og_image'])) {
                echo '<meta property="og:image" content="' . esc_url($options['default_og_image']) . '">' . "\n";
                echo '<meta property="og:image:type" content="image/jpeg">' . "\n";
            }
        }
    }
    
    private function add_twitter_card_tags() {
        $options = get_option('news_seo_options');
        $post_id = get_queried_object_id();
        
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        if (!empty($options['twitter_site'])) {
            echo '<meta name="twitter:site" content="@' . esc_attr($options['twitter_site']) . '">' . "\n";
        }
        
        if (is_singular()) {
            $post = get_post($post_id);
            $custom_title = get_post_meta($post_id, '_news_seo_twitter_title', true);
            $title = $custom_title ? $custom_title : $post->post_title;
            
            $custom_desc = get_post_meta($post_id, '_news_seo_twitter_description', true);
            $description = $custom_desc ? $custom_desc : wp_trim_words(strip_tags($post->post_content), 25);
            
            echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta name="twitter:url" content="' . esc_url(get_permalink($post_id)) . '">' . "\n";
            
            if (has_post_thumbnail($post_id)) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');
                $image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true);
                
                echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '">' . "\n";
                if ($image_alt) {
                    echo '<meta name="twitter:image:alt" content="' . esc_attr($image_alt) . '">' . "\n";
                }
            } elseif (!empty($options['default_og_image'])) {
                echo '<meta name="twitter:image" content="' . esc_url($options['default_og_image']) . '">' . "\n";
            }
            
            $author_id = $post->post_author;
            $twitter_handle = get_user_meta($author_id, 'news_seo_twitter_handle', true);
            if ($twitter_handle) {
                echo '<meta name="twitter:creator" content="@' . esc_attr($twitter_handle) . '">' . "\n";
            }
        } else {
            // Handle category and tag pages
            if (is_category()) {
                $category = get_queried_object();
                echo '<meta name="twitter:title" content="' . esc_attr($category->name) . '">' . "\n";
                $description = $category->description ? $category->description : $category->name;
                echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
                echo '<meta name="twitter:url" content="' . esc_url(get_category_link($category->term_id)) . '">' . "\n";
            } elseif (is_tag()) {
                $tag = get_queried_object();
                echo '<meta name="twitter:title" content="' . esc_attr($tag->name) . '">' . "\n";
                $description = $tag->description ? $tag->description : $tag->name;
                echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
                echo '<meta name="twitter:url" content="' . esc_url(get_tag_link($tag->term_id)) . '">' . "\n";
            } elseif (is_search()) {
                $search_query = get_search_query();
                $organization_name = get_bloginfo('name');
                echo '<meta name="twitter:title" content="Search: ' . esc_attr($search_query) . ' - ' . esc_attr($organization_name) . '">' . "\n";
                echo '<meta name="twitter:description" content="Search results for &quot;' . esc_attr($search_query) . '&quot; on ' . esc_attr($organization_name) . '">' . "\n";
                echo '<meta name="twitter:url" content="' . esc_url(get_search_link()) . '">' . "\n";
            } elseif (is_author()) {
                $author = get_queried_object();
                $author_bio = get_user_meta($author->ID, 'description', true);
                echo '<meta name="twitter:title" content="' . esc_attr($author->display_name) . ' - ' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                if ($author_bio) {
                    echo '<meta name="twitter:description" content="' . esc_attr($author_bio) . '">' . "\n";
                } else {
                    echo '<meta name="twitter:description" content="Posts by ' . esc_attr($author->display_name) . ' on ' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                }
                echo '<meta name="twitter:url" content="' . esc_url(get_author_posts_url($author->ID)) . '">' . "\n";
            } else {
                echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                
                // For home page showing latest posts, use custom description if available
                $twitter_description = get_bloginfo('description');
                if (is_home() && !empty($options['home_page_meta_description'])) {
                    $twitter_description = $options['home_page_meta_description'];
                }
                echo '<meta name="twitter:description" content="' . esc_attr($twitter_description) . '">' . "\n";
                echo '<meta name="twitter:url" content="' . esc_url(home_url()) . '">' . "\n";
            }
            
            if (!empty($options['default_og_image'])) {
                echo '<meta name="twitter:image" content="' . esc_url($options['default_og_image']) . '">' . "\n";
            }
        }
    }
    
    private function add_canonical_tags() {
        // WordPress core handles canonical for posts and pages, but not for home, category, and tag pages
        if (is_home() || is_front_page()) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '">' . "\n";
        } elseif (is_category()) {
            $category = get_queried_object();
            echo '<link rel="canonical" href="' . esc_url(get_category_link($category->term_id)) . '">' . "\n";
        } elseif (is_tag()) {
            $tag = get_queried_object();
            echo '<link rel="canonical" href="' . esc_url(get_tag_link($tag->term_id)) . '">' . "\n";
        } elseif (is_tax()) {
            $term = get_queried_object();
            echo '<link rel="canonical" href="' . esc_url(get_term_link($term)) . '">' . "\n";
        } elseif (is_author()) {
            $author = get_queried_object();
            echo '<link rel="canonical" href="' . esc_url(get_author_posts_url($author->ID)) . '">' . "\n";
        }
    }
    
    private function add_robots_tags() {
        $robots = '';
        
        if (is_home() || is_front_page()) {
            // Home page: index, follow with rich snippets
            $robots = 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
        } elseif (is_singular('post')) {
            // Posts/articles: index, follow with rich snippets
            $robots = 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
        } elseif (is_page()) {
            // Static pages: index, follow with rich snippets
            $robots = 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
        } elseif (is_category()) {
            // Category pages: index, follow with rich snippets
            $robots = 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
        } elseif (is_tax()) {
            // Custom taxonomy pages: index, follow with rich snippets
            $robots = 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
        } elseif (is_tag()) {
            // Tag pages: noindex, follow
            $robots = 'noindex, follow';
        } elseif (is_search()) {
            // Search pages: noindex, follow
            $robots = 'noindex, follow';
        } elseif (is_author()) {
            // Author pages: noindex, follow
            $robots = 'noindex, follow';
        }
        
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        }
    }
    
    private function add_custom_head_code() {
        $options = get_option('news_seo_options');
        
        if (!empty($options['custom_head_code'])) {
            echo "\n" . '<!-- Custom Head Code from News SEO Plugin -->' . "\n";
            echo $options['custom_head_code'] . "\n";
            echo '<!-- End Custom Head Code -->' . "\n";
        }
    }
    
    private function maybe_remove_wp_generator() {
        $options = get_option('news_seo_options');
        
        if (!empty($options['remove_wp_generator'])) {
            remove_action('wp_head', 'wp_generator');
            add_filter('the_generator', '__return_empty_string');
        }
    }
    
    private function remove_wp_robots_tag() {
        // Remove WordPress default robots meta tag since we're adding our own comprehensive one
        remove_action('wp_head', 'wp_robots');
        remove_action('wp_head', 'noindex', 1);
        add_filter('wp_robots', '__return_empty_array');
    }
    
    public function add_twitter_field_to_user_profile($user) {
        ?>
        <h3>News SEO Settings</h3>
        <table class="form-table">
            <tr>
                <th><label for="news_seo_twitter_handle">Twitter Handle</label></th>
                <td>
                    <input type="text" name="news_seo_twitter_handle" id="news_seo_twitter_handle" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'news_seo_twitter_handle', true)); ?>" 
                           class="regular-text" placeholder="username" />
                    <p class="description">Enter your Twitter username without the @ symbol (e.g., "username"). This will be used in the twitter:creator meta tag for articles you author.</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_twitter_field_from_user_profile($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        
        if (isset($_POST['news_seo_twitter_handle'])) {
            $twitter_handle = sanitize_text_field($_POST['news_seo_twitter_handle']);
            // Remove @ symbol if user included it
            $twitter_handle = ltrim($twitter_handle, '@');
            update_user_meta($user_id, 'news_seo_twitter_handle', $twitter_handle);
        }
    }
    
    public function add_category_keywords_field() {
        ?>
        <div class="form-field">
            <label for="news_seo_category_keywords">SEO Keywords</label>
            <input type="text" name="news_seo_category_keywords" id="news_seo_category_keywords" class="regular-text" placeholder="keyword1, keyword2, keyword3" />
            <p class="description">Comma-separated keywords for this category's SEO meta tags. Leave blank to use the category name as keyword.</p>
        </div>
        <?php
    }
    
    public function edit_category_keywords_field($term) {
        $keywords = get_term_meta($term->term_id, 'news_seo_keywords', true);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="news_seo_category_keywords">SEO Keywords</label></th>
            <td>
                <input type="text" name="news_seo_category_keywords" id="news_seo_category_keywords" 
                       value="<?php echo esc_attr($keywords); ?>" class="regular-text" 
                       placeholder="keyword1, keyword2, keyword3" />
                <p class="description">Comma-separated keywords for this category's SEO meta tags. Leave blank to use the category name as keyword.</p>
            </td>
        </tr>
        <?php
    }
    
    public function save_category_keywords_field($term_id) {
        if (isset($_POST['news_seo_category_keywords'])) {
            $keywords = sanitize_text_field($_POST['news_seo_category_keywords']);
            update_term_meta($term_id, 'news_seo_keywords', $keywords);
        }
    }
    
    
    public function add_admin_menu() {
        add_options_page(
            'News SEO Settings',
            'News SEO',
            'manage_options',
            'news-seo-settings',
            array($this, 'admin_page')
        );
    }
    
    public function admin_page() {
        if (isset($_POST['submit'])) {
            $options = array();
            $options['enable_news_keywords'] = isset($_POST['enable_news_keywords']) ? 1 : 0;
            $options['enable_news_sitemap'] = isset($_POST['enable_news_sitemap']) ? 1 : 0;
            $options['enable_sitemap_pagination'] = isset($_POST['enable_sitemap_pagination']) ? 1 : 0;
            $options['default_og_image'] = sanitize_url($_POST['default_og_image']);
            $options['twitter_site'] = sanitize_text_field($_POST['twitter_site']);
            $options['apple_mobile_app_title'] = sanitize_text_field($_POST['apple_mobile_app_title']);
            $options['theme_color'] = sanitize_hex_color($_POST['theme_color']);
            $options['site_rating'] = sanitize_text_field($_POST['site_rating']);
            $options['site_copyright'] = sanitize_text_field($_POST['site_copyright']);
            $options['default_keywords'] = sanitize_text_field($_POST['default_keywords']);
            $options['home_page_meta_description'] = sanitize_text_field($_POST['home_page_meta_description']);
            $options['custom_head_code'] = wp_kses($_POST['custom_head_code'], array(
                'script' => array('type' => array(), 'src' => array(), 'async' => array(), 'defer' => array()),
                'style' => array('type' => array()),
                'link' => array('rel' => array(), 'href' => array(), 'type' => array()),
                'meta' => array('name' => array(), 'content' => array(), 'property' => array(), 'http-equiv' => array()),
                'noscript' => array()
            ));
            $options['remove_wp_generator'] = isset($_POST['remove_wp_generator']) ? 1 : 0;
            $options['enable_categories_sitemap'] = isset($_POST['enable_categories_sitemap']) ? 1 : 0;
            $options['enable_tags_sitemap'] = isset($_POST['enable_tags_sitemap']) ? 1 : 0;
            $options['tags_minimum_posts'] = max(0, intval($_POST['tags_minimum_posts']));
            $options['news_sitemap_limit'] = intval($_POST['news_sitemap_limit']);
            $options['sitemap_posts_per_page'] = max(100, intval($_POST['sitemap_posts_per_page']));
            $options['sitemap_pages_per_page'] = max(100, intval($_POST['sitemap_pages_per_page']));
            $options['sitemap_terms_per_page'] = max(100, intval($_POST['sitemap_terms_per_page']));
            
            update_option('news_seo_options', $options);
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }
        
        $options = get_option('news_seo_options');
        include NEWS_SEO_PLUGIN_PATH . 'admin/admin-page.php';
    }
    
    public function add_meta_boxes() {
        $post_types = get_post_types(array('public' => true));
        foreach ($post_types as $post_type) {
            add_meta_box(
                'news_seo_meta_box',
                'News SEO Settings',
                array($this, 'meta_box_callback'),
                $post_type,
                'normal',
                'high'
            );
        }
    }
    
    public function meta_box_callback($post) {
        wp_nonce_field('news_seo_meta_box', 'news_seo_meta_box_nonce');
        
        $meta_description = get_post_meta($post->ID, '_news_seo_meta_description', true);
        $og_title = get_post_meta($post->ID, '_news_seo_og_title', true);
        $og_description = get_post_meta($post->ID, '_news_seo_og_description', true);
        $twitter_title = get_post_meta($post->ID, '_news_seo_twitter_title', true);
        $twitter_description = get_post_meta($post->ID, '_news_seo_twitter_description', true);
        $news_keywords = get_post_meta($post->ID, '_news_seo_news_keywords', true);
        $keywords = get_post_meta($post->ID, '_news_seo_keywords', true);
        
        include NEWS_SEO_PLUGIN_PATH . 'admin/meta-box.php';
    }
    
    public function save_meta_box_data($post_id) {
        if (!isset($_POST['news_seo_meta_box_nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['news_seo_meta_box_nonce'], 'news_seo_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = array(
            '_news_seo_meta_description',
            '_news_seo_og_title',
            '_news_seo_og_description',
            '_news_seo_twitter_title',
            '_news_seo_twitter_description',
            '_news_seo_news_keywords',
            '_news_seo_keywords'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    public function generate_sitemap_index() {
        $options = get_option('news_seo_options');
        
        if (empty($options['enable_news_sitemap'])) {
            wp_die('Sitemap is disabled');
        }
        
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Posts sitemaps (with pagination if enabled)
        if (!empty($options['enable_sitemap_pagination'])) {
            $posts_per_page = !empty($options['sitemap_posts_per_page']) ? $options['sitemap_posts_per_page'] : 1000;
            $total_posts = wp_count_posts('post')->publish;
            $total_pages = ceil($total_posts / $posts_per_page);
            
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<sitemap>' . "\n";
                echo '<loc>' . esc_url(home_url($i == 1 ? '/posts-sitemap.xml' : "/posts-sitemap{$i}.xml")) . '</loc>' . "\n";
                echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
                echo '</sitemap>' . "\n";
            }
        } else {
            echo '<sitemap>' . "\n";
            echo '<loc>' . esc_url(home_url('/posts-sitemap.xml')) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '</sitemap>' . "\n";
        }
        
        // Pages sitemaps (with pagination if enabled)
        if (!empty($options['enable_sitemap_pagination'])) {
            $pages_per_page = !empty($options['sitemap_pages_per_page']) ? $options['sitemap_pages_per_page'] : 1000;
            $total_pages_count = wp_count_posts('page')->publish;
            $total_page_sitemaps = ceil($total_pages_count / $pages_per_page);
            
            for ($i = 1; $i <= $total_page_sitemaps; $i++) {
                echo '<sitemap>' . "\n";
                echo '<loc>' . esc_url(home_url($i == 1 ? '/pages-sitemap.xml' : "/pages-sitemap{$i}.xml")) . '</loc>' . "\n";
                echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
                echo '</sitemap>' . "\n";
            }
        } else {
            echo '<sitemap>' . "\n";
            echo '<loc>' . esc_url(home_url('/pages-sitemap.xml')) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '</sitemap>' . "\n";
        }
        
        // Categories sitemaps (with pagination if enabled)
        if (!empty($options['enable_categories_sitemap'])) {
            if (!empty($options['enable_sitemap_pagination'])) {
                $terms_per_page = !empty($options['sitemap_terms_per_page']) ? $options['sitemap_terms_per_page'] : 1000;
                $total_categories = wp_count_terms('category', array('hide_empty' => true));
                $total_cat_sitemaps = ceil($total_categories / $terms_per_page);
                
                for ($i = 1; $i <= $total_cat_sitemaps; $i++) {
                    echo '<sitemap>' . "\n";
                    echo '<loc>' . esc_url(home_url($i == 1 ? '/categories-sitemap.xml' : "/categories-sitemap{$i}.xml")) . '</loc>' . "\n";
                    echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
                    echo '</sitemap>' . "\n";
                }
            } else {
                echo '<sitemap>' . "\n";
                echo '<loc>' . esc_url(home_url('/categories-sitemap.xml')) . '</loc>' . "\n";
                echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
                echo '</sitemap>' . "\n";
            }
        }
        
        // Tags sitemaps (with pagination if enabled)
        if (!empty($options['enable_tags_sitemap'])) {
            if (!empty($options['enable_sitemap_pagination'])) {
                $terms_per_page = !empty($options['sitemap_terms_per_page']) ? $options['sitemap_terms_per_page'] : 1000;
                $total_tags = wp_count_terms('post_tag', array('hide_empty' => true));
                $total_tag_sitemaps = ceil($total_tags / $terms_per_page);
                
                for ($i = 1; $i <= $total_tag_sitemaps; $i++) {
                    echo '<sitemap>' . "\n";
                    echo '<loc>' . esc_url(home_url($i == 1 ? '/tags-sitemap.xml' : "/tags-sitemap{$i}.xml")) . '</loc>' . "\n";
                    echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
                    echo '</sitemap>' . "\n";
                }
            } else {
                echo '<sitemap>' . "\n";
                echo '<loc>' . esc_url(home_url('/tags-sitemap.xml')) . '</loc>' . "\n";
                echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
                echo '</sitemap>' . "\n";
            }
        }
        
        // Google News sitemap (no pagination needed - only 2 days)
        echo '<sitemap>' . "\n";
        echo '<loc>' . esc_url(home_url('/news-sitemap.xml')) . '</loc>' . "\n";
        echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
        echo '</sitemap>' . "\n";
        
        echo '</sitemapindex>';
    }
    
    public function generate_posts_sitemap() {
        $options = get_option('news_seo_options');
        
        if (empty($options['enable_news_sitemap'])) {
            wp_die('Sitemap is disabled');
        }
        
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        
        $page = max(1, intval(get_query_var('sitemap_page', 1)));
        $posts_per_page = !empty($options['sitemap_posts_per_page']) ? $options['sitemap_posts_per_page'] : 1000;
        $offset = ($page - 1) * $posts_per_page;
        
        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => $posts_per_page,
            'offset' => $offset,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($posts as $post) {
            // Double-check to ensure we only include posts, not pages
            if ($post->post_type !== 'post') {
                continue;
            }
            
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . esc_xml(get_the_modified_date('c', $post->ID)) . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.8</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
    }
    
    public function generate_pages_sitemap() {
        $options = get_option('news_seo_options');
        
        if (empty($options['enable_news_sitemap'])) {
            wp_die('Sitemap is disabled');
        }
        
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        
        $page = max(1, intval(get_query_var('sitemap_page', 1)));
        $pages_per_page = !empty($options['sitemap_pages_per_page']) ? $options['sitemap_pages_per_page'] : 1000;
        $offset = ($page - 1) * $pages_per_page;
        
        $pages = get_pages(array(
            'post_status' => 'publish',
            'number' => $pages_per_page,
            'offset' => $offset,
            'sort_column' => 'menu_order, post_title'
        ));
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Add homepage only on first page
        if ($page == 1) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '<changefreq>daily</changefreq>' . "\n";
            echo '<priority>1.0</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        foreach ($pages as $page_item) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($page_item->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . esc_xml(get_the_modified_date('c', $page_item->ID)) . '</lastmod>' . "\n";
            echo '<changefreq>monthly</changefreq>' . "\n";
            echo '<priority>0.6</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
    }
    
    public function generate_categories_sitemap() {
        $options = get_option('news_seo_options');
        
        if (empty($options['enable_news_sitemap']) || empty($options['enable_categories_sitemap'])) {
            wp_die('Categories sitemap is disabled');
        }
        
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        
        $page = max(1, intval(get_query_var('sitemap_page', 1)));
        $terms_per_page = !empty($options['sitemap_terms_per_page']) ? $options['sitemap_terms_per_page'] : 1000;
        $offset = ($page - 1) * $terms_per_page;
        
        $categories = get_categories(array(
            'hide_empty' => true,
            'number' => $terms_per_page,
            'offset' => $offset
        ));
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($categories as $category) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_category_link($category->term_id)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.5</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
    }
    
    public function generate_tags_sitemap() {
        $options = get_option('news_seo_options');
        
        if (empty($options['enable_news_sitemap']) || empty($options['enable_tags_sitemap'])) {
            wp_die('Tags sitemap is disabled');
        }
        
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        
        $page = max(1, intval(get_query_var('sitemap_page', 1)));
        $terms_per_page = !empty($options['sitemap_terms_per_page']) ? $options['sitemap_terms_per_page'] : 1000;
        $offset = ($page - 1) * $terms_per_page;
        
        $tag_args = array(
            'hide_empty' => true,
            'number' => $terms_per_page,
            'offset' => $offset
        );
        
        // If minimum posts filter is set, only include tags with specified minimum posts
        $minimum_posts = intval($options['tags_minimum_posts']);
        if ($minimum_posts > 0) {
            // Custom filtering approach using get_terms
            $all_tags = get_tags(array(
                'hide_empty' => true,
                'number' => $terms_per_page * 2, // Get more to account for filtering
                'offset' => $offset
            ));
            
            $tags = array();
            $count = 0;
            foreach ($all_tags as $tag) {
                if ($tag->count >= $minimum_posts) {
                    $tags[] = $tag;
                    $count++;
                    if ($count >= $terms_per_page) break;
                }
            }
        } else {
            $tags = get_tags($tag_args);
        }
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($tags as $tag) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_tag_link($tag->term_id)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.4</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
    }

    public function generate_news_sitemap() {
        $options = get_option('news_seo_options');
        
        if (empty($options['enable_news_sitemap'])) {
            wp_die('News sitemap is disabled');
        }
        
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        
        // Only get 'post' type for news sitemap, exclude 'page' and other post types
        $post_types = array('post');
        $limit = !empty($options['news_sitemap_limit']) ? $options['news_sitemap_limit'] : 1000;
        
        $posts = get_posts(array(
            'post_type' => $post_types,
            'post_status' => 'publish',
            'numberposts' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
            'date_query' => array(
                array(
                    'after' => '2 days ago'
                )
            )
        ));
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";
        
        foreach ($posts as $post) {
            // Double-check to ensure we only include posts, not pages
            if ($post->post_type !== 'post') {
                continue;
            }
            
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<news:news>' . "\n";
            echo '<news:publication>' . "\n";
            echo '<news:name>' . esc_xml(get_bloginfo('name')) . '</news:name>' . "\n";
            echo '<news:language>' . esc_xml(get_locale()) . '</news:language>' . "\n";
            echo '</news:publication>' . "\n";
            echo '<news:publication_date>' . esc_xml(get_the_date('c', $post->ID)) . '</news:publication_date>' . "\n";
            echo '<news:title>' . esc_xml($post->post_title) . '</news:title>' . "\n";
            echo '</news:news>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
    }
    
    public function regenerate_news_sitemap() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        wp_redirect(admin_url('options-general.php?page=news-seo-settings&sitemap_regenerated=1'));
        exit;
    }
    
    public function flush_sitemap_rules() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $this->add_rewrite_rules();
        flush_rewrite_rules();
        
        wp_redirect(admin_url('options-general.php?page=news-seo-settings&rules_flushed=1'));
        exit;
    }
    
    public function enqueue_scripts() {
        // Frontend scripts if needed
    }
    
    public function admin_enqueue_scripts($hook) {
        if ($hook == 'settings_page_news-seo-settings' || $hook == 'post.php' || $hook == 'post-new.php') {
            wp_enqueue_media();
            wp_enqueue_script('news-seo-admin', NEWS_SEO_PLUGIN_URL . 'admin/js/admin.js', array('jquery'), NEWS_SEO_VERSION, true);
            wp_enqueue_style('news-seo-admin', NEWS_SEO_PLUGIN_URL . 'admin/css/admin.css', array(), NEWS_SEO_VERSION);
        }
    }
}

NewsSEOPlugin::get_instance();