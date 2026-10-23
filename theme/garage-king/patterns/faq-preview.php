<?php
/**
 * Title: FAQ preview
 * Slug: garage-king/faq-preview
 * Categories: garage-king
 */
$qa = array(
	array( 'Are you a franchise?', 'No. The Garage King is an independent, family-owned and operated Central Texas business, and every installation is done by our own crew.' ),
	array( 'How long does an installation take?', 'Most storage solution installations are completed within one day.' ),
	array( 'Do you offer a warranty?', 'Yes. We stand behind our workmanship with a guarantee, and our products carry a product warranty. Details to be confirmed with the client.' ),
	array( 'What areas do you serve?', 'We serve all Greater Austin, Greater San Antonio and surrounding areas. Don\'t see your town? Complete our contact form and we will let you know if we can provide for your needs and service area.' ),
);
?>
<!-- wp:group {"align":"full","className":"gk-section","layout":{"type":"constrained","contentSize":"820px"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignfull gk-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Common questions, answered</h2>
<!-- /wp:heading -->
<?php foreach ( $qa as $q ) : ?>
<!-- wp:details -->
<details class="wp-block-details"><summary><?php echo esc_html( $q[0] ); ?></summary><!-- wp:paragraph {"placeholder":"Answer"} -->
<p><?php echo esc_html( $q[1] ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->
<?php endforeach; ?>
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/faq">All questions</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
