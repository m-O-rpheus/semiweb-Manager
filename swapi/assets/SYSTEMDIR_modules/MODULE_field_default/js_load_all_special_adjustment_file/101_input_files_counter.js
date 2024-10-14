 

	swapinode().addEventListener('change', function(e) {

		if( e.target && e.target.classList.contains('swapi_field_file') ) {

			let elem = e.target.parentNode.querySelector(':scope span.swapi_field_file');

			elem.innerHTML = elem.dataset.swapiBefore + e.target.files.length + elem.dataset.swapiAfter;
		}
	});

    