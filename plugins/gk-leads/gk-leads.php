<?php
/**
 * Plugin Name: Garage King Leads
 * Description: The quote and contact forms for the Garage King site, in the same two steps as the client's current site: step 1 is the form (saved at once as a private "Lead", with the visitor's estimate); step 2 is the scheduler on the thank-you page. Can pass each lead on to a webhook. Behaviour lives here, not in the theme, so changing the theme never loses the form.
 * Version: 0.3.0
 * Requires PHP: 8.0
 * Text Domain: gk-leads
 */

defined( 'ABSPATH' ) || exit;

/*
 * Optional settings (put them in wp-config.php, never in the repo):
 *   define( 'GK_LEAD_WEBHOOK', 'https://...' );   // POST every lead as JSON (GoHighLevel, Zapier, Jobber, ...)
 *   define( 'GK_LEAD_EMAIL', 'someone@example.com' ); // email a copy of every lead
 *   define( 'GK_SCHEDULER_URL', 'https://...' );      // the scheduler shown on the thank-you page (default: the client's current Calendly page)
 * With neither set, leads are only stored in this site's Leads screen (the prototype default).
 */

const GK_LEADS_VERSION = '0.3.0';

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
		'street_address' => 'Street address',
		'city'           => 'City',
		'state'          => 'State',
		'postal_code'    => 'Postal code',
		'garage_details' => 'Garage size and other details',
		'reason'         => 'Reason for contact',
		'message'        => 'Message',
		'consent'        => 'Consent to emails and texts',
	);
}

/** Step 2: the scheduler, shown on the thank-you page after step 1, as on the client's current site. */
add_shortcode( 'gk_scheduler', function () {
	$url = gk_leads_scheduler_url();
	if ( ! $url ) {
		return '';
	}
	$src = add_query_arg( array( 'embed_domain' => wp_parse_url( home_url(), PHP_URL_HOST ), 'embed_type' => 'Inline' ), $url );
	return '<div class="gk-scheduler"><iframe src="' . esc_url( $src ) . '" title="Schedule your free consultation" loading="lazy" width="100%" height="720" frameborder="0"></iframe>'
		. '<p class="gk-qf__small"><a href="' . $url . '" target="_blank" rel="noopener">Open the scheduler in a new tab</a></p></div>';
} );

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
	$c      = ( 'contact' === $source );
	wp_enqueue_style( 'gk-leads', plugins_url( 'assets/quote-form.css', __FILE__ ), array(), GK_LEADS_VERSION );
	wp_enqueue_script( 'gk-leads', plugins_url( 'assets/quote-form.js', __FILE__ ), array(), GK_LEADS_VERSION, true );

	$err = isset( $_GET['gk_err'] ) ? preg_replace( '/[^a-z_,]/', '', wp_unslash( $_GET['gk_err'] ) ) : '';
	$id  = function ( $name ) use ( $source ) {
		return esc_attr( 'gk-' . $name . '-' . $source );
	};

	ob_start();
	echo gk_leads_proto_note(); // phpcs:ignore WordPress.Security.EscapeOutput
	if ( $err ) {
		?><p class="gk-qf__error" role="alert">Please check: <?php echo esc_html( str_replace( '_', ' ', str_replace( ',', ', ', $err ) ) ); ?>.</p><?php
	}
	?>
	<form class="gk-qf" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php echo gk_leads_estimate_box(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<input type="hidden" name="action" value="gk_quote">
		<?php wp_nonce_field( 'gk_quote', 'gk_nonce' ); ?>
		<input type="hidden" name="gk_t" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="source" value="<?php echo esc_attr( $source ); ?>">
		<input type="hidden" name="estimate_json" value="">
		<div class="gk-qf__hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

		<?php if ( ! $c ) : ?><h3>Step 1 of 2: your details</h3><?php endif; ?>
		<div class="gk-qf__grid">
			<p><label for="<?php echo $id( 'first' ); ?>">First name *</label><input id="<?php echo $id( 'first' ); ?>" name="first_name" required autocomplete="given-name"></p>
			<p><label for="<?php echo $id( 'last' ); ?>">Last name *</label><input id="<?php echo $id( 'last' ); ?>" name="last_name" required autocomplete="family-name"></p>
			<p><label for="<?php echo $id( 'email' ); ?>">Email *</label><input id="<?php echo $id( 'email' ); ?>" type="email" name="email" required autocomplete="email"></p>
			<p><label for="<?php echo $id( 'phone' ); ?>">Phone number *</label><input id="<?php echo $id( 'phone' ); ?>" type="tel" name="phone" required autocomplete="tel"></p>
			<?php if ( ! $c ) : ?>
				<p class="gk-qf__wide"><label for="<?php echo $id( 'street' ); ?>">Street address *</label><input id="<?php echo $id( 'street' ); ?>" name="street_address" required autocomplete="street-address"></p>
			<?php endif; ?>
			<p><label for="<?php echo $id( 'city' ); ?>">City *</label><input id="<?php echo $id( 'city' ); ?>" name="city" required autocomplete="address-level2"></p>
			<?php if ( ! $c ) : ?>
				<p><label for="<?php echo $id( 'state' ); ?>">State *</label><input id="<?php echo $id( 'state' ); ?>" name="state" required autocomplete="address-level1"></p>
				<p><label for="<?php echo $id( 'zip' ); ?>">Postal code *</label><input id="<?php echo $id( 'zip' ); ?>" name="postal_code" required autocomplete="postal-code"></p>
			<?php else : ?>
				<p><label for="gk-reason">Reason for contact *</label>
					<select id="gk-reason" name="reason" required><option value="">Choose one</option><option>New project</option><option>Existing customer or service</option><option>Other</option></select></p>
			<?php endif; ?>
		</div>
		<?php if ( ! $c ) : ?>
			<p><label for="<?php echo $id( 'details' ); ?>">Garage size (2 car, 3 car, etc.) and any other details about your garage *</label><textarea id="<?php echo $id( 'details' ); ?>" name="garage_details" rows="3" required></textarea></p>
		<?php else : ?>
			<p><label for="gk-message">Message (optional)</label><textarea id="gk-message" name="message" rows="4"></textarea></p>
		<?php endif; ?>
		<p class="gk-qf__consent"><label><input type="checkbox" name="consent" value="yes"> Yes, I consent to receiving emails and texts.</label><br>
			<small>We will email you your pricing and may send updates about promotions, special offers and news. We will text you for appointment confirmations and general communication. We will not share or sell your personal information. You can unsubscribe at any time.</small></p>
		<p><button type="submit" class="wp-block-button__link wp-element-button"><?php echo $c ? 'Send my message' : 'Go to step 2'; ?></button></p>
	</form>
	<?php
	return ob_get_clean();
} );

