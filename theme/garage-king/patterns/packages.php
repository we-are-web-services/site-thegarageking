<?php
/**
 * Title: Packages and pricing teaser
 * Slug: garage-king/packages
 * Categories: garage-king
 */
$pk = array(
	array( 'The Emerson', 'A starter combination of overhead racks and slatwall.' ),
	array( 'The Duke', 'Our larger combination for bigger garages.' ),
	array( 'The Earl', 'Package details to come from the client.' ),
);
?>
<!-- wp:group {"align":"full","backgroundColor":"ink","textColor":"white","className":"gk-section","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignfull gk-section has-white-color has-ink-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:paragraph {"className":"gk-eyebrow","textColor":"amber"} -->
<p class="gk-eyebrow has-amber-color has-text-color">Pricing</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textColor":"white"} -->
<h2 class="wp-block-heading has-white-color has-text-color">Packages and a price estimator</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color">Three popular combinations, plus a project estimator so you can see an investment range before you call. Prices and the estimator are placeholders until the client confirms them.</p>
<!-- /wp:paragraph -->
<!-- wp:columns {"className":"gk-cards","style":{"spacing":{"blockGap":{"top":"1.25rem","left":"1.25rem"}}}} -->
<div class="wp-block-columns gk-cards">
<?php foreach ( $pk as $i => $c ) : ?>
<!-- wp:column {"className":"gk-card"} -->
<div class="wp-block-column gk-card"><!-- wp:heading {"level":3,"textColor":"amber"} -->
<h3 class="wp-block-heading has-amber-color has-text-color"><?php echo esc_html( $c[0] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html( $c[1] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"white","fontSize":"small"} -->
<p class="has-white-color has-text-color has-small-font-size"><strong>Price: to be confirmed</strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
<!-- wp:group {"className":"gk-estimator","backgroundColor":"steel","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
<div class="wp-block-group gk-estimator has-steel-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)"><!-- wp:heading {"level":3,"textColor":"white"} -->
<h3 class="wp-block-heading has-white-color has-text-color">Project estimator (placeholder)</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color">1. Choose your service area &nbsp;·&nbsp; 2. Choose your solutions &nbsp;·&nbsp; 3. Pick quantities &nbsp;·&nbsp; See an estimated investment. The calculator rules and prices are not decided yet.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/garage-storage-pricing">See pricing</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
