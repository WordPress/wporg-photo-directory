<?php
/**
 * Helper functions.
 */

namespace WordPressdotorg\Theme\Photo_Directory_2024;

use WordPressdotorg\Photo_Directory;

/**
 * Returns the photo post type.
 *
 * @return string
 */
function get_photo_post_type() {
	return Photo_Directory\Registrations::get_post_type();
}

/**
 * Get the selected terms from the current query, converting them to an array if necessary.
 *
 * @param string $query_var Parameter name.
 * @param bool   $is_array  Whether the return value should be an array.
 *
 * @return array
 */
function get_query_terms( $query_var, $is_array = true ) {
	global $wp_query;
	if ( $is_array ) {
		$terms = isset( $wp_query->query[ $query_var ] ) ? $wp_query->query[ $query_var ] : array();
		if ( is_string( $terms ) ) {
			$terms = explode( '+', $terms );
		}
		return $terms;
	}

	return isset( $wp_query->query[ $query_var ] ) ? $wp_query->query[ $query_var ] : '';
}
