

    /* Open drop-down menu either up or down. Depending on the scroll position */
    const swapi_dropdown_position = function(e) {

        const flow = e.closest('.swapi_flow');
    
        if ( ( (e.offsetTop - flow.scrollTop) - (flow.clientHeight / 2) ) < 0 ) {

            e.dataset.swapiDropdownDirection = "down";
        } 
        else {
            e.dataset.swapiDropdownDirection = "up";
        }
    };





    /* Change summary text if dropdown contains only radio buttons or checkboxes */
    const swapi_dropdown_summary = function(e) {

        if ( e.classList.contains('swapi_count_types_1') ) {

            const summary = e.querySelector(':scope > summary');


            /* The dropdown only includes radios */
            if ( e.classList.contains('swapi_has_type_radio') ) {
 
                const checked = e.querySelector(':scope .swapi_field_radio:checked');

                if ( checked !== null && checked.nextSibling !== null ) {

                    summary.textContent = checked.nextSibling.textContent;
                }
            }


            /* The dropdown only includes checkboxen */
            else if ( e.classList.contains('swapi_has_type_checkbox') ) {

                summary.textContent = summary.dataset.swapiBefore + e.querySelectorAll(':scope .swapi_field_checkbox:checked').length + summary.dataset.swapiAfter;
            }
        }
    };





    /* Dropdown logic begin */
    const swapi_dropdown_logic = function() {

        const sel = swapinode().querySelectorAll(':scope .swapi_input_wrapper_dropdown');

        if ( sel.length > 0 ) {

            sel.forEach(function(e) {

                swapi_dropdown_position(e);
                swapi_dropdown_summary(e);
            });
        }
    };

	swapinode().addEventListener('change', function(e) {

		if( e.target && e.target.classList.contains('swapi_field_selection') ) {

            swapi_dropdown_logic();

            // auto close on radio or checkbox
            const dropdown = e.target.closest('.swapi_input_wrapper_dropdown');

            if ( dropdown !== null ) {

                dropdown.open = false;
            }
		}
	});

    swapinode().addEventListener('swapi_after_pagechange', function() {

        swapi_dropdown_logic();
    });

    setInterval(function () {

        swapi_dropdown_logic();

    }, 200);

    swapi_dropdown_logic();

    