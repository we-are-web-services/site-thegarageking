/* Project estimator for the Pricing page. Reads assets/pricing.json; no other dependencies. */
( function () {
	'use strict';

	var root = document.getElementById( 'gk-estimator' );
	if ( ! root || ! window.gkEstimator ) {
		return;
	}

	var money = new Intl.NumberFormat( 'en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 } );

	function el( tag, attrs, children ) {
		var node = document.createElement( tag );
		Object.keys( attrs || {} ).forEach( function ( k ) {
			if ( k === 'text' ) {
				node.textContent = attrs[ k ];
			} else {
				node.setAttribute( k, attrs[ k ] );
			}
		} );
		( children || [] ).forEach( function ( c ) {
			node.appendChild( c );
		} );
		return node;
	}

	function build( cfg ) {
		var quantities = {};
		var areaSelect = el( 'select', { id: 'gk-est-area', class: 'gk-est__input' } );
		cfg.areas.forEach( function ( a ) {
			areaSelect.appendChild( el( 'option', { value: a, text: a } ) );
		} );

		var form = el( 'div', { class: 'gk-est__form' } );
		form.appendChild( el( 'h3', { text: '1. Choose your service area' } ) );
		form.appendChild( el( 'label', { for: 'gk-est-area', class: 'screen-reader-text', text: 'Service area' } ) );
		form.appendChild( areaSelect );
		form.appendChild( el( 'h3', { text: '2. Choose your solutions' } ) );

		cfg.products.forEach( function ( p ) {
			quantities[ p.id ] = 0;
			var step = p.step || 1;
			var input = el( 'input', { type: 'number', id: 'gk-est-' + p.id, class: 'gk-est__input gk-est__qty', min: '0', max: String( p.max ), step: String( step ), value: '0', inputmode: 'numeric' } );
			var row = el( 'div', { class: 'gk-est__row' }, [
				el( 'div', { class: 'gk-est__label' }, [
					el( 'label', { for: 'gk-est-' + p.id, text: p.label } ),
					el( 'small', { text: p.help + ' (' + money.format( p.price ) + ' per ' + p.unit.replace( /s$/, '' ) + ')' } )
				] ),
				el( 'div', { class: 'gk-est__qtywrap' }, [ input, el( 'span', { class: 'gk-est__unit', text: p.unit } ) ] )
			] );
			form.appendChild( row );
			input.addEventListener( 'input', update );
		} );

		( cfg.quoteOnly || [] ).forEach( function ( q ) {
			form.appendChild( el( 'div', { class: 'gk-est__row gk-est__row--quote' }, [
				el( 'div', { class: 'gk-est__label' }, [ el( 'strong', { text: q.label } ), el( 'small', { text: q.help } ) ] ),
				el( 'span', { class: 'gk-est__unit', text: 'quoted on the call' } )
			] ) );
		} );

		var out = el( 'div', { class: 'gk-est__result', 'aria-live': 'polite' } );
		var cta = el( 'a', { class: 'wp-block-button__link wp-element-button gk-est__cta', href: window.gkEstimator.bookUrl, text: 'Book a Free Design Consultation to lock this in' } );

		root.textContent = '';
		root.appendChild( form );
		root.appendChild( el( 'div', { class: 'gk-est__panel' }, [ el( 'h3', { text: 'Estimated investment' } ), out, cta ] ) );

		function clamp( v, max ) {
			v = parseFloat( v );
			if ( isNaN( v ) || v < 0 ) {
				return 0;
			}
			return Math.min( v, max );
		}

		function update() {
			var lines = [];
			var subtotal = 0;
			cfg.products.forEach( function ( p ) {
				var q = clamp( document.getElementById( 'gk-est-' + p.id ).value, p.max );
				quantities[ p.id ] = q;
				if ( q > 0 ) {
					var cost = q * p.price;
					subtotal += cost;
					var unit = q === 1 ? p.unit.replace( /s$/, '' ) : p.unit;
					lines.push( [ q + ' ' + unit + ' × ' + money.format( p.price ), cost ] );
				}
			} );

			var total = subtotal;
			var notes = [];
			( cfg.discounts || [] ).forEach( function ( d ) {
				if ( d.type === 'perUnitOver' ) {
					var n = d.products.reduce( function ( s, id ) {
						return s + ( quantities[ id ] || 0 );
					}, 0 );
					if ( n > d.threshold ) {
						var off = n * d.amountPerUnit;
						total -= off;
						lines.push( [ d.label, -off ] );
					} else if ( n === d.threshold ) {
						notes.push( d.label + ' (add one more rack to apply it).' );
					}
				}
			} );

			out.textContent = '';
			if ( ! lines.length ) {
				out.appendChild( el( 'p', { class: 'gk-est__empty', text: 'Add a solution to see an estimate.' } ) );
				return;
			}
			var list = el( 'ul', { class: 'gk-est__lines' } );
			lines.forEach( function ( l ) {
				list.appendChild( el( 'li', {}, [ el( 'span', { text: l[ 0 ] } ), el( 'strong', { text: ( l[ 1 ] < 0 ? '−' : '' ) + money.format( Math.abs( l[ 1 ] ) ) } ) ] ) );
			} );
			out.appendChild( list );
			out.appendChild( el( 'p', { class: 'gk-est__total', text: money.format( Math.max( total, 0 ) ) } ) );
			out.appendChild( el( 'p', { class: 'gk-est__area', text: 'Installed in the ' + areaSelect.value + '. Professional installation included.' } ) );
			notes.forEach( function ( n ) {
				out.appendChild( el( 'p', { class: 'gk-est__note', text: n } ) );
			} );
			if ( cfg.sample ) {
				out.appendChild( el( 'p', { class: 'gk-est__sample', text: 'Sample prices for the prototype. Final prices come from the client.' } ) );
			}
		}

		areaSelect.addEventListener( 'change', update );
		update();
	}

	fetch( window.gkEstimator.pricingUrl )
		.then( function ( r ) {
			return r.json();
		} )
		.then( build )
		.catch( function () {
			root.textContent = 'The estimator could not load. Call us for a quote.';
		} );
}() );
