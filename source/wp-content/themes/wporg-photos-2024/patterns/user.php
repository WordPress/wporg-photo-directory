<?php
/**
 * Title: User Profile
 * Slug: wporg-photos-2024/user
 * Inserter: no
 */

?>
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:avatar {"size":100,"style":{"border":{"radius":"100%"}}} /-->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":1,"metadata":{"bindings":{"content":{"source":"wporg-photos/user-name"}}}} -->
		<h1 class="wp-block-heading">Username</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"wporg-photos/user-link"}}}} -->
		<p><a href="#">See WordPress.org Profile</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide">
	<!-- wp:navigation {"menuSlug":"user","ariaLabel":"<?php esc_attr_e( 'User menu', 'wporg-photos' ); ?>","overlayMenu":"never","layout":{"type":"flex","orientation":"horizontal","justifyContent":"left","flexWrap":"nowrap"},"fontSize":"small","className":"is-style-button-list"} /-->
</div>
<!-- /wp:group -->
