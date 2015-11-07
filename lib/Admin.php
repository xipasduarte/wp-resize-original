<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/admin
 */

namespace xipasduarte\WPResizeOriginal;

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    PluginName
 * @subpackage PluginName/admin
 * @author     Your Name <email@example.com>
 */
class Admin {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PluginName_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PluginName_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_style(
			$this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . 'dist/styles/plugin-name-admin.css',
			array(),
			$this->plugin->get_version(),
			'all'
		);

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PluginName_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PluginName_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_script(
			$this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . 'dist/scripts/plugin-name-admin.js',
			array( 'jquery' ),
			$this->plugin->get_version(),
			false
		);

	}

	/**
	 * Enforce Attachment Size
	 *
	 * @param array $data Sanitized post data.
	 * @param array $postarr Raw post data.
	 **/
	public function enforce_attachment_size( $data, $postarr ) {
		// File path.
		$file = $postarr['file'];

		// Check if we are dealing with an attachment of type image.
		if ( 'attachment' === $data['post_type']
			&& preg_match( '!^image/!', $postarr['post_mime_type'] )
			&& \file_is_displayable_image( $file ) ) {

			$defaults = array();
			$attachment_resize = apply_filters( 'wp_resize_original_dimensions', $defaults );

			if ( ! empty( $attachment_resize ) ) {
				// Return an implementation that extends WP_Image_Editor.
				$image = \wp_get_image_editor( $postarr['file'] );

				if ( ! \is_wp_error( $image ) ) {
					$image->resize( $attachment_resize[0], $attachment_resize[1] );
					$image->save( $postarr['file'] );
				}
			}
		}

		return $data;
	}
}
