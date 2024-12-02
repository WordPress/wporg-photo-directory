<?php

namespace WordPressdotorg\Theme\Photo_Directory_2024;

use WordPressdotorg\Photo_Directory;

require_once( __DIR__ . '/inc/helpers.php' );
require_once( __DIR__ . '/inc/block-bindings.php' );
require_once( __DIR__ . '/inc/block-config.php' );
require_once( __DIR__ . '/inc/seo-social-meta.php' );

// Block files
require_once( __DIR__ . '/src/meta-list/index.php' );
require_once( __DIR__ . '/src/photo-attribution/index.php' );

// Actions & filters.
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
add_action( 'pre_get_posts', __NAMESPACE__ . '\pre_get_posts' );
add_filter( 'frontpage_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_filter( 'search_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_filter( 'archive_template_hierarchy', __NAMESPACE__ . '\override_template_hierarchy' );
add_action( 'template_redirect', __NAMESPACE__ . '\redirect_term_archives' );
add_action( 'template_redirect', __NAMESPACE__ . '\redirect_filters_page' );
add_filter( 'the_title', __NAMESPACE__ . '\filter_photo_title', 10, 2 );

// Adds user's recent submissions above upload form.
remove_filter( 'wporg_photos_pre_upload_form', [ 'WordPressdotorg\Photo_Directory\Moderation', 'output_list_of_pending_submissions_for_user' ] );
remove_filter( 'wporg_photos_pre_upload_form', [ 'WordPressdotorg\Photo_Directory\Uploads', 'output_user_recent_submissions' ] );
add_filter( 'wporg_photos_pre_upload_form', __NAMESPACE__ . '\output_list_of_pending_submissions_for_user', 9 );
add_filter( 'wporg_photos_pre_upload_form', __NAMESPACE__ . '\output_user_recent_submissions', 10 );

add_action(
	'init',
	function() {
		// Remove filters attached in the plugin.
		remove_action( 'pre_get_posts', [ 'WordPressdotorg\Photo_Directory\Posts', 'offset_front_page_paginations' ], 11 );
		remove_filter( 'the_posts', [ 'WordPressdotorg\Photo_Directory\Posts', 'fix_front_page_pagination_count' ], 10, 2 );

		// Don't swap author link with w.org profile link.
		remove_all_filters( 'author_link' );

		// Remove the "By…" from the author name block.
		remove_filter( 'render_block_core/post-author-name', 'WordPressdotorg\Theme\Parent_2021\Gutenberg_Tweaks\render_author_prefix', 10, 2 );
	}
);

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

	if ( str_ends_with( $query->get( 'orderby' ), '_desc' ) ) {
		$orderby = str_replace( '_desc', '', $query->get( 'orderby' ) );
		$query->set( 'orderby', $orderby );
		$query->set( 'order', 'desc' );
	} else if ( str_ends_with( $query->get( 'orderby' ), '_asc' ) ) {
		$orderby = str_replace( '_asc', '', $query->get( 'orderby' ) );
		$query->set( 'orderby', $orderby );
		$query->set( 'order', 'asc' );
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
	$url = '';

	// Run through these variables in this order, the first one that is set will be used as the base URL.
	$query_vars = [
		's' => 'search',
		'photo_category' => 'c',
		'photo_orientation' => 'orientation',
		'photo_color' => 'color',
	];

	if ( isset( $wp_query->query['photo_category'] ) && 'all' === $wp_query->query['photo_category'] ) {
		unset( $wp_query->query['photo_category'] );
		// Ensure that the redirect happens even if no other value is set.
		$url = home_url();
	}

	if ( isset( $wp_query->query['photo_orientation'] ) && 'all' === $wp_query->query['photo_orientation'] ) {
		unset( $wp_query->query['photo_orientation'] );
		// Ensure that the redirect happens even if no other value is set.
		$url = home_url();
	}

	// Return early if we're already on an author or browse page.
	if ( ! empty( $wp->request ) ) {
		return;
	}

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

	if ( $url && isset( $wp_query->query['orderby'] ) ) {
		$url = add_query_arg( 'orderby', $wp_query->query['orderby'], $url );
	}

	if ( $url ) {
		// Redirect to the new permalink-style URL.
		wp_safe_redirect( $url );
		exit;
	}
}

/**
 * Redirects the old individual taxonomy list pages to the new filters page.
 */
function redirect_filters_page() {
	$url = false;

	if ( is_page( [ 'c', 'color', 'orientation' ] ) ) {
		$url = home_url( '/filters/' );
	}

	if ( $url ) {
		// Redirect to the new permalink-style URL.
		wp_safe_redirect( $url );
		exit;
	}
}

/**
 * Swap the photo title (a generated ID) for a more human-friendly title.
 *
 * @param string $post_title The post title.
 * @param int    $post_id    The post ID.
 *
 * @return string Updated post title.
 */
function filter_photo_title( $post_title, $post_id ) {
	if ( ! is_admin() && get_photo_post_type() === get_post_type( $post_id ) ) {
		return __( 'Photo detail', 'wporg-photos' );
	}
	return $post_title;
}

/**
 * Adds current user's 6 most recent photo submissions above upload form.
 *
 * @param string $content Existing content before the upload form.
 *
 * @return string
 */
function output_user_recent_submissions( $content ) {
	// Bail if user does not have any published photos.
	if ( ! Photo_Directory\User::count_published_photos( get_current_user_id() ) ) {
		return $content;
	}

	$title = __( 'Your latest published photos', 'wporg-photos' );
	$author = get_current_user_id();
	$view_more = sprintf(
		/* translators: %s: URL to current user's photo archive. */
		__( 'View <a href="%s">your archive of photos</a> to see everything you&#8217;ve already had published.', 'wporg-photos' ),
		esc_url( get_author_posts_url( get_current_user_id() ) )
	);

	$block_markup = <<<HTML
<!-- wp:heading -->
<h2 class="wp-block-heading">$title</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":0,"query":{"author":"$author","postType":"photo","perPage":"6"},"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:wporg/link-wrapper {"className":"is-style-no-underline"} -->
		<a class="wp-block-wporg-link-wrapper is-style-no-underline" href="">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0"},"border":{"radius":"2px","style":"solid","width":"1px"}},"borderColor":"black-opacity-15"} -->
			<div class="wp-block-group has-border-color has-black-opacity-15-border-color" style="border-style:solid;border-width:1px;border-radius:2px">
				<!-- wp:post-featured-image {"aspectRatio":"16/9"} /-->
			</div>
			<!-- /wp:group -->
		</a>
		<!-- /wp:wporg/link-wrapper -->
	<!-- /wp:post-template -->
</div>
<!-- /wp:query -->

<!-- wp:paragraph -->
<p>$view_more</p>
<!-- /wp:paragraph -->
HTML;

	return $content . do_blocks( $block_markup );
}

/**
 * Amends content with a list of submissions in the queue for a user.
 *
 * @param string $content The content of the page so far.
 *
 * @return string
 */
function output_list_of_pending_submissions_for_user( $content ) {
	$user_id = get_current_user_id();

	// Bail if no user.
	if ( ! $user_id ) {
		return $content;
	}

	$pending = Photo_Directory\User::get_pending_photos( $user_id, '' );

	// Bail if user does not have any pending posts.
	if ( ! $pending ) {
		return $content;
	}

	ob_start();
	require_once __DIR__ . '/view/submissions-pending.php';

	return $content . do_blocks( ob_get_clean() );
}
