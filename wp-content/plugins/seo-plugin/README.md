# News SEO Plugin

A comprehensive WordPress SEO plugin specifically designed for news websites, providing essential meta tags, social media optimization, and Google News XML sitemap functionality.

## Features

### ✅ Meta Tags & SEO
- **Meta Descriptions**: Automatic and custom meta descriptions for posts and pages
- **Open Graph Tags**: Complete Open Graph implementation for Facebook and social media sharing
- **Twitter Cards**: Twitter Card meta tags for enhanced Twitter sharing
- **News Keywords**: Google News specific meta keywords for posts
- **Article Meta Tags**: Publication and modification dates for articles

### ✅ News XML Sitemap
- **Google News Compatible**: Generates XML sitemap specifically for Google News
- **Automatic Updates**: Sitemap updates automatically when new posts are published
- **Configurable**: Choose post types, limits, and other sitemap settings
- **Recent Content Focus**: Automatically includes content from the last 2 days (Google News requirement)

### ✅ Admin Interface
- **User-Friendly Settings**: Easy-to-use admin panel with all configuration options
- **Meta Boxes**: Individual post/page SEO settings with real-time preview
- **Character Counters**: Live character counting for optimal meta tag lengths
- **SEO Preview**: Google search result and social media preview
- **Media Integration**: Built-in WordPress media library integration for images

## Installation

1. Upload the `news-seo-plugin` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Settings' > 'News SEO' to configure the plugin
4. Start optimizing your content!

## Configuration

### Plugin Settings (Settings > News SEO)

#### Basic SEO Settings
- **Meta Description**: Enable/disable automatic meta descriptions
- **Open Graph**: Enable/disable Open Graph meta tags
- **Twitter Cards**: Enable/disable Twitter Card meta tags
- **News Keywords**: Enable/disable news_keywords meta tag for Google News

#### Social Media Settings
- **Default Open Graph Image**: Set a default image for posts without featured images
- **Twitter Site Handle**: Your website's Twitter handle (without @)

#### News Sitemap Settings
- **Enable News Sitemap**: Turn on/off the XML sitemap functionality
- **Post Types**: Select which post types to include in the sitemap
- **Sitemap Limit**: Maximum number of posts to include (Google recommends max 1000)

### Individual Post Settings

For each post/page, you can customize:
- **Meta Description**: Custom description for search engines (160 chars max)
- **Open Graph Title**: Custom title for social sharing (60 chars max)
- **Open Graph Description**: Custom description for social sharing (160 chars max)
- **Twitter Title**: Custom title for Twitter (70 chars max)
- **Twitter Description**: Custom description for Twitter (200 chars max)
- **News Keywords**: Comma-separated keywords for Google News (posts only)

## URLs and Access Points

### News Sitemap
- **URL**: `yoursite.com/news-sitemap.xml`
- **Google News Submission**: Submit this URL to Google News Publisher Center
- **Automatic Updates**: Updates when new posts are published

### Admin Pages
- **Settings**: WordPress Admin > Settings > News SEO
- **Post Meta**: Available in post/page edit screens as "News SEO Settings" meta box

## Features Excluded (Available in WordPress Core)

This plugin focuses only on features NOT provided by WordPress core:
- ❌ Title tags (handled by WordPress/themes)
- ❌ Robots meta tags (handled by WordPress)
- ❌ UTF-8 encoding (handled by WordPress)
- ❌ Viewport meta tags (handled by themes)
- ❌ Basic canonical URLs (handled by WordPress)

## Google News Optimization

This plugin is specifically optimized for news websites with:
- **News XML Sitemap**: Google News compatible sitemap format
- **News Keywords**: Specific meta tag for Google News
- **Article Dates**: Proper publication and modification date meta tags
- **Fresh Content**: Sitemap automatically includes only recent content (last 2 days)
- **News-Specific Schema**: Ready for news-specific structured data (future enhancement)

## Technical Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Browser**: Modern browsers for admin interface

## File Structure

```
news-seo-plugin/
├── news-seo-plugin.php          # Main plugin file
├── admin/
│   ├── admin-page.php           # Settings page template
│   ├── meta-box.php             # Post meta box template
│   ├── css/
│   │   └── admin.css            # Admin styles
│   └── js/
│       └── admin.js             # Admin JavaScript
└── README.md                    # This file
```

## Usage Examples

### Setting Up for Google News

1. **Enable News Sitemap**: Go to Settings > News SEO and enable "News XML Sitemap"
2. **Configure Post Types**: Select "Posts" (or your news post type) in sitemap settings
3. **Set Keywords**: For each news post, add relevant news keywords in the meta box
4. **Submit to Google**: Submit `yoursite.com/news-sitemap.xml` to Google News Publisher Center

### Social Media Optimization

1. **Enable Open Graph & Twitter Cards**: Check both options in plugin settings
2. **Set Default Image**: Upload a default image for posts without featured images
3. **Add Twitter Handle**: Enter your site's Twitter handle
4. **Customize Per Post**: Use meta boxes to customize social sharing for each post

### Meta Description Best Practices

- **Length**: Keep between 120-160 characters for optimal display
- **Unique**: Write unique descriptions for each post/page
- **Compelling**: Write descriptions that encourage clicks
- **Keywords**: Include target keywords naturally

## Troubleshooting

### Sitemap Issues
- **Not Accessible**: Check permalink settings and flush rewrite rules
- **Empty Sitemap**: Ensure you have published posts from the last 2 days
- **Wrong Post Types**: Check sitemap post type settings

### Meta Tags Not Showing
- **Theme Conflicts**: Some themes may override meta tags
- **Plugin Conflicts**: Deactivate other SEO plugins that might conflict
- **Cache Issues**: Clear caching plugins after making changes

### Admin Interface Issues
- **JavaScript Errors**: Ensure WordPress media library is properly loaded
- **Style Issues**: Clear browser cache and check for theme CSS conflicts

## Support & Development

### Customization
The plugin is built with developer-friendly hooks and can be extended:
- Filter hooks for customizing meta tag output
- Action hooks for adding custom functionality
- Clean, documented code for easy modification

### Performance
- **Lightweight**: Minimal database queries and efficient code
- **Conditional Loading**: Admin assets only load when needed
- **Caching Friendly**: Compatible with WordPress caching plugins

## Version History

### v1.0.0
- Initial release
- Meta description functionality
- Open Graph meta tags
- Twitter Cards support
- News-specific meta tags
- News XML sitemap
- Complete admin interface
- Real-time SEO preview

## License

This plugin is licensed under the GPL v2 or later.

---

**Note**: This plugin is designed to complement WordPress's built-in SEO features, not replace them. It focuses specifically on advanced meta tags and news-specific functionality that WordPress doesn't provide by default.