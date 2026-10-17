<?php
/**
 * Title: Product page (overhead storage)
 * Slug: garage-king/product-page
 * Categories: garage-king
 */
$img   = gk_placeholder( '2021/04/20190227_103359-scaled.jpg' );
$sizes = array( '8×4 Enduro Deck', '8×3', '8×2', '6×4', '6×3', '6×2', '4×4', '4×3', '4×2' );
?>
<!-- wp:cover {"url":"<?php echo $img; ?>","dimRatio":70,"overlayColor":"black","minHeight":52,"minHeightUnit":"vh","contentPosition":"center left","align":"full","className":"gk-hero gk-hero--page","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-center-left gk-hero gk-hero--page" style="min-height:52vh"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"gk-eyebrow","textColor":"amber"} -->
<p class="gk-eyebrow has-amber-color has-text-color">Overhead storage solutions</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-white-color has-text-color">Get your garage floor back</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","fontSize":"medium"} -->
<p class="has-muted-color has-text-color has-medium-font-size">Steel storage systems anchored into your roof trusses, anywhere in the garage, without throwing anything away.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/schedule-quote">Book a Free Design Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"className":"gk-section","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"}}}} -->
<div class="wp-block-group gk-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"4rem"}}}} -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading -->
<h2 class="wp-block-heading">What fits overhead</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Holiday decorations, camping gear, bins, luggage and sports equipment. The open-channel deck lets you see what you have from below.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading -->
<h2 class="wp-block-heading">How it is installed</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Steel racks are anchored into the roof trusses, even above the garage door. Weight capacity and safety specifications to be supplied by the client.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Nine sizes, including the exclusive 8′×4′ Enduro Deck</h3>
<!-- /wp:heading -->
<!-- wp:group {"className":"gk-chips gk-chips--light","layout":{"type":"flex","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"0.6rem"}}} -->
<div class="wp-block-group gk-chips gk-chips--light">
<?php foreach ( $sizes as $s ) : ?>
<!-- wp:paragraph {"className":"gk-chip"} -->
<p class="gk-chip"><?php echo esc_html( $s ); ?></p>
<!-- /wp:paragraph -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<!-- wp:paragraph -->
<p><em>Placeholder: a visual size chart and ceiling-height layout diagrams (under and over 9.5 feet) will replace this.</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"garage-king/how-it-works-preview"} /-->
<!-- wp:pattern {"slug":"garage-king/reviews"} /-->
<!-- wp:pattern {"slug":"garage-king/cta-band"} /-->
