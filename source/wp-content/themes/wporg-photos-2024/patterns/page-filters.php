<?php
/**
 * Title: Filters page
 * Slug: wporg-photos-2024/page-filters
 * Inserter: no
 */

?>
<!-- wp:post-title {"level":1,"fontSize":"heading-3"} /-->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0">
	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Categories', 'wporg-photos' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:categories {"taxonomy":"photo_category","showPostCounts":true,"className":"is-tax-photo_categories"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Colors', 'wporg-photos' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:categories {"taxonomy":"photo_color","showPostCounts":true,"className":"is-tax-photo_color"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Orientations', 'wporg-photos' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:categories {"taxonomy":"photo_orientation","showPostCounts":true,"className":"is-tax-photo_orientation"} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
