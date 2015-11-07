=== Plugin Name ===
Contributors: xipasduarte
Tags: images, media, resize, original
Requires at least: 4.0.0
Tested up to: 4.3.1
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Change an attachment's original size before saving to the database on WordPress.

== Description ==

Change an attachment's original size before saving to the database on WordPress. This is the main goal of this plugin. When one of the filters has a function attached to its hook the plugin intercepts the uploads and does its thing.

Right now it's only possible to use the plugin by coding, meaning that a little knowledge is required. This knowledge is not extensive, and relates only to the use of the `add_filter()` function.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `wp-resize-original` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Choose one of the modes below

### Manual mode

In this mode you specify the maximum dimensions the images may have using the filter hook `wp_resize_original_dimensions`.

```php
add_filter( 'wp_resize_original_dimensions', function( $dims ) {
	return array( 2300, 1300 );
}, 10, 1 );
```

**Atention:** Right now, as the plugin stands, if you set the dimension bellow one of your image sizes you will essentially hinder them useless, as the image will never be big enough to make the cut.

### Automatic mode

Probably the safest mode. With this the plugin calculates the maximum dimensions you require to make all the image cuts defined (crop is taken into account). Like before the functionality is activated by means of a filter hook `wp_resize_original_auto`.

```php
add_filter( 'wp_resize_original_auto', function() {
	return true;
}, 10, 1 );
```

== Changelog ==

= 1.0.0 =
* Initial release with the simplest functionality.
