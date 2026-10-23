<?php
/**
 * Plugin Name: Garage King Leads
 * Description: The quote and contact forms for the Garage King site, matched to the client's current site: the quote form is two steps (step 1 is the form, saved at once as a private "Lead"; step 2 is the scheduler on the thank-you page); the contact form is one step. Can pass each lead on to a webhook. Behaviour lives here, not in the theme, so changing the theme never loses the form.
 * Version: 0.4.0
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

const GK_LEADS_VERSION = '0.4.0';

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

/** The three forms. */
function gk_leads_sources() {
	return array( 'quote', 'contact', 'ads' );
}

/**
 * Where each form ends, exactly as on the client's current site (no new steps or conversion triggers):
 * the landing page forms (quote and ads) go to the thank-you page that holds the scheduler, at the same address as today
 * (the Google Ads conversion is tied to it). The contact form stays on its page and shows a confirmation, as the current
 * Gravity Form does.
 */
function gk_leads_thank_you_url( $source ) {
	if ( 'contact' === $source ) {
		return add_query_arg( 'gk_sent', '1', home_url( '/contact-us/' ) );
	}
	return home_url( '/ga-thank-you-page-garage-talk/' );
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
		'heard_from'     => 'How did you hear about us',
		'discount_code'  => 'Discount code',
		'garage_size'    => 'Garage size',
		'ceiling_height' => 'Garage ceiling height',
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

function gk_leads_proto_note() {
	$live = ( defined( 'GK_LEAD_WEBHOOK' ) && GK_LEAD_WEBHOOK ) || ( defined( 'GK_LEAD_EMAIL' ) && GK_LEAD_EMAIL );
	return $live ? '' : '<p class="gk-qf__proto"><strong>Draft form.</strong> Nothing is sent to the client yet. Requests are stored on this test site only.</p>';
}

add_shortcode( 'gk_quote_form', function ( $atts ) {
	$atts   = shortcode_atts( array( 'source' => 'quote' ), $atts, 'gk_quote_form' );
	$source = in_array( $atts['source'], gk_leads_sources(), true ) ? $atts['source'] : 'quote';
	$c      = ( 'contact' === $source );
	wp_enqueue_style( 'gk-leads', plugins_url( 'assets/quote-form.css', __FILE__ ), array(), GK_LEADS_VERSION );

	// Contact form: the page shows a confirmation instead of the form, and sends the same tracking event as the current Gravity Form.
	if ( $c && isset( $_GET['gk_sent'] ) ) {
		ob_start();
		?>
		<div class="gk-qf__sent" role="status"><p>Thanks for contacting us! We will get in touch with you shortly.</p></div>
		<script>
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push({ event: 'form_lead_submit', cov_form_type: 'Gravity Form Plugin', cov_form_id: 2, cov_form_name: 'Form id-2' });
			if (window.history && history.replaceState) { history.replaceState(null, '', location.pathname); }
		</script>
		<?php
		return ob_get_clean();
	}

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
		<input type="hidden" name="action" value="gk_quote">
		<?php wp_nonce_field( 'gk_quote', 'gk_nonce' ); ?>
		<input type="hidden" name="gk_t" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="source" value="<?php echo esc_attr( $source ); ?>">
		<div class="gk-qf__hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

		<?php if ( ! $c ) : ?><h3>Step 1 of 2: your details</h3><?php endif; ?>
		<div class="gk-qf__grid">
			<p><label for="<?php echo $id( 'first' ); ?>">First name *</label><input id="<?php echo $id( 'first' ); ?>" name="first_name" placeholder="First Name" required autocomplete="given-name"></p>
			<p><label for="<?php echo $id( 'last' ); ?>">Last name *</label><input id="<?php echo $id( 'last' ); ?>" name="last_name" placeholder="Last Name" required autocomplete="family-name"></p>
			<p><label for="<?php echo $id( 'email' ); ?>">Email *</label><input id="<?php echo $id( 'email' ); ?>" type="email" name="email" placeholder="Enter Your Best Email Here" required autocomplete="email"></p>
			<p><label for="<?php echo $id( 'phone' ); ?>">Phone number *</label><input id="<?php echo $id( 'phone' ); ?>" type="tel" name="phone" placeholder="Phone Number" required autocomplete="tel"></p>
			<p class="gk-qf__wide"><label for="<?php echo $id( 'street' ); ?>">Street address *</label><input id="<?php echo $id( 'street' ); ?>" name="street_address" placeholder="Street Address" required autocomplete="street-address"></p>
			<p><label for="<?php echo $id( 'city' ); ?>">City *</label><input id="<?php echo $id( 'city' ); ?>" name="city" placeholder="City" required autocomplete="address-level2"></p>
			<?php if ( ! $c ) : ?>
				<p><label for="<?php echo $id( 'state' ); ?>">State *</label><input id="<?php echo $id( 'state' ); ?>" name="state" placeholder="State" required autocomplete="address-level1"></p>
			<?php endif; ?>
			<p><label for="<?php echo $id( 'zip' ); ?>"><?php echo $c ? 'Zip code' : 'Postal code'; ?> *</label><input id="<?php echo $id( 'zip' ); ?>" name="postal_code" placeholder="<?php echo $c ? 'Zip Code' : 'Postal Code'; ?>" required autocomplete="postal-code"></p>
			<?php if ( $c ) : ?>
				<p><label for="gk-heard">How did you hear about us? *</label>
					<select id="gk-heard" name="heard_from" required><option value="">Choose one</option><option>Magazine</option><option>Google Search or Other Search Engine</option><option>Home Show</option><option>Referral</option><option>Facebook</option><option>Other - Let us know in message section</option></select></p>
				<p><label for="gk-discount">Discount code</label><input id="gk-discount" name="discount_code" placeholder="Enter Discount Code Here"></p>
				<p><label for="gk-size">Garage size *</label>
					<select id="gk-size" name="garage_size" required><option value="">Choose one</option><option>1 Car</option><option>2 Car</option><option>3 Car</option><option>4 Car</option><option>4 Car +</option></select></p>
				<p><label for="gk-ceiling">Garage ceiling height *</label>
					<select id="gk-ceiling" name="ceiling_height" required><option value="">Choose one</option><?php foreach ( range( 8, 17 ) as $ft ) : ?><option><?php echo (int) $ft; ?>&#8242;</option><?php endforeach; ?></select></p>
			<?php endif; ?>
		</div>
		<?php if ( ! $c ) : ?>
			<p><label for="<?php echo $id( 'details' ); ?>">Garage size (2 car, 3 car, etc.) and any other details about your garage *</label><textarea id="<?php echo $id( 'details' ); ?>" name="garage_details" rows="3" required></textarea></p>
		<?php else : ?>
			<p><label for="gk-message">Message *</label><textarea id="gk-message" name="message" rows="4" placeholder="How Can We Help You Today?" required></textarea></p>
		<?php endif; ?>
		<?php if ( $c ) : ?>
		<p class="gk-qf__consent"><label><input type="checkbox" name="consent" value="yes"> I agree to the <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">privacy policy</a>.</label><br>
			<small>I consent to receive emails and or texts regarding pricing and our latest email newsletters with promotions. We never sell your information and you may opt out at any time.</small></p>
		<?php else : ?>
		<p class="gk-qf__consent"><label><input type="checkbox" name="consent" value="yes"> Yes, I consent to receiving emails and texts.</label><br>
			<small>We will email you your pricing and may send updates about promotions, special offers and news. We will text you for appointment confirmations and general communication. We will not share or sell your personal information. You can unsubscribe at any time.</small></p>
		<?php endif; ?>
		<p><button type="submit" class="wp-block-button__link wp-element-button"><?php echo $c ? 'Submit' : 'Go to step 2'; ?></button></p>
	</form>
	<?php
	return ob_get_clean();
} );

function gk_leads_redirect_back( $fields ) {
	$back = wp_get_referer() ?: home_url( '/schedule-quote/' );
	wp_safe_redirect( add_query_arg( 'gk_err', implode( ',', $fields ), remove_query_arg( array( 'gk_err' ), $back ) ) );
	exit;
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
	$addr    = implode( ', ', array_filter( array( $m( 'street_address' ), $m( 'city' ), trim( $m( 'state' ) . ' ' . $m( 'postal_code' ) ) ) ) );
	$lines[] = 'Address: ' . $addr;
	foreach ( array( 'garage_details', 'heard_from', 'discount_code', 'garage_size', 'ceiling_height', 'message' ) as $k ) {
		if ( '' !== $m( $k ) ) {
			$lines[] = gk_leads_fields()[ $k ] . ': ' . $m( $k );
		}
	}
	$lines[] = 'Consent to emails and texts: ' . ( 'yes' === $m( 'consent' ) ? 'yes' : 'no' );
	$lines[] = 'Form: ' . $m( 'source' );
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
	foreach ( array( 'first_name', 'last_name', 'email', 'phone', 'street_address', 'city', 'state', 'postal_code', 'garage_details', 'heard_from', 'discount_code', 'garage_size', 'ceiling_height', 'message', 'consent' ) as $key ) {
		$raw          = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$data[ $key ] = in_array( $key, array( 'garage_details', 'message' ), true ) ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
	}
	$data['email'] = sanitize_email( $data['email'] );

	$required = array( 'first_name', 'last_name', 'email', 'phone', 'street_address', 'city', 'postal_code' );
	$required = ( 'contact' === $source ) ? array_merge( $required, array( 'heard_from', 'garage_size', 'ceiling_height', 'message' ) ) : array_merge( $required, array( 'state', 'garage_details' ) );
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
	wp_update_post( array( 'ID' => $lead_id, 'post_content' => implode( "\n", gk_leads_lines( $lead_id ) ) ) );
	gk_leads_notify( $lead_id );

	wp_safe_redirect( $done );
	exit;
}
add_action( 'admin_post_nopriv_gk_quote', 'gk_leads_handle' );
add_action( 'admin_post_gk_quote', 'gk_leads_handle' );
