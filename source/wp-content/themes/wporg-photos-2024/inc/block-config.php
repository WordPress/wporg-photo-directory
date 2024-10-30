<?php
/**
 * Set up configuration for dynamic blocks.
 */

namespace WordPressdotorg\Theme\Photo_Directory_2024\Block_Config;

use WordPressdotorg\Photo_Directory;
use function WordPressdotorg\Theme\Photo_Directory_2024\{get_photo_post_type, get_query_terms};

// Actions & filters.
add_filter( 'wporg_query_total_label', __NAMESPACE__ . '\update_query_total_label', 10, 2 );
add_filter( 'wporg_block_navigation_menus', __NAMESPACE__ . '\add_site_navigation_menus' );
add_filter( 'wporg_query_filter_options_category', __NAMESPACE__ . '\get_category_options' );
add_filter( 'wporg_query_filter_options_color', __NAMESPACE__ . '\get_color_options' );
add_filter( 'wporg_query_filter_options_orientation', __NAMESPACE__ . '\get_orientation_options' );
add_action( 'wporg_query_filter_in_form', __NAMESPACE__ . '\inject_other_filters', 10, 2 );
add_filter( 'render_block_wporg/link-wrapper', __NAMESPACE__ . '\inject_permalink_link_wrapper' );
add_filter( 'render_block_core/post-content', __NAMESPACE__ . '\inject_alt_text_label' );

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
 * Provide a list of category options.
 *
 * @param array $options The options for this filter.
 * @return array New list of category options.
 */
function get_category_options( $options ) {
	return get_single_query_filter_options(
		'photo_category',
		array(
			'default' => __( 'Categories', 'wporg-photos' ),
			/* translators: The dropdown label for filtering, %s is the selected item. */
			'selected' => __( 'Categories: %s', 'wporg-photos' ),
		)
	);
}

/**
 * Provide a list of color options.
 *
 * @param array $options The options for this filter.
 * @return array New list of color options.
 */
function get_color_options( $options ) {
	$colors = get_terms(
		array(
			'taxonomy' => 'photo_color',
			'orderby' => 'name',
		)
	);

	$selected = get_query_terms( 'photo_color' );

	$count = count( $selected );
	$label = sprintf(
		/* translators: The dropdown label for filtering, %s is the selected term count. */
		_n( 'Colors <span>%s</span>', 'Colors <span>%s</span>', $count, 'wporg-photos' ),
		$count
	);
	return array(
		'label' => $label,
		'title' => __( 'Colors', 'wporg-photos' ),
		'key' => 'photo_color',
		'action' => home_url( '/' ),
		'options' => array_combine( wp_list_pluck( $colors, 'slug' ), wp_list_pluck( $colors, 'name' ) ),
		'selected' => $selected,
	);
}

/**
 * Provide a list of orientation options.
 *
 * @param array $options The options for this filter.
 * @return array New list of color options.
 */
function get_orientation_options( $options ) {
	return get_single_query_filter_options(
		'photo_orientation',
		array(
			'default' => __( 'Orientation', 'wporg-photos' ),
			/* translators: The dropdown label for filtering, %s is the selected item. */
			'selected' => __( 'Orientation: %s', 'wporg-photos' ),
		)
	);
}

/**
 * Get the arguments for a single-select query filter.
 *
 * @param string $taxonomy The taxonomy term & query var to use.
 * @param array  $label_args {
 *     An array of label arguments.
 *
 *     @type string $default  The label to use if no term is selected.
 *     @type string $selected The label with a placeholder for term name.
 * }
 *
 * @return array
 */
function get_single_query_filter_options( $taxonomy, $label_args ) {
	global $wp_query;

	$term_list = get_terms(
		array(
			'taxonomy' => $taxonomy,
			'orderby' => 'name',
		)
	);
	$options = array_combine( wp_list_pluck( $term_list, 'slug' ), wp_list_pluck( $term_list, 'name' ) );
	$options = array_merge( [ 'all' => __( 'All', 'wporg-photos' ) ], $options );

	$label = $label_args['default'];

	$selected = isset( $wp_query->query[ $taxonomy ] ) ? $wp_query->query[ $taxonomy ] : 'all';
	if ( isset( $options[ $selected ] ) ) {
		$label = sprintf( $label_args['selected'], $options[ $selected ] );
	}

	return array(
		'label' => $label,
		'title' => $label_args['default'],
		'key' => $taxonomy,
		'action' => home_url( '/' ),
		'options' => $options,
		'selected' => [ $selected ],
	);
}

/**
 * Add in the other existing filters as hidden inputs in the filter form.
 *
 * Enables combining filters by building up the correct URL on submit.
 *
 * @param string   $key   The key for the current filter.
 * @param WP_Block $block The current block being rendered.
 */
function inject_other_filters( $key, $block ) {
	global $wp_query;

	$query_vars = [ 'photo_category', 'photo_color', 'photo_orientation' ];
	foreach ( $query_vars as $query_var ) {
		if ( ! isset( $wp_query->query[ $query_var ] ) ) {
			continue;
		}
		if ( $key === $query_var ) {
			continue;
		}
		$values = get_query_terms( $query_var, 'photo_color' === $query_var );
		if ( is_array( $values ) ) {
			foreach ( $values as $value ) {
				printf( '<input type="hidden" name="%s[]" value="%s" />', esc_attr( $query_var ), esc_attr( $value ) );
			}
		} else {
			printf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $query_var ), esc_attr( $values ) );
		}
	}

	// Pass through search query.
	if ( isset( $wp_query->query['s'] ) ) {
		printf( '<input type="hidden" name="s" value="%s" />', esc_attr( $wp_query->query['s'] ) );
	}
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

/**
 * Add an "Alt text" label in the post content block on photos.
 *
 * @param string $block_content The block content.
 *
 * @return array The updated block.
 */
function inject_alt_text_label( $block_content ) {
	if ( ! is_singular( get_photo_post_type() ) ) {
		return $block_content;
	}

	$alt_text_label = sprintf(
		'<span class="wporg-alt-text-label">%s</span> ',
		__( 'Alternative Text:', 'wporg-photos' )
	);
	return str_replace( '<p>', '<p>' . $alt_text_label, $block_content );
}
