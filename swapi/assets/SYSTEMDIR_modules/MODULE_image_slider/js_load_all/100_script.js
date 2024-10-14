 

/* JavaScript für das Slider Modul */
/* ========================================================================================================================================================== */

    let mjv_scrollStop = 0;
    let mjv_scrollTimeout;

    const func_scrollTimeout = function() {

        mjv_scrollTimeout = setTimeout(function() {

            const mjv_sel = swapinode().querySelectorAll('.swapi_section .swapi_img_slider_slide_wrapper:not([data-swapi-slides="1"])');

            if ( mjv_sel !== null ) {

                mjv_sel.forEach(function(e) { 

                    // 2. Bugfix: Setze den Checked Status auf das Radio erst, wenn das Scrollen angehalten wurde
                    if ( e.scrollLeft === mjv_scrollStop ) {
    
                        e.querySelector('.swapi_img_slider_radio[data-swapi-radio="' + Math.round( e.scrollLeft / e.offsetWidth ) + '"]').checked = true;
                    }
                    else {
    
                        mjv_scrollStop = e.scrollLeft;
                    }
                });
            }

            func_scrollTimeout();

        }, 200);
    };

    func_scrollTimeout();

    swapinode().addEventListener('change', function(e) {    
        
        if ( e.target && e.target.classList.contains('swapi_img_slider_radio') ) {

            // 1. Bugfix: Timeout Restart - Damit dieser nicht dazwischen funkt wenn geklickt wird.
            clearTimeout(mjv_scrollTimeout);
            func_scrollTimeout();

            const mjv_sel = e.target.parentNode;

            mjv_sel.scrollLeft = (parseInt(mjv_sel.offsetWidth, 10) * e.target.dataset.swapiRadio);
        }
    });

