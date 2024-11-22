<?php
/**
 * Title: No results
 * Slug: wporg-photos-2024/no-results-fav-mine
 * Inserter: no
 */

?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:paragraph {"className":"has-text-align-center","style":{"typography":{"lineHeight":"1.28"}},"fontSize":"heading-3","fontFamily":"eb-garamond"} -->
	<p class="has-text-align-center has-eb-garamond-font-family has-heading-3-font-size" style="line-height:1.28"><?php esc_html_e( 'You haven’t favorited any photos yet.', 'wporg-photos' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Explore all photos', 'wporg-photos' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/filters/' ) ); ?>"><?php esc_html_e( 'Browse the filters', 'wporg-photos' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
