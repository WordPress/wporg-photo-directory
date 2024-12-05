<?php
/**
 * Set up configuration for dynamic blocks.
 */

namespace WordPressdotorg\Theme\Photo_Directory_2024\Block_Config;

use WordPressdotorg\Photo_Directory\Favorites;
use function WordPressdotorg\Theme\Photo_Directory_2024\{get_photo_post_type, get_query_terms, get_favorites_user};

// Actions & filters.
add_filter( 'wporg_query_total_label', __NAMESPACE__ . '\update_query_total_label', 10, 2 );
add_filter( 'wporg_block_navigation_menus', __NAMESPACE__ . '\add_navigation_menu_main' );
add_filter( 'wporg_block_navigation_menus', __NAMESPACE__ . '\add_navigation_menu_download' );
add_filter( 'wporg_block_navigation_menus', __NAMESPACE__ . '\add_navigation_menu_user' );
add_filter( 'wporg_query_filter_options_category', __NAMESPACE__ . '\get_category_options' );
add_filter( 'wporg_query_filter_options_color', __NAMESPACE__ . '\get_color_options' );
add_filter( 'wporg_query_filter_options_orientation', __NAMESPACE__ . '\get_orientation_options' );
add_filter( 'wporg_query_filter_options_sort', __NAMESPACE__ . '\get_sort_options' );
add_action( 'wporg_query_filter_in_form', __NAMESPACE__ . '\inject_other_filters', 10, 2 );
add_filter( 'wporg_favorite_button_settings', __NAMESPACE__ . '\get_favorite_settings', 10, 2 );
add_filter( 'render_block_wporg/link-wrapper', __NAMESPACE__ . '\inject_permalink_link_wrapper' );
add_filter( 'render_block_core/post-content', __NAMESPACE__ . '\inject_alt_text_label' );
add_filter( 'render_block_core/post-featured-image', __NAMESPACE__ . '\inject_img_alt_text', 10, 3 );
add_filter( 'render_block_core/post-featured-image', __NAMESPACE__ . '\inject_img_sizes', 10, 3 );
add_filter( 'render_block_core/navigation-link', __NAMESPACE__ . '\inject_nav_download_attribute', 10, 2 );
add_filter( 'render_block_core/site-title', __NAMESPACE__ . '\update_site_title' );
add_filter( 'render_block_data', __NAMESPACE__ . '\avatar_set_favorites_user_id', 10, 2 );
add_filter( 'render_block_data', __NAMESPACE__ . '\pattern_update_no_results', 10, 2 );

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
 * Set up the main navigation menu, used in the local navigation bar.
 */
function add_navigation_menu_main( $menus ) {
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
	if ( ! is_user_logged_in() ) {
		global $wp;
		$redirect_url = home_url( $wp->request );
		$menu[] = array(
			'label' => __( 'Log in', 'wporg-photos' ),
			'url' => wp_login_url( $redirect_url ),
			'className' => 'has-separator',
		);
	} else {
		$favorite_user = get_favorites_user();
		$is_user_favorites = $favorite_user && get_current_user_id() === $favorite_user->ID;
		$menu[] = array(
			'label' => __( 'My favorites', 'wporg-photos' ),
			'url' => '/favorites/',
			'className' => ( $is_user_favorites ? 'current-menu-item ' : '' ) . 'has-separator',
		);
	}

	$menus['main'] = $menu;
	return $menus;
}

/**
 * Set up the download menu, used on single photos for the download dropdown.
 */
function add_navigation_menu_download( $menus ) {
	$menu = array();

	if ( is_singular( get_photo_post_type() ) ) {
		$photo_sizes = [
			'medium_large' => [
				// translators: %s is the image size.
				'label' => __( 'Small %s', 'wporg-photos' ),
				'className' => 'is-download-link',
			],
			'1536x1536' => [
				// translators: %s is the image size.
				'label' => __( 'Medium %s', 'wporg-photos' ),
				'className' => 'is-download-link',
			],
			'2048x2048' => [
				// translators: %s is the image size.
				'label' => __( 'Large %s', 'wporg-photos' ),
				'className' => 'is-download-link',
			],
			'full' => [
				// translators: %s is the image size.
				'label' => __( 'Original Size %s', 'wporg-photos' ),
				'className' => 'is-download-link',
			],
		];

		$photo_id = get_post_thumbnail_id();
		$photo_meta = wp_get_attachment_metadata( $photo_id );
		foreach ( array_keys( $photo_sizes ) as $size ) {
			$src = wp_get_attachment_image_src( $photo_id, $size );
			if ( 'full' === $size ) {
				$filesize = $photo_meta['filesize'] ?? '';
			} else {
				$filesize = $photo_meta['sizes'][ $size ]['filesize'] ?? '';
			}
			$photo_sizes[ $size ]['url'] = $src[0];
			$photo_sizes[ $size ]['label'] = sprintf(
				$photo_sizes[ $size ]['label'],
				sprintf(
					'<span class=\'is-download-dimensions\'>(%s&times;%s)</span> <span class=\'is-download-filesize\'>%s</span>',
					$src[1],
					$src[2],
					size_format( $filesize )
				)
			);
		}

		$menu[] = array(
			'label' => __( 'Download', 'wporg-photos' ),
			'submenu' => $photo_sizes,
		);
	}

	$menus['download'] = $menu;
	return $menus;
}

