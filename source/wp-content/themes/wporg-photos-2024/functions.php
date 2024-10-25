<?php

namespace WordPressdotorg\Theme\Photo_Directory_2024;

use WordPressdotorg\Photo_Directory;

require_once( __DIR__ . '/inc/block-config.php' );

// Actions & filters.
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
add_action( 'pre_get_posts', __NAMESPACE__ . '\pre_get_posts' );
add_filter( 'frontpage_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_filter( 'search_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_filter( 'archive_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );

// Remove filters attached in the plugin.
// @todo Remove these from the plugin once the new theme is live.
add_action(
	'init',
	function() {
		remove_action( 'pre_get_posts', [ 'WordPressdotorg\Photo_Directory\Posts', 'offset_front_page_paginations' ], 11 );
		remove_filter( 'the_posts', [ 'WordPressdotorg\Photo_Directory\Posts', 'fix_front_page_pagination_count' ], 10, 2 );
	}
);

/**
 * Returns the photo post type.
 *
 * @return string
 */
function get_photo_post_type() {
	return Photo_Directory\Registrations::get_post_type();
}

/**
 * Enqueue scripts and styles.
 */
function enqueue_assets() {
	$asset_file = get_theme_file_path( 'build/style/index.asset.php' );
	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	// The parent style is registered as `wporg-parent-2021-style`, and will be loaded unless
	// explicitly unregistered. We can load any child-theme overrides by declaring the parent
	// stylesheet as a dependency.

	$metadata = require $asset_file;
	wp_enqueue_style(
		'wporg-photos-2024',
		get_theme_file_uri( 'build/style/style-index.css' ),
		array( 'wporg-parent-2021-style', 'wporg-global-fonts' ),
		$metadata['version']
	);
}

/**
 * Modifies the main query during the `pre_get_posts` action.
 *
 * @param WP_Query $query Query object.
 */
function pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$query->set( 'post_type', get_photo_post_type() );
}

/**
 * Use the index template for paged, archive, search pages.
 *
 * A "paged" page is a subsequent page of the homepage, e.g. /page/2/.
 *
 * @param string[] $templates A list of template candidates, in descending order of priority.
 */
function override_template_hierarchy( $templates ) {
	global $wp_query;

	if ( is_paged() || is_search() || is_archive() ) {
		array_unshift( $templates, 'index.html' );
	}

	return $templates;
}

/**
 * Get the selected terms from the current query.
 *
 * @return array
 */
function get_query_terms( $query_var ) {
	global $wp_query;
	$terms = isset( $wp_query->query[ $query_var ] ) ? $wp_query->query[ $query_var ] : array();
	if ( is_string( $terms ) ) {
		$terms = explode( '+', $terms );
	}

	return $terms;
}
