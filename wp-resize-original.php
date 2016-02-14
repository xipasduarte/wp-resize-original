<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://github.com/xipasduarte/wp-resize-original
 * @since             1.0.0
 * @package           wp-resize-original
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Resize Original
 * Plugin URI:        http://github.com/xipasduarte/wp-resize-original
 * Description:       Change an attachment's original size before saving to the database on WordPress.
 * Version:           1.0.1
 * Author:            Pedro Duarte
 * Author URI:        http://xipasduarte.github.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-resize-original
 * Domain Path:       /languages
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in lib/Activator.php
 */
\register_activation_hook( __FILE__, '\xipasduarte\WPResizeOriginal\Activator::activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in lib/Deactivator.php
 */
\register_deactivation_hook( __FILE__, '\xipasduarte\WPResizeOriginal\Deactivator::deactivate' );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
\add_action( 'plugins_loaded', function() {
	$plugin = new \xipasduarte\WPResizeOriginal\Plugin();
	$plugin->run();
} );