function gk_leads_redirect_back( $fields ) {
	$back = wp_get_referer() ?: home_url( '/schedule-quote/' );
	wp_safe_redirect( add_query_arg( 'gk_err', implode( ',', $fields ), remove_query_arg( array( 'gk_err' ), $back ) ) );
	exit;
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
	$addr    = trim( $m( 'street_address' ) . ', ' . $m( 'city' ) . ', ' . $m( 'state' ) . ' ' . $m( 'postal_code' ), ' ,' );
	$lines[] = 'Address: ' . $addr;
	foreach ( array( 'reason', 'garage_details', 'message' ) as $k ) {
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
	return $lines;
}

/** Passes the lead on to the webhook and the email, if they are set up. */
function gk_leads_notify( $lead_id ) {
	$data = array();
	foreach ( array_keys( gk_leads_fields() ) as $k ) {
		$data[ $k ] = (string) get_post_meta( $lead_id, '_gk_' . $k, true );
	}
	$payload = array(
		'source'   => 'quote-form',
		'form'     => (string) get_post_meta( $lead_id, '_gk_source', true ),
		'lead_id'  => $lead_id,
		'lead'     => $data,
		'estimate' => json_decode( (string) get_post_meta( $lead_id, '_gk_estimate', true ), true ) ?: new stdClass(),
	);
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
		wp_mail( GK_LEAD_EMAIL, 'New request: ' . $data['first_name'] . ' ' . $data['last_name'], implode( "\n", gk_leads_lines( $lead_id ) ) );
	}
}

/** Step 1 (and the contact form): saves the lead at once, then sends the visitor to the thank-you page, where step 2 (the scheduler) is. */
function gk_leads_handle() {
	if ( ! isset( $_POST['gk_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gk_nonce'] ) ), 'gk_quote' ) ) {
		wp_die( 'This form expired. Please go back, reload the page and try again.', 'Form expired', array( 'response' => 403 ) );
	}
	$source = isset( $_POST['source'] ) ? sanitize_key( wp_unslash( $_POST['source'] ) ) : 'quote';
	$source = in_array( $source, gk_leads_sources(), true ) ? $source : 'quote';
	$done   = gk_leads_thank_you_url( $source );
	// Spam traps that never show a puzzle: a hidden field people leave empty, and a minimum fill time.
	$too_fast = ( time() - (int) ( $_POST['gk_t'] ?? 0 ) ) < 3;
	if ( ! empty( $_POST['website'] ) || $too_fast ) {
		wp_safe_redirect( $done ); // look successful, store nothing.
		exit;
	}

	$data = array();
	foreach ( array( 'first_name', 'last_name', 'email', 'phone', 'street_address', 'city', 'state', 'postal_code', 'garage_details', 'reason', 'message', 'consent' ) as $key ) {
		$raw          = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$data[ $key ] = in_array( $key, array( 'garage_details', 'message' ), true ) ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
	}
	$data['email'] = sanitize_email( $data['email'] );

	$required = array( 'first_name', 'last_name', 'email', 'phone', 'city' );
	$required = ( 'contact' === $source ) ? array_merge( $required, array( 'reason' ) ) : array_merge( $required, array( 'street_address', 'state', 'postal_code', 'garage_details' ) );
	$missing  = array();
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

	$lead_id = wp_insert_post( array(
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
	update_post_meta( $lead_id, '_gk_estimate', wp_json_encode( gk_leads_read_estimate() ) );
	wp_update_post( array( 'ID' => $lead_id, 'post_content' => implode( "\n", gk_leads_lines( $lead_id ) ) ) );
	gk_leads_notify( $lead_id );

	wp_safe_redirect( $done );
	exit;
}
add_action( 'admin_post_nopriv_gk_quote', 'gk_leads_handle' );
add_action( 'admin_post_gk_quote', 'gk_leads_handle' );
