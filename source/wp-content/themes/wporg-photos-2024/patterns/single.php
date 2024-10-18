<?php
/**
 * Title: Photo Detail
 * Slug: wporg-photos-2024/single
 * Inserter: no
 */

?>
<!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide">
	
	<!-- wp:post-title {"level":1,"fontSize":"heading-3"} /-->

	<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"},"align":"wide"} -->
	<div class="wp-block-group alignwide">
		<!-- wp:wporg/favorite-button /-->

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:avatar {"size":24,"style":{"border":{"radius":"100%"}}} /-->

			<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontStyle":"normal"},"elements":{"link":{"color":{"text":"var:preset|color|charcoal-1"}}}},"textColor":"charcoal-1","fontSize":"small"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","backgroundColor":"light-grey-1"} -->
<div class="wp-block-group alignwide has-light-grey-1-background-color has-background">
	<!-- wp:post-featured-image /-->
</div>
<!-- /wp:group -->

<!-- wp:post-terms {"term":"photo_tag","prefix":"Tags "} /-->
<!-- wp:post-terms {"term":"photo_category","prefix":"Category "} /-->
<!-- wp:post-terms {"term":"photo_color","prefix":"Color "} /-->
<!-- wp:post-terms {"term":"photo_orientation","prefix":"Orientation "} /-->

<!-- wp:spacer {"height":"60px","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|50"}}}} -->
<div style="margin-top:0;margin-bottom:var(--wp--preset--spacing--50);height:60px" aria-hidden="true" class="wp-block-spacer alignwide"></div>
<!-- /wp:spacer -->
