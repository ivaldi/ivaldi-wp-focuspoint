<?php
/*
Plugin Name: Ivaldi Focuspoint
Plugin URI: https://ivaldi.nl
Description: Set focus point via media library and provide helper functions in PHP
Version: 1.0.4
Author: Michel Fiege
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/ivaldi/ivaldi-wp-focuspoint
GitHub Branch: master
*/

require_once( "acf.php" );

class Ivaldi_Focuspoint {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		wp_register_script( 'ivaldi-focuspoint-min', plugins_url( '/assets/js/jquery.focuspoint.min.js', __FILE__ ), array('jquery'), false, true );
		wp_enqueue_script( 'ivaldi-focuspoint-min' );

		wp_register_script( 'ivaldi-focuspoint', plugins_url( '/assets/js/focuspoint.js', __FILE__ ), array('jquery'), false, true );
		wp_enqueue_script( 'ivaldi-focuspoint' );

		wp_register_style( 'ivaldi-focuspoint', plugins_url( '/assets/css/focuspoint.css', __FILE__ ) );
		wp_enqueue_style( 'ivaldi-focuspoint' );
	}

	public function admin_enqueue_scripts() {
		wp_register_script( 'ivaldi-admin-focuspoint', plugins_url( '/assets/js/admin-focuspoint.js', __FILE__ ), array('jquery'), false, true );
		wp_enqueue_script( 'ivaldi-admin-focuspoint' );
	}

}

$ivaldi_focuspoint = new Ivaldi_Focuspoint();

// Helper functions

// Make sure to set class="focuspoint" on the wrapper. Example:
// <div class="focuspoint" <?php echo iv_focuspoint_attr()...
//   <img...
// </div>
function iv_focuspoint_attr( $image_id ) {

	$img = iv_get_image_object( $image_id );
	$attachment = wp_get_attachment_image_src( $img->ID, $size );

	if ( isset( $attachment[1] ) && isset( $attachment[2] ) ) {
		$width = $attachment[1];
		$height = $attachment[2];
		$focus_x = get_field( 'focus_point_x', $img->ID );
		$focus_y = get_field( 'focus_point_y', $img->ID );

		$x_percentage = $focus_x * 100;
		$y_percentage = $focus_y * 100;

		$ret = '';
		$ret .= 'data-focus-x="'.$focus_x.'" ';
		$ret .= 'data-focus-y="'.$focus_y.'" ';
		$ret .= 'data-image-w="'.$width.'" ';
		$ret .= 'data-image-h="'.$height.'" ';
		return $ret;
	}

	return '';
}

// To be used when using background images
// In this case we use a pure CSS-solution with percentage values
// This is as good as it gets without js: http://jonom.github.io/jquery-focuspoint/demos/css-js-comparison/index.html
// Example in php: <div style="<?php iv_focuspoint_style( $img->ID ); ....
function iv_focuspoint_style( $image_id, $size = 'full' ) {
	$img = iv_get_image_object( $image_id );
	$ret = '';

	if ( isset( $img->ID ) ) {

		$focus_x = get_field( 'focus_point_x', $img->ID );
		$focus_y = get_field( 'focus_point_y', $img->ID );

		$attachment = wp_get_attachment_image_src($img->ID, $size);

		if ( isset( $attachment[0] ) ) {
			$ret .= 'background-image: url('.$attachment[0].'); ';
		}

		if ( $focus_x || $focus_y ) {
			$x_percentage = $focus_x * 100;
			$y_percentage = $focus_y * 100;
			$ret .= 'background-size: cover; background-position:'.$x_percentage.'% '.$y_percentage.'%;';
		}

	// something went wrong trying to retrieve image object
	// simply return $image_id (which probably contains the url)
	} elseif( $img != '' ) {

		$url = $image_id;
		$ret .= 'background-image: url('.$url.')';
		$ret .= 'background-size: cover; background-position: 50% 50%;';

	// there is nothing to show here
	} else {
		$ret = '';
	}

	return $ret == '' ? false : $ret;
}

function iv_focuspoint_position( $image_id ) {
	$ret = '';
	$img = iv_get_image_object( $image_id );

	if ( isset( $img->ID ) ) {

		$focus_x = get_field( 'focus_point_x', $img->ID );
		$focus_y = get_field( 'focus_point_y', $img->ID );

		if ( $focus_x || $focus_y ) {
			$x_percentage = $focus_x * 100;
			$y_percentage = $focus_y * 100;
			$ret .= 'background-size: cover; background-position:'.$x_percentage.'% '.$y_percentage.'%;';
		}

	}

	return $ret;
}

function iv_get_image_object( $image_id ) {
	$img = false;
	// lets see if it really is an image id
	if ( is_numeric( $image_id ) ) {
		$img = get_post( $image_id );
	// in case its an image url, try to fetch get image object anyway
	} elseif ( stripos( $image_id, 'http' ) !== false ) {
		$img = get_post( iv_focuspoint_get_attachment_id_by_url( $image_id ) );
	// in case it already is an image object, use that one
	} elseif ( is_object( $image_id ) && isset( $image_id->ID ) ) {
		$img = $image_id;
	}
	return $img;
}

// http://bordoni.me/get-attachment-id-by-image-url/
function iv_focuspoint_get_attachment_id_by_url( $url ) {
	$post_id = attachment_url_to_postid( $url );

	if ( ! $post_id ){
		$dir = wp_upload_dir();
		$path = $url;
		if ( 0 === strpos( $path, $dir['baseurl'] . '/' ) ) {
			$path = substr( $path, strlen( $dir['baseurl'] . '/' ) );
		}

		if ( preg_match( '/^(.*)(\-\d*x\d*)(\.\w{1,})/i', $path, $matches ) ){
			$url = $dir['baseurl'] . '/' . $matches[1] . $matches[3];
			$post_id = attachment_url_to_postid( $url );
		}
	}

	return (int) $post_id;
}
