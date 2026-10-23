<?php
/**
 * Title: Solutions
 * Slug: garage-king/solutions
 * Categories: garage-king
 */
$cards = array(
	array( '01', 'Overhead Storage Solutions', 'Heavy duty overhead storage racks in nine sizes, to get your seasonal items up and out of the way.', '/overhead-racks' ),
	array( '02', 'Motorized Overhead Racks', 'Automated, remote controlled storage: press a button and the platform lowers to you. No ladders.', '/motorized-overhead-racks' ),
	array( '03', 'Slatwall', 'A heavy duty storage solution system with a dust free, durable surface and a full accessory system.', '/slatwall' ),
	array( '04', 'Garage Cabinets', 'Fully welded steel cabinets. A new line is on the way.', '/steel-cabinets' ),
	array( '05', 'Floor Coatings', 'A finished floor that looks like a showroom.', '/garage-floor-coatings' ),
	array( '06', 'Garage Ventilation', 'Cool the garage down so you can actually use it.', '/garage-exhaust-fans' ),
	array( '07', 'Lifts', 'Part of a complete garage renovation.', '/lifts' ),
	array( '08', 'Garage Door Installation and Repairs', 'The door finishes the renovation.', '/garage-door-installation-repairs' ),
);
?>
<!-- wp:group {"align":"full","backgroundColor":"black","textColor":"white","className":"gk-section","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignfull gk-section has-white-color has-black-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:paragraph {"className":"gk-eyebrow","textColor":"amber"} -->
<p class="gk-eyebrow has-amber-color has-text-color">Our solutions</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textColor":"white"} -->
<h2 class="wp-block-heading has-white-color has-text-color">Storage solution systems, built for the Texas heat</h2>
<!-- /wp:heading -->
<?php foreach ( array_chunk( $cards, 4 ) as $row ) : ?>
<!-- wp:columns {"className":"gk-cards","style":{"spacing":{"blockGap":{"top":"1.25rem","left":"1.25rem"}}}} -->
<div class="wp-block-columns gk-cards">
<?php foreach ( $row as $c ) : ?>
<!-- wp:column {"className":"gk-card"} -->
<div class="wp-block-column gk-card"><!-- wp:paragraph {"className":"gk-card__num","textColor":"amber"} -->
<p class="gk-card__num has-amber-color has-text-color"><?php echo esc_html( $c[0] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"textColor":"white"} -->
<h3 class="wp-block-heading has-white-color has-text-color"><a href="<?php echo esc_url( $c[3] ); ?>"><?php echo esc_html( $c[1] ); ?></a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php echo esc_html( $c[2] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
