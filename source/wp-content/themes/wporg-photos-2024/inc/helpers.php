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

/**
 * Return the user object for the user whose favorites are being viewed.
 *
 * @return \WP_User|false User object if favorites & found, false otherwise.
 */
function get_favorites_user() {
	$favorite_user = get_query_var( Photo_Directory\Favorites::QUERY_VAR_USER_FAVORITES );
	if ( $favorite_user ) {
		$user = get_user_by( 'slug', $favorite_user );
		if ( is_a( $user, 'WP_User' ) ) {
			return $user;
		}
	}
	return false;
}

/**
 * Returns a short plain-text excerpt of a photo's description, for use as its title.
 *
 * @param int|\WP_Post $post Photo post ID or object.
 *
 * @return string Description excerpt, or an empty string if the photo has no description.
 */
function get_photo_description_excerpt( $post ) {
	return wp_trim_words( get_post_field( 'post_content', $post ), 8 );
}
