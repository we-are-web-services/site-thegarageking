<?php
/**
 * The Garage King block theme.
 */

// Photos that used to be linked from the client's current public site now live in the theme (assets/images/old-site, resized),
// so they keep working when the old site is replaced. Falls back to the old address only for a file that is not in the theme.
function gk_placeholder( $path ) {
	$path  = ltrim( $path, '/' );
	$local = 'assets/images/old-site/' . $path;
	if ( file_exists( get_theme_file_path( $local ) ) ) {
		return esc_url( get_theme_file_uri( $local ) );
	}
	return esc_url( 'https://thegarageking.com/wp-content/uploads/' . $path );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'gk-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&family=Poppins:ital,wght@1,700;1,800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'garage-king', get_theme_file_uri( 'assets/theme.css' ), array(), wp_get_theme()->get( 'Version' ) );
	if ( gk_popup_enabled() ) {
		wp_enqueue_script( 'gk-popup', get_theme_file_uri( 'assets/popup.js' ), array(), wp_get_theme()->get( 'Version' ), true );
		wp_add_inline_script( 'gk-popup', 'window.GK_POPUP = ' . wp_json_encode( array( 'days' => 7 ) ) . ';', 'before' );
	}
} );

/*
 * Offer pop-up (placeholder: the client has not confirmed the offer wording or the code; see docs/follow-ups.md section F).
 * Off on the quote, thank-you, contact and ad landing pages, as the client's Website Content Outline asks.
 * Records nothing and sends nothing, so it adds no conversion trigger.
 */
function gk_popup_enabled() {
	if ( is_admin() || is_feed() || is_404() ) {
		return false;
	}
	return ! is_page( array( 'schedule-quote', 'ga-thank-you-page-garage-talk', 'contact-us', 'ga', 'ma', 'nextdoor', 'sael' ) );
}

add_action( 'wp_footer', function () {
	if ( ! gk_popup_enabled() ) {
		return;
	}
	$offer = apply_filters( 'gk_popup_offer', array(
		'tag'   => 'Sample offer',
		'title' => 'Buy two fixed racks, get one free',
		'text'  => 'Book your free design consultation and mention this code to claim the current offer.',
		'code'  => 'SAMPLE-CODE',
		'note'  => 'Placeholder: the offer wording and the code are examples, to be confirmed with the client.',
	) );
	?>
	<div class="gk-popup" id="gk-popup" hidden>
		<div class="gk-popup__box" role="dialog" aria-modal="true" aria-labelledby="gk-popup-title">
			<button type="button" class="gk-popup__close" aria-label="Close">&times;</button>
			<p class="gk-popup__tag"><?php echo esc_html( $offer['tag'] ); ?></p>
			<h2 class="gk-popup__title" id="gk-popup-title"><?php echo esc_html( $offer['title'] ); ?></h2>
			<p class="gk-popup__text"><?php echo esc_html( $offer['text'] ); ?></p>
			<div class="gk-popup__code"><code><?php echo esc_html( $offer['code'] ); ?></code><button type="button" class="gk-popup__copy">Copy</button></div>
			<a class="gk-popup__cta" href="<?php echo esc_url( home_url( '/schedule-quote' ) ); ?>">Book a Free Design Consultation</a>
			<p class="gk-popup__note"><?php echo esc_html( $offer['note'] ); ?></p>
		</div>
	</div>
	<?php
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
