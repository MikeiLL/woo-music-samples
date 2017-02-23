<?php
/**
 * track list and audio file sampler player for WooCommerce album products
 *
 * @author 	Dean Walker
 * @package 	woo-track-list-and-sample-player/includes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** list download files */
function woo_tlasp_list_product_files( $post_excerpt ) {

	echo $post_excerpt;

	global $post;
	global $product;

	if ( $product && $product->exists() ) {
		if ( !$product->is_downloadable() ) {
			// find the equivalent downloaded version
			$download_files = woo_tlasp_get_digital_product($product->get_title());
			if ($download_files) {
				foreach ( $download_files as $download_id => $file ) {
				woo_tlasp_do_product_file($file['file'], $file['name']);
				}
			}
		} else {
			$download_files = $product->downloadable_files; //$product->get_files();
			foreach ( $download_files as $download_id => $file ) {
				woo_tlasp_do_product_file($file['file'], $file['name']);
			}
		}
	}
}

function woo_tlasp_do_product_file($file, $name) {
	echo '<div class="woo-tlasp">';
	$sample_url = woo_tlasp_get_sample_url( $file );
	if ($sample_url) {
		$url = esc_url($sample_url);
		$player = '[audio src="' . $url . '" controls="false"]';
		echo do_shortcode($player);
	} else {
		$player = '[audio src="' . $file . '" controls="false"]';
		echo do_shortcode($player);
	}
	echo '<div class="woo-tlasp-file-name">' . esc_html($name) . '</div>';
	echo '</div>';
}


function parseUrl($url)
{
    if (($server = parse_url($url)) === false)
    //  throw new ServerConnectionFailureException; ??
        return ['scheme' => 'http',
                'host'   => 'localhost',
                'path'   => 'socket.io',
                'secured' => false];

    if (!isset($server['port'])) {
        $server['port'] = 'https' === $server['scheme'] ? 443 : 80;
    }

    $server['secured'] = 'https' === $server['scheme'];

    return $server;
}

function getDomain($url) 
{
    $domain = implode('.', array_slice(explode('.', parse_url($url, PHP_URL_HOST)), -2));
    return $domain;
}
/**
 * Return an ID of sample audio file for a given full audio file.
 *
 * For a given audio file, this methos searchs for a matching 'sample' audio file. It assumes that the
 * sample is of the same file type (eg .mp3) and that the sample has a matching file name but proceeded by
 * a prefix. So the sample for the audio file 'mysong.mp3' would be 'sample-mysong.mp3' if using the default
 * prefix
 * 
 * With Thanks: http://frankiejarrett.com/2013/05/get-an-attachment-id-by-url-in-wordpress/
 *
 * TODO look into replace with https://gist.github.com/asadowski10/496068291ec5ca5f3016
 *
 * @param string $url The URL of the file whose sample you require
 * 
 * @return int|null $attachment Returns an attachment ID, or null if no attachment is found
 */
function woo_tlasp_get_sample_url( $url ) {
	// get the last past of the URL, e.g the test-music.mp3
	// Split the $url into two parts with the wp-content directory as the separator.
		//attachment_url_to_postid($url);
		$parsed_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
		// Get the host of the current site and the host of the $url, ignoring www.
		$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
		$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
		// Return nothing if there aren't any $url parts or if the current host and $url host do not match.
		if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
			return;
		}

		// Now we're going to quickly search the DB for any attachment GUID with a partial path match.
		// Example: /uploads/2013/05/test-image.jpg
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND guid LIKE %s", $parsed_url[1] ) );
		if ( is_array( $attachment ) && ! empty( $attachment ) ) {
			return array_shift( $attachment );
		}

return null;
}

function woo_tlasp_get_digital_product($physical_product_title) {
	$download_product_title = str_ireplace (WOO_TLASP_PHYSICAL_SUFFIX, WOO_TLASP_DOWNLOAD_SUFFIX, $physical_product_title) ;
	// Now we're going to quickly search the DB for any attachment GUID with a match
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_title RLIKE %s;", $download_product_title ) );

	// Returns null if no post is found
	if ($attachment[0]) {
		return get_post_meta($attachment[0], '_downloadable_files', true);
	}
	return null;
}
?>
