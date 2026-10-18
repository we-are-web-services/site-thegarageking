<?php
/**
 * The Garage King block theme.
 */

// Placeholder photos come from the client's current public site until real media is supplied.
function gk_placeholder( $path ) {
	return esc_url( 'https://thegarageking.com/wp-content/uploads/' . ltrim( $path, '/' ) );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'gk-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&family=Poppins:ital,wght@1,700;1,800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'garage-king', get_theme_file_uri( 'assets/theme.css' ), array(), wp_get_theme()->get( 'Version' ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_page( 'garage-storage-pricing' ) ) {
		return;
	}
	wp_enqueue_script( 'gk-estimator', get_theme_file_uri( 'assets/estimator.js' ), array(), wp_get_theme()->get( 'Version' ), true );
	wp_localize_script( 'gk-estimator', 'gkEstimator', array(
		'pricingUrl' => get_theme_file_uri( 'assets/pricing.json' ),
		'bookUrl'    => home_url( '/schedule-quote/' ),
	) );
} );

add_action( 'init', function () {
	register_block_pattern_category( 'garage-king', array( 'label' => 'The Garage King' ) );
} );

// Root-relative links (href="/slug") keep working when the site lives in a subfolder,
// such as a WordPress Playground preview.
add_filter( 'render_block', function ( $content ) {
	if ( false === strpos( $content, 'href="/' ) ) {
		return $content;
	}
	return preg_replace_callback(
		'#href="/(?!/)([^"]*)"#',
		function ( $m ) {
			return 'href="' . esc_url( home_url( '/' . $m[1] ) ) . '"';
		},
		$content
	);
} );
