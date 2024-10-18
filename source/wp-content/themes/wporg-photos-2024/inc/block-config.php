<?php
/**
 * Set up configuration for dynamic blocks.
 */

namespace WordPressdotorg\Theme\Photo_Directory_2024\Block_Config;

use WordPressdotorg\Photo_Directory;

// Actions & filters.
add_filter( 'wporg_query_total_label', __NAMESPACE__ . '\update_query_total_label', 10, 2 );
add_filter( 'wporg_block_navigation_menus', __NAMESPACE__ . '\add_site_navigation_menus' );
add_filter( 'render_block_wporg/link-wrapper', __NAMESPACE__ . '\inject_permalink_link_wrapper' );

/**
 * Update the query total label to reflect "photos" found.
 *
 * @param string $label       The maybe-pluralized label to use, a result of `_n()`.
 * @param int    $found_posts The number of posts to use for determining pluralization.
 *
 * @return string Updated string with total placeholder.
 */
function update_query_total_label( $label, $found_posts ) {
	/* translators: %s: the result count. */
	return _n( '%s photo', '%s photos', $found_posts, 'wporg-photos' );
}

/**
 * Provide a list of local navigation menus.
 */
function add_site_navigation_menus( $menus ) {
	global $wp_query;

	$menu = array();
	$menu[] = array(
		'label' => __( 'Submit a photo', 'wporg-photos' ),
		'url' => '/submit/',
	);
	$menu[] = array(
		'label' => __( 'Guidelines', 'wporg-photos' ),
		'url' => '/guidelines/',
	);
	$menu[] = array(
		'label' => __( 'License', 'wporg-photos' ),
		'url' => '/license/',
	);
	$menu[] = array(
		'label' => __( 'FAQ', 'wporg-photos' ),
		'url' => '/faq/',
	);
	$menu[] = array(
		'label' => __( 'My favorites', 'wporg-photos' ),
		'url' => '/favorites/',
	);
	if ( ! is_user_logged_in() ) {
		global $wp;
		$redirect_url = home_url( $wp->request );
		$menu[] = array(
			'label' => __( 'Log in', 'wporg-photos' ),
			'url' => wp_login_url( $redirect_url ),
		);
	}

	return array(
		'main' => $menu,
	);
}

/**
 * Update the link in `wporg/link-wrapper` to use the current post permalink.
 *
 * @param string $block_content The block content.
 *
 * @return array The updated block.
 */
function inject_permalink_link_wrapper( $block_content ) {
	return str_replace( 'href=""', 'href="' . get_permalink() . '"', $block_content );
}
