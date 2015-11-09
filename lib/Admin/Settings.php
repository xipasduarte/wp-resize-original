<?php
/**
 * The main functionality of the plugin.
 *
 * @since	  1.0.0
 *
 * @package	wp-resize-original
 * @subpackage wp-resize-original/admin
 */

namespace xipasduarte\WPResizeOriginal\Admin;

/**
 * The main functionality of the plugin.
 *
 * Defines all the methods to perform the original image resizing, both to the
 * user's requested dimensions and on auto mode.
 *
 * @package	wp-resize-original
 * @subpackage wp-resize-original/admin
 * @author	 Pedro Duarte <xipasduarte@gmail.com>
 */
class Settings {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var	Plugin $plugin This plugin's instance.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Register the plugin's settings.
	 *
	 * @since	1.0.0
	 */
	public function register_settings() {

		\register_setting( 'wp_resize_original', 'wp_resize_original', [ $this, 'sanitize_options' ] );

		\add_settings_section(
			'manual_settings',
			\__( 'WP Resize Original Plugin', $this->plugin->get_plugin_name() ),
			[ $this, 'manual_settings_callback' ],
			'media'
		);

		\add_settings_field(
			'dimensions',
			\__( 'Manual Mode', $this->plugin->get_plugin_name() ),
			[ $this, 'dimensions_callback' ],
			'media',
			'manual_settings'
		);

	}

	/**
	 * Sanitize plugin options.
	 *
	 * @param array $options Options to sanitize.
	 **/
	public function sanitize_options( $options ) {
		return $options;
	}

	/**
	 * Section text: manual_settings.
	 *
	 * @since	1.0.0
	 */
	public function manual_settings_callback() {
		printf( '<p>%s</p><p>%s</p>',
			\__( 'The automatic mode is enabled by default. It works by determining the minimum required size to allow WordPress to generate all your defined thumbnail sizes.<br>If you want more control use the manual mode.', $this->plugin->get_plugin_name() ),
			\__( 'To use the manual mode all of the settings below need to be filled in.', $this->plugin->get_plugin_name() )
		);
	}

	/**
	 * Field: width.
	 *
	 * @since	1.0.0
	 */
	public function dimensions_callback() {
		// Width field.
		printf(
			'<label>%s&nbsp;<input type="number" id="width" class="small-text" name="wp_resize_original[width]" value="%s" /></label>',
			\__( 'Width', $this->plugin->get_plugin_name() ),
			isset( $this->options['width'] ) ? esc_attr( $this->options['width'] ) : ''
		);

		// Height field.
		printf(
			'&nbsp;<label>%s&nbsp;<input type="number" id="width" class="small-text" name="wp_resize_original[height]" value="%s" /></label>',
			\__( 'Height', $this->plugin->get_plugin_name() ),
			isset( $this->options['height'] ) ? esc_attr( $this->options['height'] ) : ''
		);

		printf(
			'<p>%s<br>%s</p>',
			\__( 'Specify the desired dimensions to which all new uploads will be resized before thumbnail generation.', $this->plugin->get_plugin_name() ),
			\__( 'Images with initial dimensions lower than the ones specified are not resized.', $this->plugin->get_plugin_name() )
		);
	}
}
