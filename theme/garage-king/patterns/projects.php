<?php
/**
 * Title: Featured projects
 * Slug: garage-king/projects
 * Categories: garage-king
 */
$ph = array(
	array( '2021/04/20190718_125822-scaled.jpg', 'Slatwall wall system' ),
	array( '2021/04/20190227_103359-scaled.jpg', 'Overhead storage rack' ),
	array( '2012/03/Slatwall-Garage-King-882x563.jpg', 'Slatwall and trim' ),
);
?>
<!-- wp:group {"align":"full","className":"gk-section","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignfull gk-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">See the transformation</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Drag the slider to compare. This is a sample pair of photos; real before and after pairs come from the client.</p>
<!-- /wp:paragraph -->
<!-- wp:html -->
<?php echo gk_before_after( 'sample-before-slatwall.jpg', 'sample-after-slatwall.jpg', 'A wall before and after slatwall was installed', 'Sample: slatwall install' ); ?>
<!-- /wp:html -->
<!-- wp:columns {"className":"gk-gallery"} -->
<div class="wp-block-columns gk-gallery">
<?php foreach ( $ph as $p ) : ?>
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","className":"gk-photo"} -->
<figure class="wp-block-image size-large gk-photo"><img src="<?php echo gk_placeholder( $p[0] ); ?>" alt="<?php echo esc_attr( $p[1] ); ?> (placeholder photo)"/><figcaption class="wp-element-caption"><?php echo esc_html( $p[1] ); ?></figcaption></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/garage-storage-photos">See our work</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/garage-storage-photos/project-stories">Project stories (coming soon)</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
