<?php
/**
 * Plugin Name: Garage King Leads
 * Description: The quote form for the Garage King prototype. Saves each request (with the visitor's estimate) as a private "Lead" and can pass it on to a webhook. Behaviour lives here, not in the theme, so changing the theme never loses the form.
 * Version: 0.1.0
 * Requires PHP: 8.0
 * Text Domain: gk-leads
 */

defined( 'ABSPATH' ) || exit;

/*
 * Optional settings (put them in wp-config.php, never in the repo):
 *   define( 'GK_LEAD_WEBHOOK', 'https://...' );   // POST every lead as JSON (GoHighLevel, Zapier, Jobber, ...)
 *   define( 'GK_LEAD_EMAIL', 'someone@example.com' ); // email a copy of every lead
 * With neither set, leads are only stored in this site's Leads screen (the prototype default).
 */

const GK_LEADS_VERSION = '0.1.0';
const GK_LEADS_MAX_FILES = 5;
const GK_LEADS_MAX_BYTES = 5 * 1024 * 1024;

add_action( 'init', function () {
	register_post_type( 'gk_lead', array(
		'labels'       => array( 'name' => 'Leads', 'singular_name' => 'Lead', 'menu_name' => 'Leads' ),
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-email-alt',
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => false,
	) );
} );

/** Fields the form accepts: slug => label. Everything else is ignored. */
function gk_leads_fields() {
	return array(
		'first_name'     => 'First name',
		'last_name'      => 'Last name',
		'email'          => 'Email',
		'phone'          => 'Phone',
		'city'           => 'City',
		'garage_size'    => 'Garage size',
		'ceiling_height' => 'Ceiling height',
		'storing'        => 'What you want to store',
		'consent'        => 'Consent to emails and texts',
	);
}

add_shortcode( 'gk_quote_form', function () {
	wp_enqueue_style( 'gk-leads', plugins_url( 'assets/quote-form.css', __FILE__ ), array(), GK_LEADS_VERSION );
	wp_enqueue_script( 'gk-leads', plugins_url( 'assets/quote-form.js', __FILE__ ), array(), GK_LEADS_VERSION, true );

	$err = isset( $_GET['gk_err'] ) ? preg_replace( '/[^a-z_,]/', '', wp_unslash( $_GET['gk_err'] ) ) : '';
	$live = ( defined( 'GK_LEAD_WEBHOOK' ) && GK_LEAD_WEBHOOK ) || ( defined( 'GK_LEAD_EMAIL' ) && GK_LEAD_EMAIL );

	ob_start();
	?>
	<form class="gk-qf" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
		<?php if ( ! $live ) : ?>
			<p class="gk-qf__proto"><strong>Prototype form.</strong> Nothing is sent to the client. Requests are stored on this test site only.</p>
		<?php endif; ?>
		<?php if ( $err ) : ?>
			<p class="gk-qf__error" role="alert">Please check: <?php echo esc_html( str_replace( '_', ' ', str_replace( ',', ', ', $err ) ) ); ?>.</p>
		<?php endif; ?>

		<div class="gk-qf__estimate" hidden>
			<h3>Your estimate</h3>
			<ul class="gk-qf__lines"></ul>
			<p class="gk-qf__total"></p>
			<p class="gk-qf__small">An estimate only. We confirm a written price on your planning call. <a href="<?php echo esc_url( home_url( '/garage-storage-pricing/#gk-estimator' ) ); ?>">Change my estimate</a></p>
		</div>
		<p class="gk-qf__noestimate" hidden>Want a ballpark first? <a href="<?php echo esc_url( home_url( '/garage-storage-pricing/#gk-estimator' ) ); ?>">Try the price estimator</a> and it will come with your request.</p>

		<input type="hidden" name="action" value="gk_quote">
		<?php wp_nonce_field( 'gk_quote', 'gk_nonce' ); ?>
		<input type="hidden" name="gk_t" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="estimate_json" value="">
		<div class="gk-qf__hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

		<h3>Step 1: your details</h3>
		<div class="gk-qf__grid">
			<p><label for="gk-first">First name *</label><input id="gk-first" name="first_name" required autocomplete="given-name"></p>
			<p><label for="gk-last">Last name *</label><input id="gk-last" name="last_name" required autocomplete="family-name"></p>
			<p><label for="gk-email">Email *</label><input id="gk-email" type="email" name="email" required autocomplete="email"></p>
			<p><label for="gk-phone">Phone *</label><input id="gk-phone" type="tel" name="phone" required autocomplete="tel"></p>
			<p><label for="gk-city">City *</label><input id="gk-city" name="city" required autocomplete="address-level2"></p>
			<p><label for="gk-size">Garage size</label>
				<select id="gk-size" name="garage_size"><option value="">Not sure</option><option>1 car</option><option>2 car</option><option>3 car</option><option>4+ car</option></select></p>
		</div>
		<h3>Step 2 (optional): about your garage</h3>
		<div class="gk-qf__grid">
			<p><label for="gk-ceiling">Ceiling height</label><input id="gk-ceiling" name="ceiling_height" placeholder="For example 9 ft"></p>
			<p><label for="gk-photos">Photos of your garage</label><input id="gk-photos" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple></p>
		</div>
		<p><label for="gk-store">What do you want to store or organize?</label><textarea id="gk-store" name="storing" rows="3"></textarea></p>
		<p class="gk-qf__consent"><label><input type="checkbox" name="consent" value="yes"> Yes, I consent to receiving emails and texts.</label><br>
			<small>We will email you your pricing and may send updates about promotions, special offers and news. We will text you for appointment confirmations and general communication. We will not share or sell your personal information. You can unsubscribe at any time.</small></p>
		<p><button type="submit" class="wp-block-button__link wp-element-button">Go to step 2: pick a time</button></p>
		<p class="gk-qf__small">The scheduler for choosing your call time is not built yet.</p>
	</form>
	<?php
	return ob_get_clean();
} );

