=== MetaEnhancer ===

Contributors:      wordpressdotorg
Requires at least: 6.3
Tested up to:      6.4
Requires PHP:      7.0
Stable tag:        1.0.1
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Tags:              seo, meta tags, footer customization, admin footer

MetaEnhancer adds SEO meta tags to posts/pages and customizes the admin footer message in the WordPress dashboard.

== Description ==

MetaEnhancer is a tool for enhancing your website's SEO capabilities and customizing the admin footer message in the WordPress dashboard. With this plugin, you can easily add SEO meta tags to your posts and pages.

== Features ==

- Add default SEO meta tags (title, description, keywords) through a settings page.
- Customize SEO meta tags for individual posts and pages via meta boxes in the editor.
- Customize the admin footer message displayed in the WordPress dashboard.
- Outputs the SEO meta tags in the head section of your site's HTML.

== Installation ==

= Installation from within WordPress =

1. Visit **Plugins > Add New**.
2. Search for **MetaEnhancer**.
3. Install and activate the MetaEnhancer plugin.

= Manual installation =

1. Upload the entire `metaenhancer` folder to the `/wp-content/plugins/` directory.
2. Visit **Plugins**.
3. Activate the MetaEnhancer plugin.

== Usage ==

= Settings Page =

1. Navigate to `Settings > WP-MetaEnhancer`.
2. Input default meta tags for title, description, and keywords.
3. Customize the admin footer message.

= Adding Custom SEO Meta Tags to Posts/Pages =

1. Edit any post or page.
2. Find the 'SEO Meta Tags' meta box.
3. Input custom meta tags for title, description, and keywords.
4. Save or update the post/page.

= Customizing Admin Footer Message =

1. Navigate to `Settings > WP-MetaEnhancer`.
2. Input your desired custom footer message.
3. Save the changes.

== Hooks and Filters ==

- `admin_footer_text`: Customize the admin footer text.
- `wp_head`: Output the custom SEO meta tags in the head section of your site's HTML.

== Contributing ==

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/YourFeature`).
3. Commit your changes (`git commit -am 'Add some feature'`).
4. Push to the branch (`git push origin feature/YourFeature`).
5. Create a new Pull Request.

== Support ==

For any issues or feature requests, please open an issue on the [GitHub repository](https://github.com/nymul-islam-moon/MetaEnhancher).

== License ==

This plugin is licensed under the GPLv2 or later.

== Author ==

- Nymul Islam
- [Author URI](https://nymul-islam-moon.com/)

== Changelog ==

= 1.0.1 =

* Initial release.