/**
 * Set up the user menu, used on author archives & favorites pages.
 */
function add_navigation_menu_user( $menus ) {
	$tab = '';
	if ( is_author() ) {
		$user_obj = get_queried_object();
		$tab = 'author';
	} else {
		$user_obj = get_favorites_user();
		$tab = 'favorites';
	}

	if ( ! is_a( $user_obj, 'WP_User' ) ) {
		return $menus;
	}

	$user = $user_obj->user_nicename;

	$menu = array(
		array(
			'label' => __( 'Favorites', 'wporg-photos' ),
			'url' => "/favorites/$user/",
			'className' => 'favorites' === $tab ? 'current-menu-item' : '',
		),
		array(
			'label' => __( 'Contributed', 'wporg-photos' ),
			'url' => "/author/$user/",
			'className' => 'author' === $tab ? 'current-menu-item' : '',
		),
	);

	$menus['user'] = $menu;
	return $menus;
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
 * @return array New list of orientation options.
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
 * Provide a list of sort options.
 *
 * Currently supports sorting by date. Popular (by favorite count)
 * will be added when more data is available.
 *
 * @param array $options The options for this filter.
 * @return array New list of sort options.
 */
function get_sort_options( $options ) {
	global $wp_query;
	$orderby = strtolower( $wp_query->get( 'orderby', 'date' ) );
	$order = strtolower( $wp_query->get( 'order', 'desc' ) );
	$sort = $orderby . '_' . $order;

	$label = __( 'Sort', 'wporg-photos' );
	switch ( $sort ) {
		case 'date_desc':
			$label = __( 'Sort: Newest', 'wporg-photos' );
			break;
		case 'date_asc':
			$label = __( 'Sort: Oldest', 'wporg-photos' );
			break;
	}

	$options = array(
		'date_desc' => __( 'Newest', 'wporg-photos' ),
		'date_asc' => __( 'Oldest', 'wporg-photos' ),
	);

	// Sort can be on the archive page, author page, or a user's favorites.
	// The submission URL needs to be adjusted accordingly.
	$action_url = home_url( '/' );
	if ( is_author() ) {
		$user_obj = get_queried_object();
		$tab = 'author';
	} else {
		// Returns false if not on a favorites page.
		$user_obj = get_favorites_user();
		$tab = 'favorites';
	}
	if ( is_a( $user_obj, 'WP_User' ) ) {
		$user = $user_obj->user_nicename;
		$action_url = home_url( "/$tab/$user/" );
	}

	return array(
		'label' => $label,
		'title' => __( 'Sort', 'wporg-photos' ),
		'key' => 'orderby',
		'action' => $action_url,
		'options' => $options,
		'selected' => [ $sort ],
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

	$query_vars = [ 'photo_category', 'photo_color', 'photo_orientation', 'orderby' ];
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
 * Configure the favorite button.
 *
 * @param array $settings Array of settings for this filter.
 * @param int   $post_id  The current post ID.
 *
 * @return array|bool Settings array or false if not a theme.
 */
function get_favorite_settings( $settings, $post_id ) {
	return array(
		'is_favorite' => Favorites::is_favorited_photo( $post_id ),
		'add_callback' => function( $_post_id ) {
			return Favorites::favorite_photo( $_post_id, get_current_user_id() );
		},
		'delete_callback' => function( $_post_id ) {
			return Favorites::unfavorite_photo( $_post_id, get_current_user_id() );
		},
	);
}

/**
 * Update the link in `wporg/link-wrapper` to use the current post permalink.
 *
 * @param string $block_content The block content.
 *
 * @return string The updated block.
 */
function inject_permalink_link_wrapper( $block_content ) {
	return str_replace( 'href=""', 'href="' . get_permalink() . '"', $block_content );
}

/**
 * Add an "Alt text" label in the post content block on photos.
 *
 * @param string $block_content The block content.
 *
 * @return string The updated block.
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

/**
 * Add the alt text (post content) to the image tag (featured image).
 *
 * @param string   $block_content The block content.
 * @param array    $block         The full block, including name and attributes.
 * @param WP_Block $instance      The block instance.
 *
 * @return string The updated block.
 */
function inject_img_alt_text( $block_content, $block, $instance ) {
	$post_id = $instance->context['postId'];

	$html = \WP_HTML_Processor::create_fragment( $block_content );
	if ( $html->next_tag( array( 'tag_name' => 'IMG' ) ) ) {
		$alt_text = get_the_content( '', '', $post_id );
		$html->set_attribute( 'alt', esc_attr( $alt_text ) );
	}

	return (string) $html;
}

/**
 * Fix the featured image size hints on archive pages to match the real grid sizes.
 *
 * @param string   $block_content The block content.
 * @param array    $block         The full block, including name and attributes.
 * @param WP_Block $instance      The block instance.
 *
 * @return string The updated block.
 */
function inject_img_sizes( $block_content, $block, $instance ) {
	if ( is_singular() ) {
		// Single photos can use the default sizes, which assumes the image will be full-width (it is).
		return $block_content;
	}

	$post_id = $instance->context['postId'];

	$html = \WP_HTML_Processor::create_fragment( $block_content );
	$sizes = [
		'(max-width: 600px) calc(100dvw - 40px - 2px)', // 1 column, 2*20px edge spacing, 2px border.
		'(max-width: 782px) calc(50dvw - 30px - 2px)', // 2 column, 2*20px edge spacing, 20px gap, 2px border.
		'(max-width: 890px) calc(33.33dvw - 26.67px - 2px)', // 3 columns, 2*20px edge spacing, 2*20px gap, 2px border.
		'(max-width: 1280px) calc(33.33dvw - 50px - 2px)', // 3 columns, 2*80px edge spacing, 2*20px gap, 2px border.
		'(max-width: 1920px) calc(25dvw - 55px - 2px)', // 4 columns, 2*80px edge spacing, 3*20px gap, 2px border.
		'423px', // Static value due to the max-width on the grid container.
	];

	if ( $html->next_tag( array( 'tag_name' => 'IMG' ) ) ) {
		$html->set_attribute( 'sizes', join( ', ', $sizes ) );
		// Switch out the default src for the medium version.
		// This is used if srcset is unsupported, though this is very unlikely.
		$html->set_attribute( 'src', esc_url( get_the_post_thumbnail_url( $post_id, 'medium' ) ) );
	}

	return (string) $html;
}

/**
 * Add the download, rel, and target attributes to the navigation link.
 *
 * @param string $block_content The block content.
 * @param array  $block         The full block, including name and attributes.
 *
 * @return string The updated block.
 */
function inject_nav_download_attribute( $block_content, $block ) {
	if ( str_contains( $block['attrs']['className'], 'is-download-link' ) ) {
		$html = \WP_HTML_Processor::create_fragment( $block_content );
		if ( $html->next_tag( array( 'tag_name' => 'A' ) ) ) {
			$html->set_attribute( 'download', true );
			$html->set_attribute( 'rel', 'nofollow' );
			$html->set_attribute( 'target', '_blank' );
		}

		return (string) $html;
	}

	return $block_content;
}

/**
 * Update the archive title for all filter views.
 *
 * @param string $block_content The block content.
 */
function update_site_title( $block_content ) {
	return str_replace(
		get_bloginfo( 'name' ),
		__( 'Photos', 'wporg-photos' ),
		$block_content
	);
}

/**
 * Set the user ID for the avatar block on "favorites" page.
 *
 * @param array $parsed_block An associative array of the block being rendered.
 *
 * @return array The updated block.
 */
function avatar_set_favorites_user_id( $parsed_block ) {
	if ( 'core/avatar' !== $parsed_block['blockName'] ) {
		return $parsed_block;
	}

	$favorite_user = get_favorites_user();
	if ( $favorite_user ) {
		$parsed_block['attrs']['userId'] = $favorite_user->ID;
	}

	return $parsed_block;
}

/**
 * Switch out the "no results" pattern depending on user & page.
 *
 * @param array $parsed_block An associative array of the block being rendered.
 *
 * @return array The updated block.
 */
function pattern_update_no_results( $parsed_block ) {
	if (
		'core/pattern' !== $parsed_block['blockName'] ||
		'wporg-photos-2024/no-results' !== $parsed_block['attrs']['slug']
	) {
		return $parsed_block;
	}

	$favorite_user = get_favorites_user();
	if ( $favorite_user ) {
		if ( get_current_user_id() === $favorite_user->ID ) {
			$parsed_block['attrs']['slug'] = 'wporg-photos-2024/no-results-fav-mine';
		} else {
			$parsed_block['attrs']['slug'] = 'wporg-photos-2024/no-results-fav';
		}
	} else if ( is_author() ) {
		if ( is_author( get_current_user_id() ) ) {
			$parsed_block['attrs']['slug'] = 'wporg-photos-2024/no-results-author-mine';
		} else {
			$parsed_block['attrs']['slug'] = 'wporg-photos-2024/no-results-author';
		}
	}

	return $parsed_block;
}
