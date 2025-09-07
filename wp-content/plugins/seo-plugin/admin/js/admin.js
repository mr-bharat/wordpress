jQuery(document).ready(function($) {
    'use strict';
    
    // Character count functionality
    function updateCharacterCount(element, maxLength, countElement) {
        const currentLength = element.val().length;
        const remaining = maxLength - currentLength;
        
        countElement.text(currentLength);
        
        // Update count element classes based on character usage
        countElement.removeClass('over-limit near-limit');
        const countContainer = countElement.closest('.character-count');
        countContainer.removeClass('over-limit near-limit');
        
        if (currentLength > maxLength) {
            countContainer.addClass('over-limit');
        } else if (currentLength > maxLength * 0.9) {
            countContainer.addClass('near-limit');
        }
    }
    
    // Initialize character counters
    const counters = [
        {
            input: '#news_seo_meta_description',
            counter: '#meta-desc-count',
            maxLength: 160
        },
        {
            input: '#news_seo_og_title',
            counter: '#og-title-count',
            maxLength: 60
        },
        {
            input: '#news_seo_og_description',
            counter: '#og-desc-count',
            maxLength: 160
        },
        {
            input: '#news_seo_twitter_title',
            counter: '#twitter-title-count',
            maxLength: 70
        },
        {
            input: '#news_seo_twitter_description',
            counter: '#twitter-desc-count',
            maxLength: 200
        }
    ];
    
    counters.forEach(function(counter) {
        const inputElement = $(counter.input);
        const countElement = $(counter.counter);
        
        if (inputElement.length && countElement.length) {
            // Initialize count
            updateCharacterCount(inputElement, counter.maxLength, countElement);
            
            // Update count on input
            inputElement.on('input keyup paste', function() {
                updateCharacterCount(inputElement, counter.maxLength, countElement);
                updatePreview();
            });
        }
    });
    
    // SEO Preview functionality
    function updatePreview() {
        const postTitle = $('#title').val() || $('#news_seo_og_title').val() || 'Your Post Title';
        const metaDescription = $('#news_seo_meta_description').val() || 'Your post description will appear here...';
        const ogTitle = $('#news_seo_og_title').val() || postTitle;
        const ogDescription = $('#news_seo_og_description').val() || metaDescription;
        
        // Update Google preview
        $('#preview-title').text(postTitle);
        $('#preview-description').text(metaDescription);
        
        // Update social preview
        $('#social-preview-title').text(ogTitle);
        $('#social-preview-description').text(ogDescription);
    }
    
    // Initialize preview
    updatePreview();
    
    // Update preview when title changes (WordPress post title)
    $('#title').on('input keyup paste', function() {
        updatePreview();
    });
    
    // Media uploader for default OG image
    $('#upload_og_image').on('click', function(e) {
        e.preventDefault();
        
        const mediaUploader = wp.media({
            title: 'Select Default Open Graph Image',
            button: {
                text: 'Use This Image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#default_og_image').val(attachment.url);
        });
        
        mediaUploader.open();
    });
    
    // Settings page enhancements
    if ($('body').hasClass('settings_page_news-seo-settings')) {
        // Add class to body for specific styling
        $('body').addClass('news-seo-settings');
        
        // Toggle functionality for sections
        $('.news-seo-toggle h4').on('click', function() {
            const section = $(this).next();
            const indicator = $(this).find('.toggle-indicator');
            
            section.slideToggle();
            indicator.text(indicator.text() === '▼' ? '▶' : '▼');
        });
        
        // Form validation
        $('#submit').on('click', function(e) {
            let hasError = false;
            
            // Validate Twitter handle
            const twitterHandle = $('input[name="twitter_site"]').val();
            if (twitterHandle && twitterHandle.indexOf('@') !== -1) {
                alert('Twitter handle should not include the @ symbol.');
                hasError = true;
            }
            
            // Validate sitemap limit
            const sitemapLimit = $('input[name="news_sitemap_limit"]').val();
            if (sitemapLimit && (sitemapLimit < 1 || sitemapLimit > 50000)) {
                alert('Sitemap limit must be between 1 and 50000.');
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault();
            }
        });
        
        // Show/hide dependent fields
        function toggleDependentFields() {
            const enableSitemap = $('input[name="enable_news_sitemap"]').is(':checked');
            const enablePagination = $('input[name="enable_sitemap_pagination"]').is(':checked');
            
            // OG image field is always visible (OG tags always enabled)
            // Twitter site field is always visible (Twitter cards always enabled)
            
            // Toggle sitemap fields
            $('input[name="enable_categories_sitemap"]').closest('tr').toggle(enableSitemap);
            $('input[name="enable_tags_sitemap"]').closest('tr').toggle(enableSitemap);
            
            // Toggle tags minimum posts field based on tags sitemap setting only
            const enableTagsSitemap = $('input[name="enable_tags_sitemap"]').is(':checked');
            $('.tags-minimum-posts-setting').toggle(enableTagsSitemap);
            $('input[name="news_sitemap_limit"]').closest('tr').toggle(enableSitemap);
            
            // Toggle pagination fields
            $('.pagination-setting').toggle(enablePagination);
            
            // General meta tag fields are always visible (no toggle needed)
            // Meta description is always enabled
            // Open Graph is always enabled  
            // Twitter Cards are always enabled
        }
        
        // Initialize dependent field visibility
        toggleDependentFields();
        
        // Update dependent field visibility when checkboxes change
        $('input[name="enable_news_sitemap"], input[name="enable_sitemap_pagination"], input[name="enable_tags_sitemap"]').on('change', toggleDependentFields);
    }
    
    // Meta box enhancements
    if ($('#news_seo_meta_box').length) {
        // Add class to meta box for specific styling
        $('#news_seo_meta_box').addClass('news-seo-meta-box');
        
        // Auto-resize textareas
        $('textarea').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Quick fill buttons
        if ($('#content').length) {
            const quickFillButton = $('<button type="button" class="button button-small" style="margin-left: 10px;">Quick Fill</button>');
            
            $('#news_seo_meta_description').after(quickFillButton.clone().on('click', function(e) {
                e.preventDefault();
                const content = $('#content').val() || '';
                const stripped = $('<div>').html(content).text();
                const excerpt = stripped.substring(0, 150) + (stripped.length > 150 ? '...' : '');
                $('#news_seo_meta_description').val(excerpt).trigger('input');
            }));
        }
        
        // Generate keywords from categories and tags
        $('#generate-keywords').on('click', function(e) {
            e.preventDefault();
            
            const categories = [];
            const tags = [];
            
            // Get categories
            $('input[name="post_category[]"]:checked').each(function() {
                const label = $(this).closest('label').text().trim();
                if (label) {
                    categories.push(label);
                }
            });
            
            // Get tags (if tag metabox exists)
            $('#tagsdiv-post_tag .tagchecklist span a').each(function() {
                const tagText = $(this).text().replace('×', '').trim();
                if (tagText) {
                    tags.push(tagText);
                }
            });
            
            // Alternative: get tags from input field
            const tagInput = $('#new-tag-post_tag').val();
            if (tagInput) {
                tags.push(...tagInput.split(',').map(tag => tag.trim()));
            }
            
            const allKeywords = [...categories, ...tags];
            const suggestions = [...new Set(allKeywords)].join(', ');
            
            if (suggestions) {
                $('#news_seo_keywords').val(suggestions);
            } else {
                alert('No categories or tags found. Please add categories/tags first or enter keywords manually.');
            }
        });
        
        // Keywords suggestion for news keywords (simple implementation)
        $('#news_seo_news_keywords').after('<button type="button" class="button button-small" id="suggest-news-keywords" style="margin-left: 10px;">Suggest News Keywords</button>');
        
        $('#suggest-news-keywords').on('click', function(e) {
            e.preventDefault();
            
            const title = $('#title').val() || '';
            const content = $('#content').val() || '';
            const categories = [];
            
            // Get categories
            $('input[name="post_category[]"]:checked').each(function() {
                const label = $(this).closest('label').text().trim();
                if (label) {
                    categories.push(label.toLowerCase());
                }
            });
            
            // Simple keyword extraction (you might want to improve this)
            const words = (title + ' ' + content).toLowerCase()
                .replace(/[^\w\s]/g, ' ')
                .split(/\s+/)
                .filter(word => word.length > 3)
                .slice(0, 10);
            
            const suggestions = [...new Set([...categories, ...words])].slice(0, 8).join(', ');
            
            if (suggestions) {
                $('#news_seo_news_keywords').val(suggestions);
            } else {
                alert('Unable to generate keyword suggestions. Please enter keywords manually.');
            }
        });
    }
    
    // Add status indicators
    function addStatusIndicators() {
        const indicators = [
            {
                field: '#news_seo_meta_description',
                optimal: { min: 120, max: 160 },
                warning: { min: 100, max: 180 }
            },
            {
                field: '#news_seo_og_title',
                optimal: { min: 30, max: 60 },
                warning: { min: 25, max: 65 }
            }
        ];
        
        indicators.forEach(function(indicator) {
            const field = $(indicator.field);
            if (field.length) {
                const statusElement = $('<span class="status-indicator"></span>');
                field.after(statusElement);
                
                function updateStatus() {
                    const length = field.val().length;
                    statusElement.removeClass('good warning error');
                    
                    if (length >= indicator.optimal.min && length <= indicator.optimal.max) {
                        statusElement.addClass('good');
                    } else if (length >= indicator.warning.min && length <= indicator.warning.max) {
                        statusElement.addClass('warning');
                    } else if (length > 0) {
                        statusElement.addClass('error');
                    }
                }
                
                field.on('input keyup paste', updateStatus);
                updateStatus();
            }
        });
    }
    
    // Initialize status indicators
    addStatusIndicators();
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save (if on post edit screen)
        if ((e.ctrlKey || e.metaKey) && e.which === 83 && $('#publish').length) {
            e.preventDefault();
            $('#publish').click();
        }
    });
    
    // Add tooltips for help text
    $('[title]').each(function() {
        const $this = $(this);
        const title = $this.attr('title');
        $this.removeAttr('title');
        
        $this.hover(
            function() {
                $this.append('<div class="tooltip">' + title + '</div>');
            },
            function() {
                $this.find('.tooltip').remove();
            }
        );
    });
});

// Add some utility functions
window.NewsSEO = {
    
    // Function to validate meta data
    validateMetaData: function() {
        const results = {
            title: {
                length: $('#title').val().length,
                status: 'good'
            },
            description: {
                length: $('#news_seo_meta_description').val().length,
                status: 'good'
            }
        };
        
        // Validate title length
        if (results.title.length < 30 || results.title.length > 60) {
            results.title.status = results.title.length > 0 ? 'warning' : 'error';
        }
        
        // Validate description length
        if (results.description.length < 120 || results.description.length > 160) {
            results.description.status = results.description.length > 0 ? 'warning' : 'error';
        }
        
        return results;
    },
    
    // Function to generate slug from title
    generateSlug: function(title) {
        return title.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    },
    
    // Function to count words
    countWords: function(text) {
        return text.trim().split(/\s+/).filter(word => word.length > 0).length;
    }
};