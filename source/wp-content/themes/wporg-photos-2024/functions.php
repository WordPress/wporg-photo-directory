<?php

namespace WordPressdotorg\Theme\Photo_Directory_2024;

use WordPressdotorg\Photo_Directory;

require_once( __DIR__ . '/inc/block-config.php' );

// Block files
require_once( __DIR__ . '/src/meta-list/index.php' );

// Actions & filters.
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
add_action( 'pre_get_posts', __NAMESPACE__ . '\pre_get_posts' );
add_filter( 'frontpage_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_filter( 'search_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_filter( 'archive_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_action( 'template_redirect', __NAMESPACE__ . '\redirect_term_archives' );

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

	if ( ! $query->is_singular() ) {
		$query->set( 'post_type', get_photo_post_type() );
		$query->set( 'posts_per_page', 24 );
	}

	// Update `photo_color` taxonomy queries to use `AND` operator.
	$tax_query = isset( $query->tax_query->queries ) ? $query->tax_query->queries : [];
	if ( ! empty( $tax_query ) ) {
		foreach ( $tax_query as $key => $tax_query_item ) {
			if ( 'photo_color' === $tax_query_item['taxonomy'] ) {
				$tax_query[ $key ]['operator'] = 'AND';
			}
		}
		$query->set( 'tax_query', $tax_query );
	}
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
 * Redirect category and tag archives to their canonical URLs.
 *
 * This prevents double URLs for queries. For example, core redirects the following:
 *  - /?photo_category=animals -> /c/animals/
 *  - /?photo_orientation=landscape -> /orientation/landscape/
 *  - /?photo_color[]=black -> /color/black/
 * Other combinations of queries are handled by this function, such as:
 *  - /?photo_color[]=black&photo_color[]=white -> /color/black+white/
 *  - /?photo_category=animals&photo_color[]=black -> /c/animals/?photo_color[]=black
 *  - /?s=cat -> /search/cat/
 *  - /?photo_category=animals&s=cat -> /s/cat/?photo_category=animals
 */
function redirect_term_archives() {
	global $wp_query, $wp;
	$url = false;

	// Run through these variables in this order, the first one that is set will be used as the base URL.
	$query_vars = [
		's' => 'search',
		'photo_category' => 'c',
		'photo_orientation' => 'orientation',
		'photo_color' => 'color',
	];

	if ( isset( $wp_query->query['photo_category'] ) && 'all' === $wp_query->query['photo_category'] ) {
		unset( $wp_query->query['photo_category'] );
	}

	if ( isset( $wp_query->query['photo_orientation'] ) && 'all' === $wp_query->query['photo_orientation'] ) {
		unset( $wp_query->query['photo_orientation'] );
	}

	// Return early if we're already on an author or browse page.
	if ( ! empty( $wp->request ) ) {
		return;
	}

	$url = '';
	foreach ( $query_vars as $qv => $path ) {
		// Skip over any unset properties.
		if ( ! isset( $wp_query->query[ $qv ] ) ) {
			continue;
		}

		// On the first pass, we have no URL, so we need to build it.
		if ( ! $url ) {
			if ( 's' === $qv ) {
				$value = urlencode( $wp_query->query[ $qv ] );
				$url = home_url( '/search/' . $value . '/' );
			} else {
				$values = get_query_terms( $qv );
				if ( count( $values ) === 1 ) {
					$url = get_term_link( $values[0], $qv );
					if ( is_wp_error( $url ) ) {
						$url = home_url( '/' );
					}
				} else {
					$url = home_url( $path . '/' . implode( '+', $values ) . '/' );
				}
			}
		} else {
			// append to URL.
			$value = 'photo_color' === $qv ? get_query_terms( $qv ) : $wp_query->query[ $qv ];
			$url = add_query_arg( $qv, $value, $url );
		}
	}

	if ( $url ) {
		// Redirect to the new permalink-style URL.
		wp_safe_redirect( $url );
		exit;
	}
}
