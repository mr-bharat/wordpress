# News Schema Markup WordPress Plugin

A comprehensive WordPress plugin that automatically adds schema.org structured data markup for news websites to improve SEO and search engine visibility.

## Features

### Automatically Added Schema Types

- **NewsArticle**: Added to individual blog posts with complete metadata
- **Organization**: Publisher information with logo support
- **Person**: Author information for articles
- **WebSite**: Site-wide information with search functionality
- **BreadcrumbList**: Navigation structure for better UX
- **ImageObject**: Featured images with dimensions

### Key Capabilities

- **JSON-LD Format**: Uses the recommended JSON-LD format
- **Admin Settings Panel**: Easy configuration through WordPress admin
- **Flexible Organization Types**: Support for NewsMediaOrganization, Organization, and Corporation
- **Feature Toggles**: Enable/disable specific schema types as needed
- **SEO Optimized**: Includes all essential properties for rich snippets

## Installation

1. Upload the `news-schema-markup.php` file to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > News Schema to configure the plugin

## Configuration

### Organization Settings
- **Organization Name**: Your news outlet's name
- **Organization URL**: Your website's URL
- **Organization Logo**: URL to your logo image
- **Organization Type**: Choose from NewsMediaOrganization, Organization, or Corporation

### Feature Settings
Toggle individual schema types on/off:
- NewsArticle Schema
- Organization Schema  
- Person Schema
- WebSite Schema
- Breadcrumb Schema

## Schema Details

### NewsArticle Schema
Includes:
- Headline and description
- Publication and modification dates
- Author information
- Publisher details
- Featured image with dimensions
- Article categories and tags
- Canonical URL

### Organization Schema
Includes:
- Organization name and type
- Website URL
- Logo image

### WebSite Schema
Includes:
- Site name and description
- Search functionality markup
- Homepage URL

### BreadcrumbList Schema
Includes:
- Home page link
- Category link (if applicable)
- Current page link

## Testing Your Schema

After installation, you can test your schema markup using:
- [Google's Rich Results Test](https://search.google.com/test/rich-results)
- [Schema.org Validator](https://validator.schema.org/)
- [Google Search Console](https://search.google.com/search-console)

## Requirements

- WordPress 4.0 or higher
- PHP 5.6 or higher

## License

GPL v2 or later

## Support

This plugin follows WordPress coding standards and best practices for maximum compatibility and performance.