function gk_leads_redirect_back( $fields ) {
	$back = wp_get_referer() ?: home_url( '/schedule-quote/' );
	wp_safe_redirect( add_query_arg( 'gk_err', implode( ',', $fields ), remove_query_arg( 'gk_err', $back ) ) );
	exit;
}

function gk_leads_thank_you() {
	wp_safe_redirect( home_url( '/thank-you/quote/' ) );
	exit;
}

function gk_leads_handle() {
	if ( ! isset( $_POST['gk_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gk_nonce'] ) ), 'gk_quote' ) ) {
		wp_die( 'This form expired. Please go back, reload the page and try again.', 'Form expired', array( 'response' => 403 ) );
	}
	// Spam traps that never show a puzzle: a hidden field people leave empty, and a minimum fill time.
	$too_fast = ( time() - (int) ( $_POST['gk_t'] ?? 0 ) ) < 3;
	if ( ! empty( $_POST['website'] ) || $too_fast ) {
		gk_leads_thank_you(); // look successful, store nothing.
	}

	$data = array();
	foreach ( array_keys( gk_leads_fields() ) as $key ) {
		$raw         = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$data[ $key ] = ( 'storing' === $key ) ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
	}
	$data['email'] = sanitize_email( $data['email'] );

	$missing = array();
	foreach ( array( 'first_name', 'last_name', 'email', 'phone', 'city' ) as $req ) {
		if ( '' === $data[ $req ] ) {
			$missing[] = $req;
		}
	}
	if ( $data['email'] && ! is_email( $data['email'] ) ) {
		$missing[] = 'email';
	}
	if ( $missing ) {
		gk_leads_redirect_back( array_unique( $missing ) );
	}

	// The estimate comes from the visitor's browser: keep it as information, never as a quote.
	$estimate = array();
	if ( ! empty( $_POST['estimate_json'] ) ) {
		$decoded = json_decode( wp_unslash( $_POST['estimate_json'] ), true );
		if ( is_array( $decoded ) && ! empty( $decoded['items'] ) && is_array( $decoded['items'] ) ) {
			$estimate['area']  = sanitize_text_field( $decoded['area'] ?? '' );
			$estimate['total'] = (float) ( $decoded['total'] ?? 0 );
			$estimate['sample'] = ! empty( $decoded['sample'] );
			foreach ( array_slice( $decoded['items'], 0, 12 ) as $it ) {
				$estimate['items'][] = array(
					'label' => sanitize_text_field( $it['label'] ?? '' ),
					'qty'   => (float) ( $it['qty'] ?? 0 ),
					'unit'  => sanitize_text_field( $it['unit'] ?? '' ),
					'price' => (float) ( $it['price'] ?? 0 ),
					'cost'  => (float) ( $it['cost'] ?? 0 ),
				);
			}
			foreach ( array_slice( (array) ( $decoded['discounts'] ?? array() ), 0, 5 ) as $d ) {
				$estimate['discounts'][] = array( 'label' => sanitize_text_field( $d['label'] ?? '' ), 'amount' => (float) ( $d['amount'] ?? 0 ) );
			}
		}
	}

	// Photos: images only, a few, size-capped.
	$photos = array();
	if ( ! empty( $_FILES['photos']['name'][0] ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$mimes = array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp' );
		$count = min( count( $_FILES['photos']['name'] ), GK_LEADS_MAX_FILES );
		for ( $i = 0; $i < $count; $i++ ) {
			if ( UPLOAD_ERR_OK !== $_FILES['photos']['error'][ $i ] || $_FILES['photos']['size'][ $i ] > GK_LEADS_MAX_BYTES ) {
				continue;
			}
			$file = array(
				'name'     => $_FILES['photos']['name'][ $i ],
				'type'     => $_FILES['photos']['type'][ $i ],
				'tmp_name' => $_FILES['photos']['tmp_name'][ $i ],
				'error'    => 0,
				'size'     => $_FILES['photos']['size'][ $i ],
			);
			$moved = wp_handle_upload( $file, array( 'test_form' => false, 'mimes' => $mimes ) );
			if ( ! empty( $moved['url'] ) ) {
				$photos[] = $moved['url'];
			}
		}
	}

	$lines   = array();
	$lines[] = 'Name: ' . $data['first_name'] . ' ' . $data['last_name'];
	$lines[] = 'Email: ' . $data['email'];
	$lines[] = 'Phone: ' . $data['phone'];
	$lines[] = 'City: ' . $data['city'];
	foreach ( array( 'garage_size', 'ceiling_height', 'storing' ) as $k ) {
		if ( '' !== $data[ $k ] ) {
			$lines[] = gk_leads_fields()[ $k ] . ': ' . $data[ $k ];
		}
	}
	$lines[] = 'Consent to emails and texts: ' . ( 'yes' === $data['consent'] ? 'yes' : 'no' );
	if ( $estimate ) {
		$lines[] = '';
		$lines[] = 'ESTIMATE SHOWN TO THE VISITOR (calculated in their browser, not a quote)' . ( $estimate['sample'] ? ' [sample prices]' : '' );
		$lines[] = 'Area: ' . $estimate['area'];
		foreach ( $estimate['items'] ?? array() as $it ) {
			$unit    = ( 1.0 === (float) $it['qty'] ) ? preg_replace( '/s$/', '', $it['unit'] ) : $it['unit'];
			$lines[] = $it['qty'] . ' ' . $unit . ' ' . $it['label'] . ' x $' . $it['price'] . ' = $' . $it['cost'];
		}
		foreach ( $estimate['discounts'] ?? array() as $d ) {
			$lines[] = $d['label'] . ': -$' . abs( $d['amount'] );
		}
		$lines[] = 'Estimated total: $' . $estimate['total'];
	}
	if ( $photos ) {
		$lines[] = '';
		$lines[] = 'Photos: ' . implode( ' , ', $photos );
	}

	$lead_id = wp_insert_post( array(
		'post_type'    => 'gk_lead',
		'post_status'  => 'private',
		'post_title'   => $data['first_name'] . ' ' . $data['last_name'] . ' - ' . $data['city'],
		'post_content' => implode( "\n", $lines ),
	) );
	if ( $lead_id && ! is_wp_error( $lead_id ) ) {
		foreach ( $data as $k => $v ) {
			update_post_meta( $lead_id, '_gk_' . $k, $v );
		}
		update_post_meta( $lead_id, '_gk_estimate', wp_json_encode( $estimate ) );
		update_post_meta( $lead_id, '_gk_photos', wp_json_encode( $photos ) );
	}

	$payload = array( 'source' => 'quote-form', 'lead' => $data, 'estimate' => $estimate, 'photos' => $photos );
	do_action( 'gk_lead_created', $lead_id, $payload );

	if ( defined( 'GK_LEAD_WEBHOOK' ) && GK_LEAD_WEBHOOK ) {
		$res = wp_remote_post( GK_LEAD_WEBHOOK, array(
			'timeout' => 8,
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode( $payload ),
		) );
		update_post_meta( $lead_id, '_gk_webhook', is_wp_error( $res ) ? 'failed: ' . $res->get_error_message() : 'sent: HTTP ' . wp_remote_retrieve_response_code( $res ) );
	}
	if ( defined( 'GK_LEAD_EMAIL' ) && GK_LEAD_EMAIL ) {
		wp_mail( GK_LEAD_EMAIL, 'New quote request: ' . $data['first_name'] . ' ' . $data['last_name'], implode( "\n", $lines ) );
	}

	gk_leads_thank_you();
}
add_action( 'admin_post_nopriv_gk_quote', 'gk_leads_handle' );
add_action( 'admin_post_gk_quote', 'gk_leads_handle' );
