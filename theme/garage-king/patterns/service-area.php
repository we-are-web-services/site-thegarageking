<?php
/**
 * Title: Service area
 * Slug: garage-king/service-area
 * Categories: garage-king
 */
$cities = array( 'Austin', 'San Antonio', 'Bell County', 'Cedar Park', 'Georgetown', 'Leander', 'Lakeway', 'Liberty Hill', 'New Braunfels', 'Horseshoe Bay', 'Westlake Hills', 'Hill Country' );
?>
<!-- wp:group {"align":"full","backgroundColor":"black","textColor":"white","className":"gk-section","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignfull gk-section has-white-color has-black-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:paragraph {"className":"gk-eyebrow","textColor":"amber"} -->
<p class="gk-eyebrow has-amber-color has-text-color">Service area</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textColor":"white"} -->
<h2 class="wp-block-heading has-white-color has-text-color">Serving Central Texas and the Hill Country</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color">Our crews cover Austin, San Antonio, Bell County and the surrounding communities. The map will go here.</p>
<!-- /wp:paragraph -->
<!-- wp:group {"className":"gk-chips","layout":{"type":"flex","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"0.6rem"}}} -->
<div class="wp-block-group gk-chips">
<?php foreach ( $cities as $c ) : ?>
<!-- wp:paragraph {"className":"gk-chip"} -->
<p class="gk-chip"><?php echo esc_html( $c ); ?></p>
<!-- /wp:paragraph -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/service-areas">Check my area</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
