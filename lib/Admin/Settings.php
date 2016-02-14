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

		\register_setting( 'media', 'wp_resize_original', [ $this, 'sanitize_options' ] );

		\add_settings_section(
			'manual_settings',
			\__( 'WP Resize Original Plugin', $this->plugin->get_plugin_name() ),
			[ $this, 'manual_settings_callback' ],
			'media'
		);

		\add_settings_field(
			'wp_resize_original',
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
		// TODO: Needs work for more simplicity.
		if ( ! empty( $options['width'] ) && empty( $options['height'] )
			|| ! empty( $options['height'] ) && empty( $options['width'] ) ) {
			$message = \__(
				'You must provide a value for Width and Height on WP Resize Original.',
				$this->plugin->get_plugin_name()
			);
		} else if ( '0' === $options['width'] || '0' === $options['height'] ) {
			$message = \__(
				'The dimensions for WP Resize Original must all be different from 0 (zero)',
				$this->plugin->get_plugin_name()
			);
		} else {
			$message = '';
		}

		if ( ! empty( $message ) ) {
			add_settings_error(
				'wp_resize_original',
				esc_attr( 'settings_updated' ),
				$message,
				'error'
			);

			$options['width']  = '';
			$options['height'] = '';
		}

		$options['width']  = absint( $options['width'] ) === 0 ? '' : absint( $options['width'] );
		$options['height'] = absint( $options['height'] ) === 0 ? '' : absint( $options['height'] );

		return $options;
	}

	/**
	 * Section text: manual_settings.
	 *
	 * @since	1.0.0
	 */
	public function manual_settings_callback() {
		printf(
			'<p>%s</p><p>%s</p>',
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
		$options = \get_option( 'wp_resize_original' );
		// Width field.
		printf(
			'<label>%s&nbsp;<input type="number" id="width" class="small-text" name="wp_resize_original[width]" step="1" min="0" value="%s" /></label>',
			\__( 'Width', $this->plugin->get_plugin_name() ),
			$options && isset( $options['width'] ) ? $options['width'] : ''
		);

		printf(
			'<label>%s&nbsp;<input type="number" id="height" class="small-text" name="wp_resize_original[height]" step="1" min="0" value="%s" /></label>',
			\__( 'Height', $this->plugin->get_plugin_name() ),
			$options && isset( $options['height'] ) ? $options['height'] : ''
		);

		printf(
			'<p>%s<br>%s</p>',
			\__( 'Specify the desired dimensions to which all new uploads will be resized before thumbnail generation.', $this->plugin->get_plugin_name() ),
			\__( 'Images with initial dimensions lower than the ones specified are not resized.', $this->plugin->get_plugin_name() )
		);
	}
}
