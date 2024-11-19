<?php

if ( ! $block->context['postId'] ) {
	return '';
}

$photo_post = get_post( $block->context['postId'] );
if ( ! $photo_post ) {
	return '';
}

$rtf_content = sprintf(
	/* translators: 1: URL to CC0 license, 2: URL to photo's page, 3: URL to contributor's profile, 4: Contributor's display name, 5: URL to Photo Directory. */
	__( '<a href="%1$s">CC0</a> licensed <a href="%2$s">photo</a> by <a href="%3$s">%4$s</a> from the <a href="%5$s">WordPress Photo Directory</a>.', 'wporg-photos' ),
	'https://creativecommons.org/share-your-work/public-domain/cc0/',
	esc_url( get_the_permalink( $photo_post->ID ) ),
	esc_url( get_author_posts_url( get_the_author_meta( 'ID', $photo_post->post_author ) ) ),
	esc_html( get_the_author_meta( 'display_name', $photo_post->post_author ) ),
	esc_url( home_url( '/' ) )
);
$html_content = sprintf(
	/* translators: 1: URL to CC0 license, 2: URL to photo's page, 3: URL to contributor's profile, 4: Contributor's display name, 5: URL to Photo Directory. */
	htmlentities( '<p class="attribution">' . __( '<a href="%1$s">CC0</a> licensed <a href="%2$s">photo</a> by <a href="%3$s">%4$s</a> from the <a href="%5$s">WordPress Photo Directory</a>.', 'wporg-photos' ) . '</p>' ),
	'https://creativecommons.org/share-your-work/public-domain/cc0/',
	esc_url( get_the_permalink( $photo_post->ID ) ),
	esc_url( get_author_posts_url( get_the_author_meta( 'ID', $photo_post->post_author ) ) ),
	esc_html( get_the_author_meta( 'display_name', $photo_post->post_author ) ),
	esc_url( home_url( '/' ) )
);
$txt_content = sprintf(
	/* translators: 1: Contributor's display name, 4: URL to photo's page. */
	__( 'CC0 licensed photo by %1$s from the WordPress Photo Directory: %2$s', 'wporg-photos' ),
	esc_html( get_the_author_meta( 'display_name', $photo_post->post_author ) ),
	esc_url( get_the_permalink( $photo_post->ID ) ),
);

// Initial state to pass to Interactivity API.
$init_state = [
	'i18n' => [
		'copySuccess' => __( 'Copied!', 'wporg-photos' ),
		'copyDefault' => __( 'Copy', 'wporg-photos' ),
	],
	'tab' => 'rtf',
];

$id_prefix = wp_unique_id( 'attr-' );

?>
<div
	<?php echo get_block_wrapper_attributes();  // phpcs:ignore ?>
	data-wp-interactive="wporg/photos/photo-attribution"
	<?php echo wp_interactivity_data_wp_context( $init_state );  // phpcs:ignore ?>
	data-wp-on-document--copy="callbacks.copyListener"
	data-wp-class--is-loaded="state.isLoaded"
>
	<div class="wporg-photo-attribution__tablist" role="tablist" aria-label="<?php esc_attr_e( 'Attribution options', 'wporg-photos' ); ?>">
		<button
			class="wporg-photo-attribution__tab"
			role="tab"
			data-tab="rtf"
			data-wp-bind--aria-selected="state.isCurrentTab"
			aria-controls="<?php echo esc_attr( $id_prefix . '-panel-rtf' ); ?>"
			id="<?php echo esc_attr( $id_prefix . '-tab-rtf' ); ?>"
			data-wp-bind--tabindex="state.tabIndex"
			data-wp-on--click="actions.openTab"
			data-wp-on--keydown="actions.onKeyDown"
		><?php esc_html_e( 'Rich Text', 'wporg-photos' ); ?></button>
		<button
			class="wporg-photo-attribution__tab"
			role="tab"
			data-tab="html"
			data-wp-bind--aria-selected="state.isCurrentTab"
			aria-controls="<?php echo esc_attr( $id_prefix . '-panel-html' ); ?>"
			id="<?php echo esc_attr( $id_prefix . '-tab-html' ); ?>"
			data-wp-bind--tabindex="state.tabIndex"
			data-wp-on--click="actions.openTab"
			data-wp-on--keydown="actions.onKeyDown"
		><?php esc_html_e( 'HTML', 'wporg-photos' ); ?></button>
		<button
			class="wporg-photo-attribution__tab"
			role="tab"
			data-tab="txt"
			data-wp-bind--aria-selected="state.isCurrentTab"
			aria-controls="<?php echo esc_attr( $id_prefix . '-panel-txt' ); ?>"
			id="<?php echo esc_attr( $id_prefix . '-tab-txt' ); ?>"
			data-wp-bind--tabindex="state.tabIndex"
			data-wp-on--click="actions.openTab"
			data-wp-on--keydown="actions.onKeyDown"
		><?php esc_html_e( 'Plain text', 'wporg-photos' ); ?></button>
	</div>
	<div
		class="wporg-photo-attribution__tabpanel"
		id="<?php echo esc_attr( $id_prefix . '-panel-rtf' ); ?>"
		role="tabpanel"
		data-tab="rtf"
		tabindex="0"
		aria-labelledby="<?php echo esc_attr( $id_prefix . '-tab-rtf' ); ?>"
		data-wp-bind--hidden="!state.isCurrentTab"
	>
		<?php echo $rtf_content; // phpcs:ignore ?>
	</div>
	<div 
		class="wporg-photo-attribution__tabpanel"
		id="<?php echo esc_attr( $id_prefix . '-panel-html' ); ?>"
		role="tabpanel"
		data-tab="html"
		tabindex="0"
		aria-labelledby="<?php echo esc_attr( $id_prefix . '-tab-html' ); ?>"
		data-wp-bind--hidden="!state.isCurrentTab"
	>
		<?php echo $html_content; // phpcs:ignore ?>
	</div>
	<div
		class="wporg-photo-attribution__tabpanel"
		id="<?php echo esc_attr( $id_prefix . '-panel-txt' ); ?>"
		role="tabpanel"
		data-tab="txt"
		tabindex="0"
		aria-labelledby="<?php echo esc_attr( $id_prefix . '-tab-txt' ); ?>"
		data-wp-bind--hidden="!state.isCurrentTab"
	>
		<?php echo $txt_content; // phpcs:ignore ?>
	</div>
	<div class="wporg-photo-attribution__button-copy wp-block-button is-small">
		<button
			class="wp-block-button__link wp-element-button"
			data-wp-on--click="actions.copyText"
			data-wp-text="state.buttonLabel"
		><?php esc_html_e( 'Copy', 'wporg-photos' ); ?></button>
	</div>
</div>
