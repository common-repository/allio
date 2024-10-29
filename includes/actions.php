<?php
/**
 * Front-end Actions
 *
 * @package     ALLIOC
 * @subpackage  Functions
 * @copyright   Copyright (c) 2017, Daniel Powney
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1
 */

// Exit if accessed directly
if (! defined ( 'ABSPATH' ))
	exit ();

/**
 * Content content
 */
function allioc_content_overlay() {

	if ( allioc_is_chatbot_overlay_enabled() ) {

		$overlay_settings = (array) get_option( 'allioc_overlay_settings' );

		ob_start();
		allioc_get_template_part( 'chatbot', 'overlay', true, array(
				'overlay_header_text' 		=> $overlay_settings['overlay_header_text'],
				'chat_logo' 		=> $overlay_settings['chat_logo'] != '' ? wp_get_attachment_image( $overlay_settings['chat_logo'], 'full', false, array( 'id' => 'allioc-logo-image')) : '',
				'user_icon' 		=> $overlay_settings['user_icon'] != '' ? wp_get_attachment_image( $overlay_settings['user_icon'], 'full', false, array( 'id' => 'allioc-logo-image')) : '',
				'background_img' 		=> $overlay_settings['background_img'] != '' ? wp_get_attachment_image( $overlay_settings['background_img'], 'full', false, array( 'id' => 'allioc-logo-image')) : '',

				'position_right' 		=> $overlay_settings['position_right'],
				'position_bottom' 		=> $overlay_settings['position_bottom'],
				'overlay_powered_by_text' 	=> $overlay_settings['overlay_powered_by_text'],
				'toggle_class'				=> $overlay_settings['overlay_default_open'] == true ? 'allioc-toggle-open' :  'allioc-toggle-closed'
		) );
		$html = ob_get_contents();
		ob_end_clean();

		echo $html;
	}
}
add_action( 'wp_footer', 'allioc_content_overlay' );

add_action ('wp_head', 'allioc_meta_tag');

function allioc_meta_tag() {
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
	echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />';
	echo '<meta name="apple-mobile-web-app-capable" content="yes" />';
}

/**
 * Determines whether the chatbot overlay is enabled for the current post or page
 */
function allioc_is_chatbot_overlay_enabled() {

	$overlay_settings = (array) get_option( 'allioc_overlay_settings' );

	$is_enabled = isset( $overlay_settings['enable_overlay'] ) ? $overlay_settings['enable_overlay'] : false;

	// https://codex.wordpress.org/Function_Reference/url_to_postid
	// FIXME may not work with attachments. See here: https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	$post_id = url_to_postid( allioc_current_url() );

	if ( ! ( $post_id == 0 || $post_id == null ) ) {
		$chatbot_overlay = get_post_meta( $post_id, 'allioc_chatbot_overlay', true );
		if ( $chatbot_overlay === "enable" ) {
			$is_enabled = true;
		} else if ( $chatbot_overlay === "disable" ) {
			$is_enabled = false;
		}
	}

	return apply_filters( 'allioc_enable_overlay', $is_enabled );
}


/**
 * Gets current URL
 */
function allioc_current_url() {
	$url = 'http';

	if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') {
		$url .= "s";
	}

	$url .= '://';

	if ( $_SERVER['SERVER_PORT'] != '80') {
		$url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
	} else {
		$url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}

	return allioc_normalize_url( $url );
}


/**
 * Normalizes URL
 */
function allioc_normalize_url( $url ) {

	// TODO return error for bad URLs

	// Process from RFC 3986 http://en.wikipedia.org/wiki/URL_normalization

	// Limiting protocols.
	if ( ! parse_url( $url, PHP_URL_SCHEME ) ) {
		$url = 'http://' . $url;
	}

	$parsed_url = parse_url( $url );
	if ( $parsed_url === false ) {
		return '';
	}

	// user and pass components are ignored

	// TODO Removing or adding “www” as the first domain label.
	$host = preg_replace( '/^www\./', '', $parsed_url['host'] );

	// Converting the scheme and host to lower case
	$scheme = strtolower( $parsed_url['scheme'] );
	$host = strtolower( $host );

	$path = $parsed_url['path'];
	// TODO Capitalizing letters in escape sequences
	// TODO Decoding percent-encoded octets of unreserved characters

	// Removing the default port
	$port = '';
	if ( isset( $parsed_url['port'] ) ) {
		$port = $parsed_url['port'];
	}
	if ( $port == 80 ) {
		$port = '';
	}

	// Removing the fragment # (do not get fragment component)

	// Removing directory index (i.e. index.html, index.php)
	$path = str_replace( 'index.html', '', $path );
	$path = str_replace( 'index.php', '', $path );

	// Adding trailing /
	$path_last_char = $path[strlen( $path ) -1];
	if ( $path_last_char != '/' ) {
		$path = $path . '/';
	}

	// TODO Removing dot-segments.

	// TODO Replacing IP with domain name.

	// TODO Removing duplicate slashes
	$path = preg_replace( "~\\\\+([\"\'\\x00\\\\])~", "$1", $path );

	// construct URL
	$url =  $scheme . '://' . $host . $path;

	// Add query params if they exist
	// Sorting the query parameters.
	// Removing unused query variables
	// Removing default query parameters.
	// Removing the "?" when the query is empty.
	$query = '';
	if ( isset( $parsed_url['query'] ) ) {
		$query = $parsed_url['query'];
	}
	if ( $query ) {
		$query_parts = explode( '&', $query );
		$params = array();
		foreach ( $query_parts as $param ) {
			$items = explode( '=', $param, 2 );
			$name = $items[0];
			$value = '';
			if ( count( $items ) == 2 ) {
				$value = $items[1];
			}
			$params[$name] = $value;
		}
		ksort( $params );
		$count_params = count( $params );
		if ( $count_params > 0 ) {
			$url .= '?';
			$index = 0;
			foreach ( $params as $name => $value ) {
				$url .= $name;
				if ( strlen( $value ) != 0 ) {
					$url .= '=' . $value;
				}
				if ( $index++ < ( $count_params - 1 ) ) {
					$url .= '&';
				}
			}
		}
	}

	// Remove some query params which we do not want
	$url = allioc_remove_query_string_params( $url, array() );

	return $url;
}


/**
 * Removes query string parameters from URL
 * @param $url
 * @param $param
 * @return string
 *
 * @since 1.2
 */
function allioc_remove_query_string_params( $url, $params ) {
	foreach ( $params as $param ) {
		$url = preg_replace( '/(.*)(\?|&)' . $param . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&' );
		$url = substr( $url, 0, -1 );
	}
	return $url;
}
