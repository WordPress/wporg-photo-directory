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

<!-- wp:table {"hasFixedLayout":alse,"className":"wporg-submissions-pending__list","fontSize":"small"} -->
<figure class="wp-block-table has-small-font-size wporg-submissions-pending__list">
	<table>
		<thead><tr>
			<th><?php esc_html_e( 'File', 'wporg-photos' ); ?></th>
			<th><?php esc_html_e( 'Submission date', 'wporg-photos' ); ?></th>
			<th><?php esc_html_e( 'Caption', 'wporg-photos' ); ?></th>
		</tr></thead>
		<tbody>
		<?php
		foreach ( $pending as $photo_post ) :
			$image_id = get_post_thumbnail_id( $photo_post );
			$image_url = get_the_post_thumbnail_url( $photo_post->ID, 'medium_large' );
			$image_title = get_post_meta( $photo_post->ID, Registrations::get_meta_key( 'original_filename' ), true ) ?: __( '(unknown)', 'wporg-photos' );
			$image_date = get_the_date( 'Y-m-d', $photo_post );
			$image_caption = get_the_content( null, false, $photo_post ) ?: __( '(none provided)', 'wporg-photos' );
			?>
			<tr>
				<td><?php echo esc_html( $image_title ); ?></td>
				<td><?php echo esc_html( $image_date ); ?></td>
				<td><?php echo esc_html( $image_caption ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</figure>
<!-- /wp:table -->
