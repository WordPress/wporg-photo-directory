<?php
/**
 * Block Name: Photo Attribution
 * Description: Display photo attribution information.
 *
 * @package wporg
 */

 namespace WordPressdotorg\Theme\Photo_Directory_2024\Photo_Attribution_Block;

defined( 'WPINC' ) || die();

add_action( 'init', __NAMESPACE__ . '\init' );

/**
 * Register the block.
 */
function init() {
	register_block_type( __DIR__ . '/../../build/photo-attribution' );
}
