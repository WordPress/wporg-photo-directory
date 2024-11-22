<?php
/**
 * Title: Photo Grid (no filters)
 * Slug: wporg-photos-2024/grid-simple
 * Inserter: no
 */

?>
<!-- wp:query {"queryId":0,"query":{"inherit":true},"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group">
			<!-- wp:wporg/query-total /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"var:preset|spacing|40","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
	<div style="margin-top:0;margin-bottom:0;height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"grid","columnCount":4}} -->
		<!-- wp:wporg/link-wrapper {"className":"is-style-no-underline"} -->
		<a class="wp-block-wporg-link-wrapper is-style-no-underline" href="">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0"},"border":{"radius":"2px","style":"solid","width":"1px"}},"borderColor":"black-opacity-15"} -->
			<div class="wp-block-group has-border-color has-black-opacity-15-border-color" style="border-style:solid;border-width:1px;border-radius:2px">
				<!-- wp:post-featured-image {"aspectRatio":"16/9"} /-->
			</div>
			<!-- /wp:group -->
		</a>
		<!-- /wp:wporg/link-wrapper -->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous {"label":"<?php esc_attr_e( 'Previous', 'wporg-photos' ); ?>"} /-->

		<!-- wp:query-pagination-numbers /-->

		<!-- wp:query-pagination-next {"label":"<?php esc_attr_e( 'Next', 'wporg-photos' ); ?>"} /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:pattern {"slug":"wporg-photos-2024/no-results"} /-->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->

<!-- wp:spacer {"height":"60px","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|50"}}}} -->
<div style="margin-top:0;margin-bottom:var(--wp--preset--spacing--50);height:60px" aria-hidden="true" class="wp-block-spacer alignwide"></div>
<!-- /wp:spacer -->
