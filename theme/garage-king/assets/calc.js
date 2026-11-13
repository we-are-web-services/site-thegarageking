/* Pricing estimate: adds up quantity x regular price. An estimate only. */
( function () {
	document.querySelectorAll( '[data-gk-calc]' ).forEach( function ( root ) {
		var out = root.querySelector( '[data-gk-calc-total]' );
		var inputs = root.querySelectorAll( 'input[data-price]' );
		function total() {
			var sum = 0;
			inputs.forEach( function ( i ) { var q = Math.max( 0, parseFloat( i.value ) || 0 ); sum += q * parseFloat( i.getAttribute( 'data-price' ) ); } );
			out.textContent = '$' + Math.round( sum ).toLocaleString( 'en-US' );
		}
		inputs.forEach( function ( i ) { i.addEventListener( 'input', total ); } );
		total();
	} );
} )();
