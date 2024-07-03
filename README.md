# MetaEnhancer

MetaEnhancer is a WordPress plugin designed to enhance your website's SEO capabilities by allowing you to easily add SEO meta tags to posts and pages. Additionally, it provides a feature to customize the admin footer message in the WordPress dashboard.

## Features

- Add default SEO meta tags (title, description, keywords) through a settings page.
- Customize SEO meta tags for individual posts and pages via meta boxes in the editor.
- Customize the admin footer message displayed in the WordPress dashboard.
- Outputs the SEO meta tags in the head section of your site's HTML.

## Installation

1. Download the plugin.
2. Upload the plugin files to the `/wp-content/plugins/metaenhancer` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.

## Usage

### Settings Page

1. Navigate to `Settings > Custom SEO & Footer`.
2. Input default meta tags for title, description, and keywords.
3. Customize the admin footer message.

### Adding Custom SEO Meta Tags to Posts/Pages

1. Edit any post or page.
2. Find the 'SEO Meta Tags' meta box.
3. Input custom meta tags for title, description, and keywords.
4. Save or update the post/page.

### Customizing Admin Footer Message

1. Navigate to `Settings > Custom SEO & Footer`.
2. Input your desired custom footer message.
3. Save the changes.

## Hooks and Filters

- `admin_footer_text`: Customize the admin footer text.
- `wp_head`: Output the custom SEO meta tags in the head section of your site's HTML.

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/YourFeature`).
3. Commit your changes (`git commit -am 'Add some feature'`).
4. Push to the branch (`git push origin feature/YourFeature`).
5. Create a new Pull Request.

## Support

For any issues or feature requests, please open an issue on the [GitHub repository](https://github.com/WP-API/Basic-Auth).

## License

This plugin is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Author

- WordPress API Team
- [Author URI](https://nymul-islam-moon.com/)
