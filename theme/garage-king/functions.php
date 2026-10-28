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
	wp_enqueue_style( 'gk-fonts', get_theme_file_uri( 'assets/fonts/fonts.css' ), array(), wp_get_theme()->get( 'Version' ) );
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

/*
 * Search basics: a short description for each main page, share tags, and LocalBusiness structured data on the home and contact pages.
 * Only facts already on the client's current site. Left out on purpose until the client confirms them: rating and review count, founding year
 * (the site says 2004, the ads say 2000), street address and hours. FAQ structured data waits until the FAQ answers are approved.
 */
function gk_seo_descriptions() {
	return array(
		'home'                             => 'The boutique, family owned and operated expert in luxury full garage renovations, including floors, doors and storage solution systems. Serving Greater Austin and Greater San Antonio.',
		'overhead-racks'                   => 'Strong, lightweight steel overhead storage racks in nine sizes, installed by The Garage King for all ceiling heights.',
		'motorized-overhead-racks'         => 'Motorized overhead storage racks that lower with the press of a button, with a wireless remote and safety net, installed by The Garage King.',
		'slatwall'                         => 'Heavy duty slatwall garage organization in four finishes, installed by The Garage King in Greater Austin and Greater San Antonio.',
		'steel-cabinets'                   => 'Powder coated steel garage cabinets, mounted off the floor, with soft self-closing drawers and doors.',
		'garage-exhaust-fans'              => 'Garage ventilation that cools your garage up to 20 degrees and your attic up to 40 degrees.',
		'garage-storage-pricing'           => 'Regular pricing for overhead racks, slatwall, steel cabinets, floor coatings and garage ventilation, and current offers.',
		'garage-storage-photos'            => 'Photos of recent garage storage installations by The Garage King in Greater Austin and Greater San Antonio.',
		'garage-storage-solutions'         => 'Garage storage solution systems from The Garage King: overhead racks, slatwall, steel cabinets, floor coatings and ventilation.',
		'how-it-works'                     => 'How a garage renovation with The Garage King works, from your free design consultation to installation.',
		'faq'                              => 'Answers to common questions about garage storage installations by The Garage King.',
		'about'                            => 'The Garage King is a boutique, family owned and operated expert in luxury full garage renovations.',
		'testimonials'                     => 'Reviews from The Garage King customers in Greater Austin and Greater San Antonio.',
		'service-areas'                    => 'The Garage King serves all Greater Austin, Greater San Antonio and surrounding areas.',
		'contact-us'                       => 'Contact The Garage King to book a free design consultation for your garage.',
		'schedule-quote'                   => 'Book your free design consultation with The Garage King.',
	);
}

add_action( 'wp_head', function () {
	$map  = gk_seo_descriptions();
	$slug = is_front_page() ? 'home' : ( is_page() ? get_post_field( 'post_name', get_queried_object_id() ) : '' );
	if ( $slug && isset( $map[ $slug ] ) ) {
		$desc = $map[ $slug ];
		printf( "<meta name=\"description\" content=\"%s\" />\n", esc_attr( $desc ) );
		printf( "<meta property=\"og:type\" content=\"website\" />\n<meta property=\"og:site_name\" content=\"The Garage King\" />\n" );
		printf( "<meta property=\"og:title\" content=\"%s\" />\n<meta property=\"og:description\" content=\"%s\" />\n", esc_attr( wp_get_document_title() ), esc_attr( $desc ) );
		printf( "<meta property=\"og:url\" content=\"%s\" />\n", esc_url( is_front_page() ? home_url( '/' ) : get_permalink() ) );
	}
	if ( is_front_page() || ( is_page() && 'contact-us' === get_post_field( 'post_name', get_queried_object_id() ) ) ) {
		$data = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'HomeAndConstructionBusiness',
			'name'        => 'The Garage King',
			'url'         => home_url( '/' ),
			'telephone'   => '+1-844-694-5464',
			'email'       => 'info@thegarageking.com',
			'description' => $map['home'],
			'areaServed'  => array( 'Greater Austin, Texas', 'Greater San Antonio, Texas' ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
	}
}, 1 );
