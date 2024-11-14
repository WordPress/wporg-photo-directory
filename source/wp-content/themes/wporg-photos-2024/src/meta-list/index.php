<?php
/**
 * Block Name: Meta List
 * Description: Display a photo's metadata as a list.
 *
 * @package wporg
 */

namespace WordPressdotorg\Theme\Photo_Directory_2024\Meta_List;

use WordPressdotorg\Photo_Directory\Photo;

add_action( 'init', __NAMESPACE__ . '\init' );

/**
 * Register the block.
 */
function init() {
	register_block_type(
		dirname( dirname( __DIR__ ) ) . '/build/meta-list',
		array(
			'render_callback' => __NAMESPACE__ . '\render',
		)
	);
}

/**
 * Render the block content.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the block markup.
 */
function render( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$list_items = array();

	$meta_fields = array(
		array(
			'label' => __( 'Date published', 'wporg-photos' ),
			'key' => 'post.post_date',
		),
		array(
			'label' => __( 'Dimensions', 'wporg-photos' ),
			'key' => 'other.dimensions',
		),
		array(
			'label' => __( 'Colors', 'wporg-photos' ),
			'key' => 'taxonomy.photo_color',
		),
		array(
			'label' => __( 'Categories', 'wporg-photos' ),
			'key' => 'taxonomy.photo_category',
		),
		array(
			'label' => __( 'Orientation', 'wporg-photos' ),
			'key' => 'taxonomy.photo_orientation',
		),
		array(
			'label' => __( 'Tags', 'wporg-photos' ),
			'key' => 'taxonomy.photo_tag',
		),
		array(
			'label' => __( 'Aperture', 'wporg-photos' ),
			'key' => 'exif.aperture',
		),
		array(
			'label' => __( 'Focal length', 'wporg-photos' ),
			'key' => 'exif.focal_length',
		),
		array(
			'label' => __( 'ISO', 'wporg-photos' ),
			'key' => 'exif.iso',
		),
		array(
			'label' => __( 'Shutter speed', 'wporg-photos' ),
			'key' => 'exif.shutter_speed',
		),
	);
	$show_label = $attributes['showLabel'] ?? false;

	if ( isset( $attributes['meta'] ) ) {
		$meta_fields = array_filter(
			$meta_fields,
			function( $field ) use ( $attributes ) {
				return in_array( $field['key'], $attributes['meta'] );
			}
		);
	}

	foreach ( $meta_fields as $field ) {
		list( $type, $key ) = explode( '.', $field['key'], 2 );
		$value = get_value( $type, $key, $block->context['postId'] );

		if ( ! empty( $value ) ) {
			$list_items[] = sprintf(
				'<li class="is-meta-%1$s">
					<span%2$s>%3$s</span>
					<span>%4$s</span>
				</li>',
				sanitize_title( $field['key'] ),
				$show_label ? '' : ' class="screen-reader-text"',
				$field['label'],
				wp_kses_post( $value )
			);
		}
	}

	$class = $show_label ? '' : 'has-hidden-label';
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $class ) );
	return sprintf(
		'<div %s><ul>%s</ul></div>',
		$wrapper_attributes,
		join( '', $list_items )
	);
}

/**
 * Retrieves a value from a given photo.
 *
 * @param string $type    Type of value (taxonomy, meta, etc).
 * @param string $key     Name of meta information.
 * @param string $post_id ID of the post to look up.
 *
 * @return string
 */
function get_value( $type, $key, $post_id ) {
	$value = '';
	switch ( $type ) {
		case 'post':
			$post = get_post( $post_id );
			$value = $post->$key;
			if ( 'post_date' === $key ) {
				$value = date_i18n( 'F jS, Y', strtotime( $value ) );
			}
			break;
		case 'exif':
			$exif = Photo::get_exif( $post_id );
			if ( isset( $exif[ $key ] ) ) {
				$value = $exif[ $key ]['value'];
			}
			break;
		case 'taxonomy':
			$value = get_the_term_list( $post_id, $key, '', ', ' );
			break;
		default:
			if ( 'dimensions' === $key ) {
				$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'original' );
				if ( $image_src ) {
					$value = sprintf(
						// translators: %s is the dimensions of the image in pixels.
						__( '%s pixels', 'wporg-photos' ),
						$image_src[1] . ' &times; ' . $image_src[2]
					);
				}
			}
			break;
	}

	if ( is_wp_error( $value ) ) {
		return '';
	}

	return $value;
}
