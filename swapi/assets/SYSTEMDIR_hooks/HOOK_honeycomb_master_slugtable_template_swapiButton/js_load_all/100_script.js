

    /* Add state */
    swapinode().addEventListener('click', function(e) {

        let sel = '.swapi_hcm_root[data-swapi-slugtable="swapiButton"] .swapi_btn'; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null

        if( sel !== null ) {

            // +++++++ state +++++++
            if ( sel.classList.contains("slugtable_template_swapiButton_btnadd") ) {

                sel.previousSibling.appendChild( document.importNode( sel.parentNode.querySelector(':scope > .SLUGTABLE_TEMPLATE_CLASS__state').content, true ) );
            }
        }
    });



    /* generate CSS */
    swapinode().addEventListener('input', function(e) {

        if ( e.target.hasAttribute('data-swapibutton-default') || e.target.hasAttribute('data-swapibutton-css') ) {

            let p   = e.target.closest('.swapi_hcm_root');
            let css = '[data-swapi-slugtable="' + p.getAttribute('data-swapi-slugtable') + '"] [data-template-hcm-naming="' + p.querySelector(':scope [data-template-hcm-naming]').getAttribute('data-template-hcm-naming') + '"] ~ .swapi_hcm_body';
            let gen = '';

            p.querySelectorAll(':scope [data-swapibutton-css]').forEach(function(a) {

                let elem = a.closest('.SLUGTABLE_TEMPLATE_CLASS__state');

                gen += css + ' .SLUGTABLE_TEMPLATE_CLASS__state:nth-child(' + (Array.from(elem.parentNode.children).indexOf(elem)+1).toString() + ') .slugtable_template_swapiButton_example{' + a.value + '}';
            });

            gen = css + ' .slugtable_template_swapiButton_example{' + p.querySelector(':scope [data-swapibutton-default]').value + '}' + gen;

            p.querySelector(':scope .slugtable_template_swapiButton_style').innerHTML = '<style>' + gen + '</style>';
        }
    });

