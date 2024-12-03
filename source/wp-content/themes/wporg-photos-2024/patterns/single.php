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

	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"},"className":"is-entry-header"} -->
	<div class="wp-block-group alignwide is-entry-header">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:avatar {"size":40,"style":{"border":{"radius":"100%"}}} /-->

			<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontStyle":"normal"},"elements":{"link":{"color":{"text":"var:preset|color|charcoal-1"}}}},"textColor":"charcoal-1","fontSize":"small"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"top"}} -->
		<div class="wp-block-group">
			<!-- wp:wporg/favorite-button /-->

			<!-- wp:navigation {"menuSlug":"download","className":"is-download-menu","textColor":"white","backgroundColor":"blueberry-1","openSubmenusOnClick":true,"overlayMenu":"never","icon":"menu","overlayBackgroundColor":"white","overlayTextColor":"charcoal-1","fontSize":"small","layout":{"type":"flex","orientation":"horizontal","flexWrap":"wrap","justifyContent":"right"}} /-->
		</div>
		<!-- /wp:group -->
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

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"fontSize":"heading-6"} -->
			<h2 class="wp-block-heading has-heading-6-font-size">Image info</h2>
			<!-- /wp:heading -->
		
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
			<div class="wp-block-group">
				<!-- wp:wporg/meta-list {"meta":["post.post_date","other.dimensions","taxonomy.photo_color","taxonomy.photo_category","taxonomy.photo_orientation","taxonomy.photo_tag"]} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"35%"} -->
		<div class="wp-block-column" style="flex-basis:35%">
			<!-- wp:heading {"fontSize":"heading-6"} -->
			<h2 class="wp-block-heading has-heading-6-font-size">EXIF data</h2>
			<!-- /wp:heading -->

			<!-- wp:wporg/meta-list {"meta":["exif.aperture","exif.focal_length","exif.iso","exif.shutter_speed"],"className":"is-style-has-border"} /-->
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

		<!-- wp:wporg/photo-attribution /-->
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

<!-- wp:separator {"className":"is-style-wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"backgroundColor":"light-grey-1"} -->
<hr class="wp-block-separator has-text-color has-light-grey-1-color has-alpha-channel-opacity has-light-grey-1-background-color has-background is-style-wide" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)"/>
<!-- /wp:separator -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--30)">
	<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.28"}},"fontSize":"heading-3","fontFamily":"eb-garamond"} -->
	<p class="has-eb-garamond-font-family has-heading-3-font-size" style="line-height:1.28"><?php echo wp_kses_post( __( '<em>Discover more</em> content submitted by the WordPress community', 'wporg-photos' ) ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex"}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/filters/' ) ); ?>"><?php esc_html_e( 'Browse the filters', 'wporg-photos' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/random/' ) ); ?>"><?php esc_html_e( 'Take me to a random photo', 'wporg-photos' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"60px","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<div style="margin-top:0;margin-bottom:0;height:60px" aria-hidden="true" class="wp-block-spacer alignwide"></div>
<!-- /wp:spacer -->
