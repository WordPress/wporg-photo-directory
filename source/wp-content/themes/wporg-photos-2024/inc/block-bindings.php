<?php
/**
 * Set up custom block bindings.
 */

namespace WordPressdotorg\Theme\Photo_Directory_2024\Block_Bindings;

use function WordPressdotorg\Theme\Photo_Directory_2024\{get_favorites_user};

add_action( 'init', __NAMESPACE__ . '\init' );

/**
 * Initialize custom block bindings.
 */
function init() {
	register_block_bindings_source(
		'wporg-photos/user-name',
		array(
			'label'              => __( 'User name', 'wporg-photos' ),
			'get_value_callback' => function ( $args, $block_instance ) {
				if ( is_author() ) {
					$user = get_queried_object();
				} else {
					$user = get_favorites_user();
				}

				if ( is_a( $user, 'WP_User' ) ) {
					return $user->display_name;
				}
			},
		)
	);

	register_block_bindings_source(
		'wporg-photos/user-link',
		array(
			'label'              => __( 'User name', 'wporg-photos' ),
			'get_value_callback' => function ( $args, $block_instance ) {
				if ( is_author() ) {
					$user = get_queried_object();
				} else {
					$user = get_favorites_user();
				}

				if ( is_a( $user, 'WP_User' ) ) {
					return sprintf(
						'<a href="%s">%s</a>',
						esc_url( 'https://profiles.wordpress.org/' . $user->user_nicename . '/' ),
						__( 'See WordPress.org Profile', 'wporg-photos' )
					);
				}
			},
		)
	);
}
