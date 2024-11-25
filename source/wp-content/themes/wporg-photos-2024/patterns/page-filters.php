<?php
/**
 * Title: Filters page
 * Slug: wporg-photos-2024/page-filters
 * Inserter: no
 */

?>
<!-- wp:post-title {"level":1,"fontSize":"heading-3"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:heading -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Categories', 'wporg-photos' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:categories {"taxonomy":"photo_category","showPostCounts":true,"className":"is-tax-photo_categories"} /-->

	<!-- wp:heading -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Colors', 'wporg-photos' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:categories {"taxonomy":"photo_color","showPostCounts":true,"className":"is-tax-photo_color"} /-->

	<!-- wp:heading -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Orientations', 'wporg-photos' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:categories {"taxonomy":"photo_orientation","showPostCounts":true,"className":"is-tax-photo_orientation"} /-->
</div>
<!-- /wp:group -->
