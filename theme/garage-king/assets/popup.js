/* The Garage King: offer pop-up (placeholder).
 * Shows once per visitor per GK_POPUP.days. Desktop: on exit intent (mouse leaves through the top) or after 25 seconds.
 * Mobile: after scrolling halfway down the page or after 15 seconds. Never on the quote, thank-you, contact or ad pages (excluded in functions.php).
 * Preview: add ?gk_popup=1 to any address to show it after 1.5 seconds and ignore the once-per-visitor rule; ?gk_popup=reset clears it.
 * It records nothing and sends nothing: no form, no tracking events, no new conversion triggers. */
( function () {
	var cfg = window.GK_POPUP || {};
	var box = document.getElementById( 'gk-popup' );
	if ( ! box ) { return; }
	var KEY = 'gkPopupSeen';
	var DAYS = cfg.days || 7;
	var params = new URLSearchParams( window.location.search );
	var force = params.get( 'gk_popup' ) === '1';
	var shown = false;

	function store( fn ) { try { return fn(); } catch ( e ) { return null; } }
	if ( params.get( 'gk_popup' ) === 'reset' ) { store( function () { window.localStorage.removeItem( KEY ); } ); }
	var seen = store( function () { return window.localStorage.getItem( KEY ); } );
	if ( ! force && seen && ( Date.now() - parseInt( seen, 10 ) ) < DAYS * 86400000 ) { return; }

	var lastFocus = null;
	function open() {
		if ( shown ) { return; }
		shown = true;
		lastFocus = document.activeElement;
		box.hidden = false;
		var close = box.querySelector( '.gk-popup__close' );
		if ( close ) { close.focus(); }
	}
	function close() {
		box.hidden = true;
		store( function () { window.localStorage.setItem( KEY, String( Date.now() ) ); } );
		if ( lastFocus && lastFocus.focus ) { lastFocus.focus(); }
	}

	box.addEventListener( 'click', function ( e ) { if ( e.target === box || e.target.closest( '.gk-popup__close' ) ) { close(); } } );
	document.addEventListener( 'keydown', function ( e ) { if ( ! box.hidden && e.key === 'Escape' ) { close(); } } );
	var copy = box.querySelector( '.gk-popup__copy' );
	if ( copy ) {
		copy.addEventListener( 'click', function () {
			var code = box.querySelector( '.gk-popup__code code' ).textContent;
			if ( navigator.clipboard ) { navigator.clipboard.writeText( code ).then( function () { copy.textContent = 'Copied'; } ); }
		} );
	}

	if ( force ) { window.setTimeout( open, 1500 ); return; }
	var isTouch = window.matchMedia( '(pointer: coarse)' ).matches || window.innerWidth < 782;
	if ( isTouch ) {
		window.setTimeout( open, 15000 );
		window.addEventListener( 'scroll', function onScroll() {
			var h = document.documentElement;
			if ( ( h.scrollTop + window.innerHeight ) / h.scrollHeight > 0.5 ) { window.removeEventListener( 'scroll', onScroll ); open(); }
		}, { passive: true } );
	} else {
		window.setTimeout( open, 25000 );
		document.addEventListener( 'mouseout', function ( e ) { if ( ! e.relatedTarget && e.clientY <= 0 ) { open(); } } );
	}
} )();
