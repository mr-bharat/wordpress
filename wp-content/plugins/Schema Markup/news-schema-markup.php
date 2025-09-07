<?php
/**
 * Plugin Name: News Schema Markup
 * Description: Automatically adds schema.org structured data markup for news websites including NewsArticle, Organization, Person, and more.
 * Version: 8.0.0
 * Author: Bharat Rawat
 * Author URI: https://www.facebook.com/bharatrawat000
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

class NewsSchemaMarkup {
    
    private $options;
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_head', array($this, 'add_schema_markup'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('show_user_profile', array($this, 'add_author_fields'));
        add_action('edit_user_profile', array($this, 'add_author_fields'));
        add_action('personal_options_update', array($this, 'save_author_fields'));
        add_action('edit_user_profile_update', array($this, 'save_author_fields'));
        add_action('add_meta_boxes', array($this, 'add_post_meta_boxes'));
        add_action('save_post', array($this, 'save_post_meta'));
        register_activation_hook(__FILE__, array($this, 'activate'));
    }
    
    public function init() {
        $this->options = get_option('news_schema_options', $this->get_default_options());
    }
    
    public function activate() {
        add_option('news_schema_options', $this->get_default_options());
    }
    
    private function get_default_options() {
        return array(
            'organization_name' => get_bloginfo('name'),
            'organization_url' => home_url(),
            'organization_logo' => '',
            'organization_type' => 'NewsMediaOrganization',
            'organization_sameas' => '',
            'organization_founding_date' => '',
            'organization_founder_name' => '',
            'organization_founder_sameas' => '',
            'contact_telephone' => '',
            'contact_email' => '',
            'contact_address' => '',
            'enable_newsarticle' => true,
            'enable_organization' => true,
            'enable_person' => true,
            'enable_website' => true,
            'enable_breadcrumbs' => true,
            'enable_webpage' => true,
            'enable_navigation' => true,
        );
    }
    
    public function add_schema_markup() {
        global $post;
        
        $schema_data = array();
        
        if (is_single() && $this->options['enable_newsarticle']) {
            $schema_data[] = $this->get_news_article_schema();
        }
        
        if ($this->options['enable_website']) {
            $schema_data[] = $this->get_website_schema();
        }
        
        if ($this->options['enable_organization']) {
            $schema_data[] = $this->get_organization_schema();
        }
        
        if ((is_single() || is_category() || is_tag()) && $this->options['enable_breadcrumbs']) {
            $breadcrumb_schema = $this->get_breadcrumb_schema();
            if ($breadcrumb_schema) {
                $schema_data[] = $breadcrumb_schema;
            }
        }
        
        if ($this->options['enable_webpage']) {
            $webpage_schema = $this->get_webpage_schema();
            if ($webpage_schema) {
                $schema_data[] = $webpage_schema;
            }
        }
        
        if ($this->options['enable_navigation']) {
            $navigation_schema = $this->get_navigation_schema();
            if ($navigation_schema) {
                $schema_data = array_merge($schema_data, $navigation_schema);
            }
        }
        
        if (!empty($schema_data)) {
            $graph_data = array(
                '@context' => 'https://schema.org',
                '@graph' => $schema_data
            );
            
            echo '<script type="application/ld+json">';
            echo wp_json_encode($graph_data, JSON_UNESCAPED_SLASHES);
            echo '</script>' . "\n";
        }
    }
    
    private function get_news_article_schema() {
        global $post;
        
        if (!is_single() || !$post) {
            return null;
        }
        
        $author = get_the_author_meta('display_name', $post->post_author);
        $author_url = get_author_posts_url($post->post_author);
        
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        $image_url = $image ? $image[0] : '';
        
        $schema = array(
            '@type' => 'NewsArticle',
            '@id' => get_permalink() . '#article',
            'headline' => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink()
            ),
            'inLanguage' => 'en',
            'articleBody' => wp_strip_all_tags(get_the_content()),
            'wordCount' => str_word_count(wp_strip_all_tags(get_the_content())),
            'isAccessibleForFree' => $this->get_post_accessibility($post->ID),
            'thumbnailUrl' => $image_url,
            'author' => $this->get_person_schema($post->post_author, $author, $author_url),
            'publisher' => array(
                '@id' => $this->options['organization_url'] . '#organization'
            )
        );
        
        if ($image_url) {
            $image_schema = array(
                '@type' => 'ImageObject',
                'url' => $image_url,
                'width' => $image[1],
                'height' => $image[2]
            );
            
            $image_caption = wp_get_attachment_caption(get_post_thumbnail_id($post->ID));
            if ($image_caption) {
                $image_schema['caption'] = $image_caption;
            }
            
            $image_alt = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
            if ($image_alt) {
                $image_schema['name'] = $image_alt;
            }
            
            $schema['image'] = $image_schema;
        }
        
        
        $categories = get_the_category();
        if (!empty($categories)) {
            $schema['articleSection'] = array();
            foreach ($categories as $category) {
                $schema['articleSection'][] = $category->name;
            }
        }
        
        $tags = get_the_tags();
        if (!empty($tags)) {
            $schema['keywords'] = array();
            foreach ($tags as $tag) {
                $schema['keywords'][] = $tag->name;
            }
        }
        
        return $schema;
    }
    
    private function get_organization_schema() {
        $schema = array(
            '@type' => $this->options['organization_type'],
            '@id' => $this->options['organization_url'] . '#organization',
            'name' => $this->options['organization_name'],
            'url' => $this->options['organization_url']
        );
        
        if (!empty($this->options['organization_logo'])) {
            $schema['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $this->options['organization_logo']
            );
        }
        
        if (!empty($this->options['organization_sameas'])) {
            $sameas_urls = array_filter(array_map('trim', explode("\n", $this->options['organization_sameas'])));
            if (!empty($sameas_urls)) {
                $schema['sameAs'] = $sameas_urls;
            }
        }
        
        if (!empty($this->options['organization_founding_date'])) {
            $schema['foundingDate'] = $this->options['organization_founding_date'];
        }
        
        if (!empty($this->options['organization_founder_name'])) {
            $founder_schema = array(
                '@type' => 'Person',
                'name' => $this->options['organization_founder_name']
            );
            
            if (!empty($this->options['organization_founder_sameas'])) {
                $founder_sameas_urls = array_filter(array_map('trim', explode("\n", $this->options['organization_founder_sameas'])));
                if (!empty($founder_sameas_urls)) {
                    $founder_schema['sameAs'] = $founder_sameas_urls;
                }
            }
            
            $schema['founder'] = $founder_schema;
        }
        
        if (!empty($this->options['contact_telephone']) || !empty($this->options['contact_email'])) {
            $contact_point = array(
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service'
            );
            
            if (!empty($this->options['contact_telephone'])) {
                $contact_point['telephone'] = $this->options['contact_telephone'];
            }
            
            if (!empty($this->options['contact_email'])) {
                $contact_point['email'] = $this->options['contact_email'];
            }
            
            $schema['contactPoint'] = $contact_point;
        }
        
        return $schema;
    }
    
    private function get_post_accessibility($post_id) {
        $meta_value = get_post_meta($post_id, '_news_schema_is_accessible_for_free', true);
        
        if ($meta_value === '') {
            return true;
        }
        
        return (bool)$meta_value;
    }
    
    private function get_person_schema($author_id, $author_name, $author_url) {
        $schema = array(
            '@type' => 'Person',
            'name' => $author_name,
            'url' => $author_url
        );
        
        $author_social = get_user_meta($author_id, 'news_schema_author_sameas', true);
        if (!empty($author_social)) {
            $sameas_urls = array_filter(array_map('trim', explode("\n", $author_social)));
            if (!empty($sameas_urls)) {
                $schema['sameAs'] = $sameas_urls;
            }
        }
        
        return $schema;
    }
    
    private function get_website_schema() {
        return array(
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'description' => get_bloginfo('description'),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => home_url('/?s={search_term_string}')
                ),
                'query-input' => 'required name=search_term_string'
            )
        );
    }
    
    private function get_breadcrumb_schema() {
        if (!is_single() && !is_category() && !is_tag()) {
            return null;
        }
        
        $breadcrumbs = array();
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => home_url()
        );
        
        if (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $category->name,
                    'item' => get_category_link($category->term_id)
                );
            }
            
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => count($breadcrumbs) + 1,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        } elseif (is_category()) {
            $category = get_queried_object();
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $category->name,
                'item' => get_category_link($category->term_id)
            );
        } elseif (is_tag()) {
            $tag = get_queried_object();
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $tag->name,
                'item' => get_tag_link($tag->term_id)
            );
        }
        
        return array(
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
    }
    
    private function get_webpage_schema() {
        global $post;
        
        if (is_home() || is_front_page()) {
            $current_url = home_url();
        } elseif (is_category()) {
            $category = get_queried_object();
            $current_url = get_category_link($category->term_id);
        } elseif (is_tag()) {
            $tag = get_queried_object();
            $current_url = get_tag_link($tag->term_id);
        } else {
            $current_url = wp_get_canonical_url();
        }
        
        $schema = array(
            '@type' => 'WebPage',
            'name' => wp_get_document_title(),
            'url' => esc_url($current_url),
            'description' => get_bloginfo('description'),
            'inLanguage' => 'en'
        );
        
        if (is_single() || is_page()) {
            $schema['@type'] = 'ItemPage';
            if ($post) {
                $schema['description'] = wp_strip_all_tags(get_the_excerpt());
                $schema['datePublished'] = get_the_date('c');
                $schema['dateModified'] = get_the_modified_date('c');
                
                if (is_single() && $this->options['enable_newsarticle']) {
                    $schema['mainEntity'] = array(
                        '@type' => 'NewsArticle',
                        '@id' => get_permalink() . '#article'
                    );
                }
            }
        } elseif (is_category() || is_tag() || is_archive()) {
            $schema['@type'] = 'CollectionPage';
            if (is_category()) {
                $category = get_queried_object();
                $schema['description'] = $category->description ?: 'Articles in ' . $category->name;
            } elseif (is_tag()) {
                $tag = get_queried_object();
                $schema['description'] = $tag->description ?: 'Articles tagged with ' . $tag->name;
            }
        } elseif (is_home() || is_front_page()) {
            $schema['@type'] = 'WebPage';
            $schema['description'] = get_bloginfo('description');
        }
        
        if ((is_single() || is_category() || is_tag()) && $this->options['enable_breadcrumbs']) {
            $breadcrumb_id = is_single() ? get_permalink() . '#breadcrumb' : $current_url . '#breadcrumb';
            $schema['breadcrumb'] = array(
                '@type' => 'BreadcrumbList',
                '@id' => $breadcrumb_id
            );
        }
        
        if ($this->options['enable_organization']) {
            $schema['publisher'] = array(
                '@type' => $this->options['organization_type'],
                '@id' => $this->options['organization_url'] . '#organization'
            );
        }
        
        return $schema;
    }
    
    private function get_navigation_schema() {
        $nav_menus = wp_get_nav_menus();
        $navigation_elements = array();
        
        foreach ($nav_menus as $menu) {
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            if (!$menu_items) continue;
            
            $position = 1;
            foreach ($menu_items as $item) {
                if ($item->menu_item_parent == 0) {
                    $navigation_elements[] = array(
                        '@type' => 'SiteNavigationElement',
                        'name' => $item->title,
                        'url' => $item->url,
                        'position' => $position++
                    );
                }
            }
            
            break;
        }
        
        return $navigation_elements;
    }
    
    public function add_admin_menu() {
        add_options_page(
            'News Schema Markup Settings',
            'News Schema',
            'manage_options',
            'news-schema-markup',
            array($this, 'options_page')
        );
    }
    
    public function settings_init() {
        register_setting('news_schema', 'news_schema_options');
        
        add_settings_section(
            'news_schema_organization_section',
            'Organization Settings',
            null,
            'news_schema'
        );
        
        add_settings_field(
            'organization_name',
            'Organization Name',
            array($this, 'organization_name_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'organization_url',
            'Organization URL',
            array($this, 'organization_url_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'organization_logo',
            'Organization Logo URL',
            array($this, 'organization_logo_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'organization_type',
            'Organization Type',
            array($this, 'organization_type_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'organization_sameas',
            'Organization Social Profiles',
            array($this, 'organization_sameas_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'organization_founding_date',
            'Founding Date',
            array($this, 'organization_founding_date_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'organization_founder_name',
            'Founder Name',
            array($this, 'organization_founder_name_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'organization_founder_sameas',
            'Founder Social Profiles',
            array($this, 'organization_founder_sameas_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'contact_telephone',
            'Contact Telephone',
            array($this, 'contact_telephone_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_field(
            'contact_email',
            'Contact Email',
            array($this, 'contact_email_render'),
            'news_schema',
            'news_schema_organization_section'
        );
        
        add_settings_section(
            'news_schema_features_section',
            'Feature Settings',
            null,
            'news_schema'
        );
        
        $features = array(
            'enable_newsarticle' => 'Enable NewsArticle Schema',
            'enable_organization' => 'Enable Organization Schema',
            'enable_person' => 'Enable Person Schema',
            'enable_website' => 'Enable WebSite Schema',
            'enable_breadcrumbs' => 'Enable Breadcrumb Schema',
            'enable_webpage' => 'Enable WebPage Schema',
            'enable_navigation' => 'Enable Navigation Schema'
        );
        
        foreach ($features as $key => $label) {
            add_settings_field(
                $key,
                $label,
                array($this, 'checkbox_render'),
                'news_schema',
                'news_schema_features_section',
                array('key' => $key)
            );
        }
    }
    
    public function organization_name_render() {
        echo '<input type="text" name="news_schema_options[organization_name]" value="' . esc_attr($this->options['organization_name']) . '" class="regular-text" />';
    }
    
    public function organization_url_render() {
        echo '<input type="url" name="news_schema_options[organization_url]" value="' . esc_attr($this->options['organization_url']) . '" class="regular-text" />';
    }
    
    public function organization_logo_render() {
        echo '<input type="url" name="news_schema_options[organization_logo]" value="' . esc_attr($this->options['organization_logo']) . '" class="regular-text" />';
        echo '<p class="description">Enter the full URL to your organization\'s logo image.</p>';
    }
    
    public function organization_type_render() {
        $types = array(
            'NewsMediaOrganization' => 'News Media Organization',
            'Organization' => 'Organization',
            'Corporation' => 'Corporation'
        );
        
        echo '<select name="news_schema_options[organization_type]">';
        foreach ($types as $value => $label) {
            $selected = selected($this->options['organization_type'], $value, false);
            echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    public function organization_sameas_render() {
        $value = isset($this->options['organization_sameas']) ? $this->options['organization_sameas'] : '';
        echo '<textarea name="news_schema_options[organization_sameas]" rows="5" class="large-text">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">Enter one URL per line for your organization\'s social media profiles, Wikipedia page, etc.<br>';
        echo 'Example:<br>https://twitter.com/yourorg<br>https://facebook.com/yourorg<br>https://linkedin.com/company/yourorg</p>';
    }
    
    public function organization_founding_date_render() {
        $value = isset($this->options['organization_founding_date']) ? $this->options['organization_founding_date'] : '';
        echo '<input type="date" name="news_schema_options[organization_founding_date]" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">Enter the date your organization was founded.</p>';
    }
    
    public function organization_founder_name_render() {
        $value = isset($this->options['organization_founder_name']) ? $this->options['organization_founder_name'] : '';
        echo '<input type="text" name="news_schema_options[organization_founder_name]" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">Enter the name of your organization\'s founder.</p>';
    }
    
    public function organization_founder_sameas_render() {
        $value = isset($this->options['organization_founder_sameas']) ? $this->options['organization_founder_sameas'] : '';
        echo '<textarea name="news_schema_options[organization_founder_sameas]" rows="3" class="large-text">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">Enter one URL per line for the founder\'s social media profiles.<br>';
        echo 'Example:<br>https://twitter.com/foundername<br>https://linkedin.com/in/foundername</p>';
    }
    
    public function contact_telephone_render() {
        $value = isset($this->options['contact_telephone']) ? $this->options['contact_telephone'] : '';
        echo '<input type="tel" name="news_schema_options[contact_telephone]" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">Enter your organization\'s contact telephone number (e.g., +1-555-123-4567).</p>';
    }
    
    public function contact_email_render() {
        $value = isset($this->options['contact_email']) ? $this->options['contact_email'] : '';
        echo '<input type="email" name="news_schema_options[contact_email]" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">Enter your organization\'s contact email address.</p>';
    }
    
    public function checkbox_render($args) {
        $key = $args['key'];
        $checked = checked($this->options[$key], true, false);
        echo '<input type="checkbox" name="news_schema_options[' . esc_attr($key) . ']" value="1" ' . $checked . ' />';
    }
    
    public function options_page() {
        ?>
        <div class="wrap">
            <h1>News Schema Markup Settings</h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('news_schema');
                do_settings_sections('news_schema');
                submit_button();
                ?>
            </form>
            
            <div class="card">
                <h2>About This Plugin</h2>
                <p>This plugin automatically adds schema.org structured data markup to your news website, including:</p>
                <ul>
                    <li><strong>NewsArticle:</strong> Added to individual posts</li>
                    <li><strong>Organization:</strong> Publisher information with sameAs profiles</li>
                    <li><strong>Person:</strong> Author information with sameAs profiles</li>
                    <li><strong>WebSite:</strong> Site-wide information with search functionality</li>
                    <li><strong>WebPage:</strong> Individual page information (ItemPage, CollectionPage)</li>
                    <li><strong>BreadcrumbList:</strong> Navigation structure</li>
                    <li><strong>SiteNavigationElement:</strong> Main navigation menu</li>
                </ul>
                <p>The markup is added as JSON-LD with @graph structure in the &lt;head&gt; section of your pages, following Google's recommended best practices.</p>
            </div>
        </div>
        <?php
    }
    
    public function add_author_fields($user) {
        ?>
        <h3>Schema Markup Settings</h3>
        <table class="form-table">
            <tr>
                <th><label for="news_schema_author_sameas">Social Media Profiles</label></th>
                <td>
                    <textarea name="news_schema_author_sameas" id="news_schema_author_sameas" rows="5" class="regular-text"><?php echo esc_textarea(get_user_meta($user->ID, 'news_schema_author_sameas', true)); ?></textarea>
                    <p class="description">Enter one URL per line for your social media profiles.<br>Example:<br>https://twitter.com/yourusername<br>https://linkedin.com/in/yourprofile</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_author_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        
        if (isset($_POST['news_schema_author_sameas'])) {
            update_user_meta($user_id, 'news_schema_author_sameas', sanitize_textarea_field($_POST['news_schema_author_sameas']));
        }
    }
    
    public function add_post_meta_boxes() {
        add_meta_box(
            'news_schema_post_settings',
            'News Schema Settings',
            array($this, 'post_meta_box_callback'),
            'post',
            'side',
            'default'
        );
    }
    
    public function post_meta_box_callback($post) {
        wp_nonce_field('news_schema_post_meta', 'news_schema_post_meta_nonce');
        
        $is_free = get_post_meta($post->ID, '_news_schema_is_accessible_for_free', true);
        $is_free = ($is_free !== '') ? (bool)$is_free : true; // Default to true
        
        echo '<label for="news_schema_is_free">';
        echo '<input type="checkbox" id="news_schema_is_free" name="news_schema_is_free" value="1" ' . checked($is_free, true, false) . ' />';
        echo ' Article is accessible for free</label>';
        echo '<p class="description">Uncheck if this article requires subscription or payment to read.</p>';
    }
    
    public function save_post_meta($post_id) {
        if (!isset($_POST['news_schema_post_meta_nonce']) || !wp_verify_nonce($_POST['news_schema_post_meta_nonce'], 'news_schema_post_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $is_free = isset($_POST['news_schema_is_free']) ? 1 : 0;
        update_post_meta($post_id, '_news_schema_is_accessible_for_free', $is_free);
    }
}

new NewsSchemaMarkup();
?>