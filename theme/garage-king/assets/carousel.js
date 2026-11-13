/* Carousels (How it works steps, reviews). Works without JavaScript by swiping or scrolling; this adds buttons and a gentle autoplay. */
( function () {
	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	document.querySelectorAll( '[data-gk-carousel]' ).forEach( function ( root ) {
		var track = root.querySelector( '.gk-carousel__track' );
		if ( ! track ) { return; }
		var paused = false, timer = null;
		function step( dir ) {
			var slide = track.querySelector( '.gk-carousel__slide' );
			var w = slide ? slide.getBoundingClientRect().width : track.clientWidth;
			var max = track.scrollWidth - track.clientWidth;
			var next = track.scrollLeft + dir * w;
			if ( next > max + 4 ) { next = 0; }
			if ( next < -4 ) { next = max; }
			track.scrollTo( { left: next, behavior: reduce ? 'auto' : 'smooth' } );
		}
		root.querySelectorAll( '.gk-carousel__btn' ).forEach( function ( b ) {
			b.addEventListener( 'click', function () { step( parseInt( b.getAttribute( 'data-dir' ), 10 ) ); } );
		} );
		if ( root.hasAttribute( 'data-autoplay' ) && ! reduce ) {
			[ 'mouseenter', 'focusin', 'touchstart' ].forEach( function ( ev ) { root.addEventListener( ev, function () { paused = true; } ); } );
			[ 'mouseleave', 'focusout' ].forEach( function ( ev ) { root.addEventListener( ev, function () { paused = false; } ); } );
			timer = setInterval( function () { if ( ! paused && ! document.hidden ) { step( 1 ); } }, 5000 );
		}
	} );
} )();
