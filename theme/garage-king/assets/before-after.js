/* The Garage King: before and after slider. The range input sets how much of the "before" photo shows. */
( function () {
	document.querySelectorAll( '[data-gk-ba]' ).forEach( function ( box ) {
		var input = box.querySelector( 'input[type="range"]' );
		if ( ! input ) { return; }
		function set() { box.style.setProperty( '--gk-pos', input.value + '%' ); }
		input.addEventListener( 'input', set );
		set();
	} );
} )();
