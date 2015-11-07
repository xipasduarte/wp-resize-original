<?php
/**
 * The main functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    wp-resize-original
 * @subpackage wp-resize-original/admin
 */

namespace xipasduarte\WPResizeOriginal\Admin;

/**
 * The main functionality of the plugin.
 *
 * Defines all the methods to perform the original image resizing, both to the
 * user's requested dimensions and on auto mode.
 *
 * @package    wp-resize-original
 * @subpackage wp-resize-original/admin
 * @author     Pedro Duarte <xipasduarte@gmail.com>
 */
class ResizeOriginal {

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
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
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

			$resize_dimensions = $this->resize_dimensions();

			// Do the actual resize of the original attachment.
			if ( ! empty( $resize_dimensions ) ) {
				$this->resize_original( $postarr['file'], $resize_dimensions );
			}
		}

		return $data;
	}

	/**
	 * Resize Dimensions
	 *
	 * Get the dimensions for the attachment resize.
	 * Dimensions can be set by the user with the filter wp_resize_original_dimensions
	 * or they can be determined automatically. To have them determined automatically
	 * the filter wp_resize_original_auto should return true.
	 *
	 * @return array  The resize dimensions.
	 **/
	private function resize_dimensions() {

		if ( \has_filter( 'wp_resize_original_dimensions' ) ) {

			// Grab the maximum dimensions set by the user.
			return \apply_filters( 'wp_resize_original_dimensions', array() );

		} elseif ( \has_filter( 'wp_resize_original_auto' ) && \apply_filters( 'wp_resize_original_auto', false ) ) {

			// Determine maximum dimensions.
			return $this->auto_dimensions();

		} else {
			return array();
		}
	}

	/**
	 * Resize Original
	 *
	 * Resizes the original attachment image.
	 *
	 * @param string $file File absolute path.
	 * @param array  $dimensions Width and height of new original.
	 **/
	private function resize_original( $file, $dimensions ) {

		// Return an implementation that extends WP_Image_Editor.
		$image = \wp_get_image_editor( $file );

		if ( ! \is_wp_error( $image ) ) {
			$image->resize( $dimensions[0], $dimensions[1] );
			$image->save( $file );
		}
	}

	/**
	 * Auto Dimensions
	 *
	 * Using the available image thumbs, determines which should be the max width
	 * and max height for the original to be able to generate all of them.
	 **/
	private function auto_dimensions() {

		global $_wp_additional_image_sizes;
		$wp_default_image_sizes = array( 'thumbnail', 'medium', 'large' );
		$auto_dimensions = array(
			'width'  => 0,
			'height' => 0,
			'crop'   => false,
		);

		/**
		 * Go through all the available image sizes and determine which are the
		 * the biggest dimensions of width and height, plus if they occur with
		 * crop.
		 */
		foreach ( $wp_default_image_sizes as $size ) {
			$width  = \get_option( $size . '_size_w' );
			$height = \get_option( $size . '_size_h' );
			$crop   = \get_option( $size . '_crop' );

			if ( $width > $auto_dimensions['width'] ) {
				$auto_dimensions['width'] = $width;

				$auto_dimensions['crop'] = $width > $height ? $crop : false;
			}

			if ( $height > $auto_dimensions['height'] ) {
				$auto_dimensions['height'] = $height;

				$auto_dimensions['crop'] = $height > $width ? $crop : false;
			}
		}

		foreach ( $_wp_additional_image_sizes as $size => $options ) {
			$width  = $options['width'];
			$height = $options['height'];
			$crop   = $options['crop'];

			if ( $width > $auto_dimensions['width'] ) {
				$auto_dimensions['width'] = $width;

				if ( $width > $height ) {
					$auto_dimensions['crop'] = false === $crop ? false : true;
				}
			}

			if ( $height > $auto_dimensions['height'] ) {
				$auto_dimensions['height'] = $height;

				if ( $height > $width ) {
					$auto_dimensions['crop'] = false === $crop ? false : true;
				}
			}
		}

		if ( $auto_dimensions['crop'] ) {
			return $auto_dimensions['width'] > $auto_dimensions['height']
				? array( $auto_dimensions['width'], 9999 )
				: array( 9999, $auto_dimensions['height'] );

		} else {
			return array( $auto_dimensions['width'], $auto_dimensions['height'] );
		}
	}
}
