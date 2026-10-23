<?php
/**
 * Plugin Name: Garage King Leads
 * Description: The quote and contact forms for the Garage King site. Step 1 saves the lead at once (with the visitor's estimate) as a private "Lead"; step 2 adds optional details and photos to the same lead. Can pass each step on to a webhook. Behaviour lives here, not in the theme, so changing the theme never loses the form.
 * Version: 0.2.0
 * Requires PHP: 8.0
 * Text Domain: gk-leads
 */

defined( 'ABSPATH' ) || exit;

/*
 * Optional settings (put them in wp-config.php, never in the repo):
 *   define( 'GK_LEAD_WEBHOOK', 'https://...' );   // POST every lead as JSON (GoHighLevel, Zapier, Jobber, ...)
 *   define( 'GK_LEAD_EMAIL', 'someone@example.com' ); // email a copy of every lead
 *   define( 'GK_SCHEDULER_URL', 'https://...' );      // where visitors pick a call time (default: the client's current Calendly page)
 * With neither set, leads are only stored in this site's Leads screen (the prototype default).
 */

const GK_LEADS_VERSION = '0.2.0';
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

/** Where visitors pick a time for their call (Calendly today, GoHighLevel's calendar later). */
function gk_leads_scheduler_url() {
	$url = defined( 'GK_SCHEDULER_URL' ) && GK_SCHEDULER_URL ? GK_SCHEDULER_URL : 'https://calendly.com/thegarageking/garage-talk';
	return esc_url( apply_filters( 'gk_scheduler_url', $url ) );
}

/** The three forms, each with its own thank-you page so every lead is counted once in analytics. */
function gk_leads_sources() {
	return array( 'quote', 'contact', 'ads' );
}

function gk_leads_thank_you_url( $source ) {
	return home_url( '/thank-you/' . ( in_array( $source, gk_leads_sources(), true ) ? $source : 'quote' ) . '/' );
}

/** Fields the form accepts: slug => label. Everything else is ignored. */
function gk_leads_fields() {
	return array(
		'first_name'     => 'First name',
		'last_name'      => 'Last name',
		'email'          => 'Email',
		'phone'          => 'Phone',
		'city'           => 'City',
		'reason'         => 'Reason for contact',
		'message'        => 'Message',
		'garage_size'    => 'Garage size',
		'ceiling_height' => 'Ceiling height',
		'storing'        => 'What you want to store',
		'consent'        => 'Consent to emails and texts',
	);
}

add_shortcode( 'gk_scheduler', function () {
	$url = gk_leads_scheduler_url();
	if ( ! $url ) {
		return '';
	}
	return '<div class="wp-block-buttons is-content-justification-center"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . $url . '" target="_blank" rel="noopener">Choose a time for your call</a></div></div>';
} );

/** Finds the lead named in the step 2 link, or returns 0. The key keeps strangers from editing other people's leads. */
function gk_leads_find_lead() {
	$id  = isset( $_GET['gk_lead'] ) ? absint( $_GET['gk_lead'] ) : 0;
	$key = isset( $_GET['gk_key'] ) ? sanitize_text_field( wp_unslash( $_GET['gk_key'] ) ) : '';
	return gk_leads_check_key( $id, $key );
}

function gk_leads_check_key( $id, $key ) {
	if ( ! $id || '' === $key || 'gk_lead' !== get_post_type( $id ) ) {
		return 0;
	}
	return hash_equals( (string) get_post_meta( $id, '_gk_key', true ), $key ) ? $id : 0;
}

function gk_leads_estimate_box() {
	$est = home_url( '/garage-storage-pricing/#gk-estimator' );
	ob_start();
	?>
	<div class="gk-qf__estimate" hidden>
		<h3>Your estimate</h3>
		<ul class="gk-qf__lines"></ul>
		<p class="gk-qf__total"></p>
		<p class="gk-qf__small">An estimate only, at regular prices. We confirm a written price at your consultation. <a href="<?php echo esc_url( $est ); ?>">Change my estimate</a></p>
	</div>
	<p class="gk-qf__noestimate" hidden>Want a ballpark first? <a href="<?php echo esc_url( $est ); ?>">Try the price estimator</a> and it will come with your request.</p>
	<?php
	return ob_get_clean();
}

