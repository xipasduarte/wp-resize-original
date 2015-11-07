# WordPress Resize Original Plugin

Change an attachment's original size before saving to the database on WordPress. This is the main goal of this plugin. When one of the filters has a function attached to its hook the plugin intercepts the uploads and does its thing.

Right now it's only possible to use the plugin by coding, meaning that a little knowledge is required. This knowledge is not extensive, and relates only to the use of the `add_filter()` function.

## Usage

Two modes exist to trigger the resize of the original image. Either you would like to set the dimensions yourself (manual mode) or you may choose an automatic mode.

### Manual mode

In this mode you specify the maximum dimensions the images may have using the filter hook `wp_resize_original_dimensions`.

```php
add_filter( 'wp_resize_original_dimensions', function( $dims ) {
	return array( 2300, 1300 );
}, 10, 1 );
```

**Atention:** Right now, as the plugin stands, if you set the dimension below one of your image sizes you will essentially hinder them useless, as the image will never be big enough to make the cut.

### Automatic mode

Probably the safest mode. With this the plugin calculates the maximum dimensions you require to make all the image cuts defined (crop is taken into account). Like before the functionality is activated by means of a filter hook `wp_resize_original_auto`.

```php
add_filter( 'wp_resize_original_auto', function() {
	return true;
}, 10, 1 );
```


## License

The WordPress Resize Original Plugin is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.
