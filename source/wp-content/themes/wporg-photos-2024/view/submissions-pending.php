<?php
/**
 * Display the pending submissions for the current user.
 *
 * This is prepended to the submission form, and run through `do_blocks` to process the block markup.
 */

use WordPressdotorg\Photo_Directory\{ Registrations, User };

$max_pending_submissions = User::get_concurrent_submission_limit( $user_id );
$details = array(
	sprintf(
		// translators: %d is the maximum number of pending submissions allowed.
		_n( 'You can have up to <strong>%d</strong> photo in the moderation queue at a time.', 'You can have up to <strong>%d</strong> photos in the moderation queue at a time.', $max_pending_submissions, 'wporg-photos' ),
		$max_pending_submissions,
	),
	sprintf(
		// translators: %d is the number of currently pending submissions.
		_n( 'You currently have <strong>%d</strong>.', 'You currently have <strong>%d</strong>.', count( $pending ), 'wporg-photos' ),
		count( $pending )
	),
);

?>
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Submissions awaiting moderation', 'wporg-photos' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo wp_kses_post( implode( ' ', $details ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"wporg-submissions-pending__list","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group wporg-submissions-pending__list">
	<?php
	foreach ( $pending as $photo_post ) :
		$image_id = get_post_thumbnail_id( $photo_post );
		$image_url = get_the_post_thumbnail_url( $photo_post->ID, 'medium_large' );
		$image_title = get_post_meta( $photo_post->ID, Registrations::get_meta_key( 'original_filename' ), true ) ?: __( '(unknown)', 'wporg-photos' );
		$image_date = get_the_date( 'Y-m-d', $photo_post );
		$image_caption = get_the_content( null, false, $photo_post ) ?: __( '(none provided)', 'wporg-photos' );
		?>
		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20","right":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|30"},"border":{"style":"solid","width":"1px","color":"#d9d9d9","radius":"2px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group has-border-color" style="border-color:#d9d9d9;border-style:solid;border-width:1px;border-radius:2px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
			<!-- wp:image {"id":<?php echo intval( $image_id ); ?>,"width":"180px","aspectRatio":"16/9","scale":"cover","sizeSlug":"medium","linkDestination":"none"} -->
			<figure class="wp-block-image size-medium is-resized"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'View the photo.', 'wporg-photos' ); ?>" class="wp-image-<?php echo intval( $image_id ); ?>" style="aspect-ratio:16/9;object-fit:cover;width:180px"/></figure>
			<!-- /wp:image -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"className":"is-style-short-text"} -->
				<p class="is-style-short-text"><strong><?php esc_html_e( 'File:', 'wporg-photos' ); ?></strong> <?php echo esc_html( $image_title ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"is-style-short-text"} -->
				<p class="is-style-short-text"><strong><?php esc_html_e( 'Submission date:', 'wporg-photos' ); ?></strong> <?php echo esc_html( $image_date ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"is-style-short-text"} -->
				<p class="is-style-short-text"><strong><?php esc_html_e( 'Caption:', 'wporg-photos' ); ?></strong> <?php echo esc_html( $image_caption ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<?php endforeach; ?>
</div>
<!-- /wp:group -->
