/* Fills the quote form with the visitor's estimate (saved by the Pricing page estimator in sessionStorage). */
( function () {
	'use strict';

	var form = document.querySelector( '.gk-qf' );
	if ( ! form ) {
		return;
	}
	var box = form.querySelector( '.gk-qf__estimate' );
	var none = form.querySelector( '.gk-qf__noestimate' );
	var data = null;

	try {
		data = JSON.parse( sessionStorage.getItem( 'gkEstimate' ) || 'null' );
	} catch ( e ) {
		data = null;
	}

	if ( ! data || ! data.items || ! data.items.length ) {
		none.hidden = false;
		return;
	}

	var money = new Intl.NumberFormat( 'en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 } );
	var list = box.querySelector( '.gk-qf__lines' );

	function line( text, amount ) {
		var li = document.createElement( 'li' );
		var a = document.createElement( 'span' );
		var b = document.createElement( 'strong' );
		a.textContent = text;
		b.textContent = ( amount < 0 ? '−' : '' ) + money.format( Math.abs( amount ) );
		li.appendChild( a );
		li.appendChild( b );
		list.appendChild( li );
	}

	data.items.forEach( function ( it ) {
		line( it.qty + ' ' + ( it.qty === 1 ? it.unit.replace( /s$/, '' ) : it.unit ) + ' ' + it.label, it.cost );
	} );
	( data.discounts || [] ).forEach( function ( d ) {
		line( d.label, -Math.abs( d.amount ) );
	} );
	box.querySelector( '.gk-qf__total' ).textContent = money.format( data.total ) + ' estimated, ' + data.area + ( data.sample ? ' (sample prices)' : '' );
	form.querySelector( 'input[name="estimate_json"]' ).value = JSON.stringify( data );
	box.hidden = false;
}() );
