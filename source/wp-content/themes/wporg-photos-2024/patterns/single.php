<?php
/**
 * Title: Photo Detail
 * Slug: wporg-photos-2024/single
 * Inserter: no
 */

?>
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group alignwide">
	
	<!-- wp:post-title {"level":1,"className":"screen-reader-text"} /-->

	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:avatar {"size":40,"style":{"border":{"radius":"100%"}}} /-->

			<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontStyle":"normal"},"elements":{"link":{"color":{"text":"var:preset|color|charcoal-1"}}}},"textColor":"charcoal-1","fontSize":"small"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons {"className":"wporg-theme-actions","layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-buttons wporg-theme-actions">
			<!-- wp:wporg/favorite-button /-->

			<!-- wp:button {"metadata":{"bindings":{"url":{"source":"wporg-themes/meta","args":{"key":"download-url"}}}}} -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Download</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}},"backgroundColor":"light-grey-2"} -->
<div class="wp-block-group alignwide has-light-grey-2-background-color has-background" style="margin-top:var(--wp--preset--spacing--30)">
	<!-- wp:post-featured-image {"aspectRatio":"16/9","scale":"contain"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"top":"var:preset|spacing|30"}}}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--30)">
	<!-- wp:post-content /-->

	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"fontSize":"heading-6"} -->
			<h2 class="wp-block-heading has-heading-6-font-size">Image info</h2>
			<!-- /wp:heading -->
		
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
				<p>[block]</p>
				<!-- /wp:paragraph -->

				<!-- wp:post-terms {"term":"photo_tag","prefix":"Tags "} /-->

				<!-- wp:post-terms {"term":"photo_category","prefix":"Category "} /-->

				<!-- wp:post-terms {"term":"photo_color","prefix":"Color "} /-->

				<!-- wp:post-terms {"term":"photo_orientation","prefix":"Orientation "} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"fontSize":"heading-6"} -->
			<h2 class="wp-block-heading has-heading-6-font-size">EXIF data</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>[block]</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"fontSize":"heading-6"} -->
		<h2 class="wp-block-heading has-heading-6-font-size">Attribution</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Photo attribution is not necessary, but appreciated. If you'd like to give credit to the photographer, feel free to use this text:</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>[block]</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"fontSize":"heading-6"} -->
		<h2 class="wp-block-heading has-heading-6-font-size">License/usage</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Photo contributors submit their original content under the CC0 license. This license allows everyone to use the photos anywhere, for any purpose, without the need for permission, attribution, or payment. However, you cannot claim ownership or authorship of any photos in the WordPress Photo Directory, out of respect for the original photographers. Submissions are moderated by a team of volunteers who recommend prior to use that you verify that the work is actually under the CC0 license and abides by any applicable local laws.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"60px","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|50"}}}} -->
<div style="margin-top:0;margin-bottom:var(--wp--preset--spacing--50);height:60px" aria-hidden="true" class="wp-block-spacer alignwide"></div>
<!-- /wp:spacer -->
