=== Scope Publisher ===
Contributors: thescopecom
Tags: content-curation
Requires at least: 4.7
Tested up to: 6.4
Stable tag: 1.1.12
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin allows Scope to automatically create a new blog post for content published via Scope.

== Description ==

Please do not attempt to install and use this plugin if you are not a Scope client and have discussed a Wordpress integration.

Scope (https://www.thescope.com/) is a software provider that provides a solution that allows content-curators (our clients) to collect articles (-URLs), comment them and then publish bundles of articles ("selections") to various output channels.

Links that are curated on the Scope platform might appear on thescope.com or on any of the different configured output channels (social media, email newsletters, custom integrations, etc.).

This Wordpress plugin allows Scope to automatically create a new blog post for a published selection on Scope. This plugin can only be used if this specific Wordpress output channel has been configured together with the Scope customer care team.
Most notably, the plugin requires an authentication code that is handed out by Scope.

The blog post will only be created if the output channel "Publish to your website" is explicitly enabled (for each post) on the Scope content curation platform. These posts appear as regular posts and can then be edited and published like any other (hand written) post.

Details about the posts:

The posts will appear in the layout that is configured on the Scope system. All posts will have the custom property "curated_urls", this property contains a comma separated list of all URLs that are curated in the selection. Example: "https://thescope.com,https://wordpress.org".

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to enter the authentication code.

== Changelog ==

= 1.0.0 =
* Initial Version

= 1.1.0 =
* Add post categories support
* Users can now select the desired categories to which the post will automatically be assigned

= 1.1.1 =
* Fix file include issue during update

= 1.1.2 =
* Fix: invalid static method calls

= 1.1.3 =
* Added support for custom blog post property "curated_urls".

= 1.1.4 =
* Added support for setting featured image on blog posts.

= 1.1.5 =
* Added support for setting custom categories (via slug) within selection.

= 1.1.6 =
* Increased supported WP version, updates for server IPs

= 1.1.7 =
* Increased supported WP version, update for rest route registration

= 1.1.8 =
* Increased supported WP version, support for tags and sticky flag

= 1.1.9 =
* Increased supported WP version

= 1.1.10 =
* Increased supported WP version

= 1.1.12 =
* Increased supported WP version

== Upgrade Notice ==
Starting in version 1.1.0 the plugin requires all database tables to have the same storage engine.

== Technical Details ==
The Scope servers will send an HTTP POST request through the WP REST API to create this new post. The post will only be created if the authentication code is correct and the request was sent by a Scope server. Scope will further not push to endpoints that do not have SSL enabled.
