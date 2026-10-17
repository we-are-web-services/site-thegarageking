<?php
/**
 * Title: Home hero
 * Slug: garage-king/home-hero
 * Categories: garage-king
 */
$img = gk_placeholder( '2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg' );
?>
<!-- wp:cover {"url":"<?php echo $img; ?>","dimRatio":75,"overlayColor":"black","minHeight":86,"minHeightUnit":"vh","contentPosition":"center left","align":"full","className":"gk-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-center-left gk-hero" style="min-height:86vh"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-75 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"gk-eyebrow","textColor":"amber"} -->
<p class="gk-eyebrow has-amber-color has-text-color">Texas family owned and operated</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-white-color has-text-color">Park in your <mark style="background-color:rgba(0,0,0,0)" class="has-inline-color has-amber-color">garage</mark> again.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"medium","style":{"layout":{"selfStretch":"fixed","flexSize":"36rem"}}} -->
<p class="has-muted-color has-text-color has-medium-font-size">Overhead storage solutions, slatwall systems and complete garage renovations, professionally installed for Central Texas homeowners. Most installations are finished in a single day.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"1.5rem"}}} -->
<div class="wp-block-group"><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/schedule-quote">Book a Free Design Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- wp:paragraph {"textColor":"white","className":"gk-rating"} -->
<p class="gk-rating has-white-color has-text-color">★★★★★ <strong>4.9</strong> Google rating</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
