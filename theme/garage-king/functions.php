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

/*
 * Old or planned addresses that point at a page that exists. Kept short on purpose: at launch, redirects for retired addresses
 * move to the redirect tool chosen for the site (docs/later-scope.md, "Post-launch restructuring").
 * /garage-cabinets is the address the client's Website Content Outline planned for cabinets; the live page stays at /steel-cabinets.
 */
add_action( 'template_redirect', function () {
	$map  = array( 'garage-cabinets' => '/steel-cabinets/' );
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
} );