function gk_leads_proto_note() {
	$live = ( defined( 'GK_LEAD_WEBHOOK' ) && GK_LEAD_WEBHOOK ) || ( defined( 'GK_LEAD_EMAIL' ) && GK_LEAD_EMAIL );
	return $live ? '' : '<p class="gk-qf__proto"><strong>Draft form.</strong> Nothing is sent to the client yet. Requests are stored on this test site only.</p>';
}

add_shortcode( 'gk_quote_form', function ( $atts ) {
	$atts   = shortcode_atts( array( 'source' => 'quote' ), $atts, 'gk_quote_form' );
	$source = in_array( $atts['source'], gk_leads_sources(), true ) ? $atts['source'] : 'quote';
	wp_enqueue_style( 'gk-leads', plugins_url( 'assets/quote-form.css', __FILE__ ), array(), GK_LEADS_VERSION );
	wp_enqueue_script( 'gk-leads', plugins_url( 'assets/quote-form.js', __FILE__ ), array(), GK_LEADS_VERSION, true );

	$err    = isset( $_GET['gk_err'] ) ? preg_replace( '/[^a-z_,]/', '', wp_unslash( $_GET['gk_err'] ) ) : '';
	$action = esc_url( admin_url( 'admin-post.php' ) );
	$lead   = ( 'contact' === $source ) ? 0 : gk_leads_find_lead();
	$here   = get_permalink() ?: home_url( '/schedule-quote/' );

	ob_start();
	echo gk_leads_proto_note(); // phpcs:ignore WordPress.Security.EscapeOutput
	if ( $err ) {
		?><p class="gk-qf__error" role="alert">Please check: <?php echo esc_html( str_replace( '_', ' ', str_replace( ',', ', ', $err ) ) ); ?>.</p><?php
	}

	if ( $lead ) :
		// Step 2: the lead is already saved. Everything here is optional.
		$key = (string) get_post_meta( $lead, '_gk_key', true );
		?>
		<form id="gk-step2" class="gk-qf gk-qf--step2" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
			<input type="hidden" name="action" value="gk_quote_step2">
			<?php wp_nonce_field( 'gk_quote_step2', 'gk_nonce' ); ?>
			<input type="hidden" name="gk_lead" value="<?php echo esc_attr( $lead ); ?>">
			<input type="hidden" name="gk_key" value="<?php echo esc_attr( $key ); ?>">
			<input type="hidden" name="source" value="<?php echo esc_attr( $source ); ?>">
			<h3>Step 2 of 2: thank you, we have your details</h3>
			<p>Choose a time for your free consultation, by phone or video. Then tell us a little about your garage (optional) so we can plan before the call.</p>
			<?php echo do_shortcode( '[gk_scheduler]' ); ?>
			<div class="gk-qf__grid">
				<p><label for="gk-size">Garage size</label>
					<select id="gk-size" name="garage_size"><option value="">Not sure</option><option>1 car</option><option>2 car</option><option>3 car</option><option>4+ car</option></select></p>
				<p><label for="gk-ceiling">Ceiling height</label><input id="gk-ceiling" name="ceiling_height" placeholder="For example 9 ft"></p>
			</div>
			<p><label for="gk-photos">Photos of your garage</label><input id="gk-photos" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple></p>
			<p><label for="gk-store">What do you want to store or organize?</label><textarea id="gk-store" name="storing" rows="3"></textarea></p>
			<p><button type="submit" class="wp-block-button__link wp-element-button">Send my details</button>
				<a class="gk-qf__skip" href="<?php echo esc_url( gk_leads_thank_you_url( $source ) ); ?>">Skip this step</a></p>
		</form>
		<?php
		return ob_get_clean();
	endif;

	// Step 1 (or the one-step contact form).
	?>
	<form class="gk-qf" method="post" action="<?php echo $action; ?>">
		<?php echo gk_leads_estimate_box(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<input type="hidden" name="action" value="gk_quote">
		<?php wp_nonce_field( 'gk_quote', 'gk_nonce' ); ?>
		<input type="hidden" name="gk_t" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="source" value="<?php echo esc_attr( $source ); ?>">
		<input type="hidden" name="gk_return" value="<?php echo esc_url( $here ); ?>">
		<input type="hidden" name="estimate_json" value="">
		<div class="gk-qf__hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

		<?php if ( 'contact' !== $source ) : ?><h3>Step 1 of 2: your details</h3><?php endif; ?>
		<div class="gk-qf__grid">
			<p><label for="gk-first-<?php echo esc_attr( $source ); ?>">First name *</label><input id="gk-first-<?php echo esc_attr( $source ); ?>" name="first_name" required autocomplete="given-name"></p>
			<p><label for="gk-last-<?php echo esc_attr( $source ); ?>">Last name *</label><input id="gk-last-<?php echo esc_attr( $source ); ?>" name="last_name" required autocomplete="family-name"></p>
			<p><label for="gk-email-<?php echo esc_attr( $source ); ?>">Email *</label><input id="gk-email-<?php echo esc_attr( $source ); ?>" type="email" name="email" required autocomplete="email"></p>
			<p><label for="gk-phone-<?php echo esc_attr( $source ); ?>">Phone *</label><input id="gk-phone-<?php echo esc_attr( $source ); ?>" type="tel" name="phone" required autocomplete="tel"></p>
			<p><label for="gk-city-<?php echo esc_attr( $source ); ?>">City *</label><input id="gk-city-<?php echo esc_attr( $source ); ?>" name="city" required autocomplete="address-level2"></p>
			<?php if ( 'contact' === $source ) : ?>
				<p><label for="gk-reason">Reason for contact *</label>
					<select id="gk-reason" name="reason" required><option value="">Choose one</option><option>New project</option><option>Existing customer or service</option><option>Other</option></select></p>
			<?php endif; ?>
		</div>
		<?php if ( 'contact' === $source ) : ?>
			<p><label for="gk-message">Message (optional)</label><textarea id="gk-message" name="message" rows="4"></textarea></p>
		<?php endif; ?>
		<p class="gk-qf__consent"><label><input type="checkbox" name="consent" value="yes"> Yes, I consent to receiving emails and texts.</label><br>
			<small>We will email you your pricing and may send updates about promotions, special offers and news. We will text you for appointment confirmations and general communication. We will not share or sell your personal information. You can unsubscribe at any time.</small></p>
		<p><button type="submit" class="wp-block-button__link wp-element-button"><?php echo ( 'contact' === $source ) ? 'Send my message' : 'Continue to step 2: pick a time'; ?></button></p>
	</form>
	<?php
	return ob_get_clean();
} );

function gk_leads_redirect_back( $fields ) {
	$back = wp_get_referer() ?: home_url( '/schedule-quote/' );
	wp_safe_redirect( add_query_arg( 'gk_err', implode( ',', $fields ), remove_query_arg( array( 'gk_err' ), $back ) ) );
	exit;
}

function gk_leads_source() {
	$source = isset( $_POST['source'] ) ? sanitize_key( wp_unslash( $_POST['source'] ) ) : 'quote';
	return in_array( $source, gk_leads_sources(), true ) ? $source : 'quote';
}

/** Saves up to a few photos from the form and returns their URLs. Images only, size-capped. */
function gk_leads_save_photos() {
	$photos = array();
	if ( empty( $_FILES['photos']['name'][0] ) ) {
		return $photos;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	$mimes = array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp' );
	$count = min( count( $_FILES['photos']['name'] ), GK_LEADS_MAX_FILES );
	for ( $i = 0; $i < $count; $i++ ) {
		if ( UPLOAD_ERR_OK !== $_FILES['photos']['error'][ $i ] || $_FILES['photos']['size'][ $i ] > GK_LEADS_MAX_BYTES ) {
			continue;
		}
		$file  = array(
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
	return $photos;
}

/** The estimate comes from the visitor's browser: keep it as information, never as a quote. */
function gk_leads_read_estimate() {
	$estimate = array();
	if ( empty( $_POST['estimate_json'] ) ) {
		return $estimate;
	}
	$decoded = json_decode( wp_unslash( $_POST['estimate_json'] ), true );
	if ( is_array( $decoded ) && ! empty( $decoded['items'] ) && is_array( $decoded['items'] ) ) {
		$estimate['area']   = sanitize_text_field( $decoded['area'] ?? '' );
		$estimate['total']  = (float) ( $decoded['total'] ?? 0 );
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
	return $estimate;
}

/** The readable text stored with the lead and emailed. */
function gk_leads_lines( $lead_id ) {
	$m = function ( $k ) use ( $lead_id ) {
		return (string) get_post_meta( $lead_id, '_gk_' . $k, true );
	};
	$lines   = array();
	$lines[] = 'Name: ' . $m( 'first_name' ) . ' ' . $m( 'last_name' );
	$lines[] = 'Email: ' . $m( 'email' );
	$lines[] = 'Phone: ' . $m( 'phone' );
	$lines[] = 'City: ' . $m( 'city' );
	foreach ( array( 'reason', 'message', 'garage_size', 'ceiling_height', 'storing' ) as $k ) {
		if ( '' !== $m( $k ) ) {
			$lines[] = gk_leads_fields()[ $k ] . ': ' . $m( $k );
		}
	}
	$lines[] = 'Consent to emails and texts: ' . ( 'yes' === $m( 'consent' ) ? 'yes' : 'no' );
	$lines[] = 'Form: ' . $m( 'source' );
	$estimate = json_decode( $m( 'estimate' ), true );
	if ( $estimate ) {
		$lines[] = '';
		$lines[] = 'ESTIMATE SHOWN TO THE VISITOR (calculated in their browser, not a quote)' . ( ! empty( $estimate['sample'] ) ? ' [sample prices]' : '' );
		$lines[] = 'Area: ' . ( $estimate['area'] ?? '' );
		foreach ( $estimate['items'] ?? array() as $it ) {
			$unit    = ( 1.0 === (float) $it['qty'] ) ? preg_replace( '/s$/', '', $it['unit'] ) : $it['unit'];
			$lines[] = $it['qty'] . ' ' . $unit . ' ' . $it['label'] . ' x $' . $it['price'] . ' = $' . $it['cost'];
		}
		foreach ( $estimate['discounts'] ?? array() as $d ) {
			$lines[] = $d['label'] . ': -$' . abs( $d['amount'] );
		}
		$lines[] = 'Estimated total: $' . ( $estimate['total'] ?? 0 );
	}
	$photos = json_decode( $m( 'photos' ), true );
	if ( $photos ) {
		$lines[] = '';
		$lines[] = 'Photos: ' . implode( ' , ', $photos );
	}
	return $lines;
}

/** Passes a step on to the webhook and the email, if they are set up. $step is 1 or 2. */
function gk_leads_notify( $lead_id, $step ) {
	$lines   = gk_leads_lines( $lead_id );
	$data    = array();
	foreach ( array_keys( gk_leads_fields() ) as $k ) {
		$data[ $k ] = (string) get_post_meta( $lead_id, '_gk_' . $k, true );
	}
	$payload = array(
		'source'   => 'quote-form',
		'form'     => (string) get_post_meta( $lead_id, '_gk_source', true ),
		'step'     => $step,
		'lead_id'  => $lead_id,
		'lead'     => $data,
		'estimate' => json_decode( (string) get_post_meta( $lead_id, '_gk_estimate', true ), true ) ?: new stdClass(),
		'photos'   => json_decode( (string) get_post_meta( $lead_id, '_gk_photos', true ), true ) ?: array(),
	);
	do_action( 'gk_lead_created', $lead_id, $payload ); // kept for step 1 listeners; step 2 also fires gk_lead_updated.
	if ( 2 === $step ) {
		do_action( 'gk_lead_updated', $lead_id, $payload );
	}

	if ( defined( 'GK_LEAD_WEBHOOK' ) && GK_LEAD_WEBHOOK ) {
		$res = wp_remote_post( GK_LEAD_WEBHOOK, array(
			'timeout' => 8,
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode( $payload ),
		) );
		update_post_meta( $lead_id, '_gk_webhook_' . $step, is_wp_error( $res ) ? 'failed: ' . $res->get_error_message() : 'sent: HTTP ' . wp_remote_retrieve_response_code( $res ) );
	}
	if ( defined( 'GK_LEAD_EMAIL' ) && GK_LEAD_EMAIL ) {
		$name = get_post_meta( $lead_id, '_gk_first_name', true ) . ' ' . get_post_meta( $lead_id, '_gk_last_name', true );
		wp_mail( GK_LEAD_EMAIL, ( 1 === $step ? 'New request: ' : 'More details from: ' ) . $name, implode( "\n", $lines ) );
	}
}

/** Step 1 (and the contact form): saves the lead at once. */
function gk_leads_handle() {
	if ( ! isset( $_POST['gk_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gk_nonce'] ) ), 'gk_quote' ) ) {
		wp_die( 'This form expired. Please go back, reload the page and try again.', 'Form expired', array( 'response' => 403 ) );
	}
	$source = gk_leads_source();
	// Spam traps that never show a puzzle: a hidden field people leave empty, and a minimum fill time.
	$too_fast = ( time() - (int) ( $_POST['gk_t'] ?? 0 ) ) < 3;
	if ( ! empty( $_POST['website'] ) || $too_fast ) {
		wp_safe_redirect( gk_leads_thank_you_url( $source ) ); // look successful, store nothing.
		exit;
	}

	$data = array();
	foreach ( array( 'first_name', 'last_name', 'email', 'phone', 'city', 'reason', 'message', 'consent' ) as $key ) {
		$raw          = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$data[ $key ] = ( 'message' === $key ) ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
	}
	$data['email'] = sanitize_email( $data['email'] );

	$required = array( 'first_name', 'last_name', 'email', 'phone', 'city' );
	if ( 'contact' === $source ) {
		$required[] = 'reason';
	}
	$missing = array();
	foreach ( $required as $req ) {
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

	$estimate = gk_leads_read_estimate();
	$key      = wp_generate_password( 24, false );
	$lead_id  = wp_insert_post( array(
		'post_type'   => 'gk_lead',
		'post_status' => 'private',
		'post_title'  => $data['first_name'] . ' ' . $data['last_name'] . ' - ' . $data['city'],
	) );
	if ( ! $lead_id || is_wp_error( $lead_id ) ) {
		wp_die( 'Sorry, we could not save your request. Please try again.', 'Error', array( 'response' => 500 ) );
	}
	foreach ( $data as $k => $v ) {
		update_post_meta( $lead_id, '_gk_' . $k, $v );
	}
	update_post_meta( $lead_id, '_gk_source', $source );
	update_post_meta( $lead_id, '_gk_key', $key );
	update_post_meta( $lead_id, '_gk_estimate', wp_json_encode( $estimate ) );
	update_post_meta( $lead_id, '_gk_photos', wp_json_encode( array() ) );
	wp_update_post( array( 'ID' => $lead_id, 'post_content' => implode( "\n", gk_leads_lines( $lead_id ) ) ) );
	gk_leads_notify( $lead_id, 1 );

	if ( 'contact' === $source ) {
		wp_safe_redirect( gk_leads_thank_you_url( $source ) );
		exit;
	}
	$return = isset( $_POST['gk_return'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['gk_return'] ) ), home_url( '/schedule-quote/' ) ) : home_url( '/schedule-quote/' );
	wp_safe_redirect( add_query_arg( array( 'gk_lead' => $lead_id, 'gk_key' => $key ), remove_query_arg( array( 'gk_err', 'gk_lead', 'gk_key' ), $return ) ) . '#gk-step2' );
	exit;
}
add_action( 'admin_post_nopriv_gk_quote', 'gk_leads_handle' );
add_action( 'admin_post_gk_quote', 'gk_leads_handle' );

/** Step 2: adds the optional details and photos to the lead saved in step 1. */
function gk_leads_handle_step2() {
	if ( ! isset( $_POST['gk_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gk_nonce'] ) ), 'gk_quote_step2' ) ) {
		wp_die( 'This form expired. Please go back, reload the page and try again.', 'Form expired', array( 'response' => 403 ) );
	}
	$source  = gk_leads_source();
	$lead_id = gk_leads_check_key( absint( $_POST['gk_lead'] ?? 0 ), sanitize_text_field( wp_unslash( $_POST['gk_key'] ?? '' ) ) );
	if ( ! $lead_id ) {
		wp_die( 'We could not find your request. Please start again.', 'Not found', array( 'response' => 404 ) );
	}
	foreach ( array( 'garage_size', 'ceiling_height' ) as $k ) {
		update_post_meta( $lead_id, '_gk_' . $k, sanitize_text_field( wp_unslash( $_POST[ $k ] ?? '' ) ) );
	}
	update_post_meta( $lead_id, '_gk_storing', sanitize_textarea_field( wp_unslash( $_POST['storing'] ?? '' ) ) );
	$photos = gk_leads_save_photos();
	if ( $photos ) {
		update_post_meta( $lead_id, '_gk_photos', wp_json_encode( $photos ) );
	}
	wp_update_post( array( 'ID' => $lead_id, 'post_content' => implode( "\n", gk_leads_lines( $lead_id ) ) ) );
	gk_leads_notify( $lead_id, 2 );
	wp_safe_redirect( gk_leads_thank_you_url( $source ) );
	exit;
}
add_action( 'admin_post_nopriv_gk_quote_step2', 'gk_leads_handle_step2' );
add_action( 'admin_post_gk_quote_step2', 'gk_leads_handle_step2' );
