<?php
/**
 * Set up the SEO & social sharing meta tags.
 */

namespace WordPressdotorg\Theme\Photo_Directory_2024\SEO_Social_Meta;

use function WordPressdotorg\Theme\Photo_Directory_2024\{get_photo_post_type, get_favorites_user};

add_filter( 'document_title_parts', __NAMESPACE__ . '\set_document_title' );
add_filter( 'document_title_separator', __NAMESPACE__ . '\document_title_separator' );
add_action( 'jetpack_open_graph_tags', __NAMESPACE__ . '\add_social_meta_tags', 20 );
add_action( 'jetpack_seo_meta_tags', __NAMESPACE__ . '\add_seo_meta_tags', 20 );

add_filter( 'jetpack_enable_open_graph', '__return_true', 100 ); // Enable Jetpack Open Graph tags.

/**
 * Append an optimized site name.
 *
 * @param array $title {
 *     The document title parts.
 *
 *     @type string $title   Title of the viewed page.
 *     @type string $page    Optional. Page number if paginated.
 *     @type string $tagline Optional. Site description when on home page.
 *     @type string $site    Optional. Site title when not on home page.
 * }
 * @return array Filtered title parts.
 */
function set_document_title( $title ) {
	global $wp_query;

	if ( is_front_page() ) {
		$title['title']   = __( 'WordPress Photo Directory', 'wporg-photos' );
		$title['tagline'] = __( 'WordPress.org', 'wporg-photos' );
	} else {
		if ( is_singular( get_photo_post_type() ) ) {
			/* translators: Photo identifier (e.g., 7886743cf0) */
			$title['title'] = sprintf( __( 'WordPress photo %s', 'wporg-photos' ), $title['title'] );
		} elseif ( is_author() ) {
			/* translators: Author name */
			$title['title'] = sprintf( __( 'WordPress photo by %s', 'wporg-photos' ), $title['title'] );
		} elseif ( ! get_favorites_user() ) {
			$title['title'] = strip_tags( get_the_archive_title() );
		}

		// If results are paged and the max number of pages is known.
		if ( is_paged() && $wp_query->max_num_pages ) {
			$title['page'] = sprintf(
				// translators: 1: current page number, 2: total number of pages
				__( 'Page %1$s of %2$s', 'wporg-photos' ),
				get_query_var( 'paged' ),
				$wp_query->max_num_pages
			);
		}

		$title['site'] = __( 'WordPress.org', 'wporg-photos' );
	}

	return $title;
}

/**
 * Set the separator for the document title.
 *
 * @return string Document title separator.
 */
function document_title_separator() {
	return ( is_feed() ) ? '&#8212;' : '&#124;';
}

/**
 * Add meta tags for richer social media integrations.
 */
function add_social_meta_tags( $tags ) {
	$default_image = 'https://wordpress.org/files/2024/12/photos-ogimage.png';
	$site_title = function_exists( '\WordPressdotorg\site_brand' ) ? \WordPressdotorg\site_brand() : 'WordPress.org';
	$blog_title = __( 'WordPress Photo Directory', 'wporg-photos' );
	$description = __( 'Choose from a growing collection of free, CC0-licensed photos to customize and enhance your WordPress website.', 'wporg-photos' );

	if ( is_front_page() ) {
		$tags['og:site_name']    = $site_title;
		$tags['og:title']        = $blog_title;
		$tags['og:description']  = $description;
		$tags['og:image']        = esc_url( $default_image );
		$tags['og:image:alt']    = $blog_title;
		$tags['og:locale']       = get_locale();
		$tags['twitter:card']    = 'summary_large_image';
		return $tags;
	}

	if ( is_author() ) {
		$tags['twitter:card'] = 'summary';
		$tags['og:title'] = sprintf(
			/* translators: Author name */
			__( 'WordPress photos by %s', 'wporg-photos' ),
			get_the_author()
		);
	} else if ( get_favorites_user() ) {
		// Default tags are okay, just update the image.
		$tags['og:image']        = esc_url( $default_image );
		$tags['og:image:alt']    = $blog_title;
	} else if ( is_singular( get_photo_post_type() ) ) {
		$sep = document_title_separator();
		$title = join( ' ', [ $tags['og:title'], $sep, $blog_title ] );
		$alt_text = get_the_content( '', '', get_the_ID() );

		$tags['og:title']            = $title;
		$tags['twitter:text:title']  = $title;
		$tags['og:description']      = $alt_text;
		$tags['twitter:description'] = $alt_text;
		$tags['twitter:card']        = 'summary_large_image';

		if ( has_post_thumbnail() ) {
			$photo_id = get_post_thumbnail_id();
			$src = wp_get_attachment_image_src( $photo_id, 'medium_large' );
			$tags['og:image'] = esc_url( $src[0] );
			$tags['og:image:width'] = $src[1];
			$tags['og:image:height'] = $src[2];
			$tags['og:image:alt'] = $alt_text;
			$tags['twitter:image'] = esc_url( $src[0] );
			$tags['twitter:image:alt'] = $alt_text;
		}
	} else {
		$tags['og:title']     = get_the_archive_title();
		$tags['og:image']     = esc_url( $default_image );
		$tags['og:image:alt'] = $blog_title;
	}

	return $tags;
}

/**
 * Update the description meta value.
 */
function add_seo_meta_tags( $tags ) {
	$tags['description'] = __( 'Choose from a growing collection of free, CC0-licensed photos to customize and enhance your WordPress website.', 'wporg-photos' );

	return $tags;
}
