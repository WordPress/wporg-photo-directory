<?php
/**
 * Title: No results
 * Slug: wporg-photos-2024/footer
 * Inserter: no
 */

?>
<!-- wp:group {"align":"wide","className":"is-page-footer","style":{"border":{"bottom":{"color":"var:preset|color|white-opacity-15","style":"solid","width":"1px"}},"elements":{"link":{"color":{"text":"var:preset|color|blueberry-3"}}},"spacing":{"padding":{"right":"var:preset|spacing|edge-space","left":"var:preset|spacing|edge-space"}}},"backgroundColor":"charcoal-2","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-page-footer has-white-color has-charcoal-2-background-color has-text-color has-background has-link-color" style="border-bottom-color:var(--wp--preset--color--white-opacity-15);border-bottom-style:solid;border-bottom-width:1px;padding-right:var(--wp--preset--spacing--edge-space);padding-left:var(--wp--preset--spacing--edge-space)">
	<!-- wp:columns {"align":"wide"} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"style":{"spacing":{"padding":{"right":"var:preset|spacing|edge-space","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"right":{"color":"var:preset|color|white-opacity-15","style":"solid","width":"1px"}}}} -->
		<div class="wp-block-column" style="border-right-color:var(--wp--preset--color--white-opacity-15);border-right-style:solid;border-right-width:1px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--edge-space);padding-bottom:var(--wp--preset--spacing--40)">
			<!-- wp:heading {"fontSize":"large"} -->
			<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'Contribute', 'wporg-photos' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-short-text"} -->
			<p class="is-style-short-text">
				<?php
				echo wp_kses_post(
					sprintf(
						// translators: %s: URL to submit a photo.
						__( 'The WordPress Photo Directory is the perfect place to release your photos into the public domain for the benefit of all. <a href="%s">Submit your photo</a>.</p>', 'wporg-photos' ),
						esc_url( home_url( '/submit/' ) )
					)
				);
				?>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"className":"is-style-short-text","style":{"spacing":{"padding":{"right":"var:preset|spacing|edge-space","left":"var:preset|spacing|edge-space","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-column is-style-short-text" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--edge-space);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--edge-space)">
			<!-- wp:heading {"fontSize":"large"} -->
			<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'License', 'wporg-photos' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-short-text"} -->
			<p class="is-style-short-text">
				<?php
				echo wp_kses_post(
					sprintf(
						// translators: %s: URL to CC0 license information.
						__( 'All photos are <a href="%s">CC0 licensed</a>. No rights are reserved, so you are free to use the photos anywhere, for any purpose, without the need for attribution.', 'wporg-photos' ),
						esc_url( __( 'https://creativecommons.org/share-your-work/public-domain/cc0/', 'wporg-photos' ) )
					)
				);
				?>
			</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"style":{"spacing":{"padding":{"left":"var:preset|spacing|edge-space","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"left":{"color":"var:preset|color|white-opacity-15","style":"solid","width":"1px"}}}} -->
		<div class="wp-block-column" style="border-left-color:var(--wp--preset--color--white-opacity-15);border-left-style:solid;border-left-width:1px;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--edge-space)">
			<!-- wp:heading {"fontSize":"large"} -->
			<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'FAQ', 'wporg-photos' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-short-text"} -->
			<p class="is-style-short-text">
				<?php
				echo wp_kses_post(
					sprintf(
						// translators: %s: URL to FAQ page.
						__( 'Learn more about licensing, usage, and adding your photos to the WordPress Photo Directory via <a href="%s">Frequently Asked Questions</a>.', 'wporg-photos' ),
						esc_url( home_url( '/faq/' ) )
					)
				);
				?>
			</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:wporg/global-footer /-->
