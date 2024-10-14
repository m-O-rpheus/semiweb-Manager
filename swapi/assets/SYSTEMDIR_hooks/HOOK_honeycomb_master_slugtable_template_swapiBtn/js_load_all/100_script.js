

    /* Add row */
    swapinode().addEventListener('click', function(e) {

        let sel = '.swapi_hcm_root[data-swapi-slugtable="swapiBtn"] .slugtable_template_swapiBtn_newrow'; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null

        if( sel !== null ) {

            sel.parentNode.querySelector(':scope .slugtable_template_swapiBtn_container > .SLUGTABLE_TEMPLATE_INSERT').appendChild( document.importNode( sel.parentNode.querySelector(':scope > .SLUGTABLE_TEMPLATE_CLASS__row').content, true ) );
        }
    });


    /* Add column */
    swapinode().addEventListener('click', function(e) {

        let sel = '.swapi_hcm_root[data-swapi-slugtable="swapiBtn"] .slugtable_template_swapiBtn_newcolumn'; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null

        if( sel !== null ) {

            sel = sel.closest('.swapi_hcm_body');  // body

            const btnsel = sel.querySelector(':scope > .slugtable_template_swapiBtn_container[data-count-buttons]');
            const count  = parseInt(btnsel.getAttribute('data-count-buttons'));
            
            sel.querySelectorAll(':scope .slugtable_template_swapiBtn_row').forEach(function(a) {

                a.innerHTML = '<nav></nav>';

                if ( a.closest('.SLUGTABLE_TEMPLATE_CLASS__row') !== null ) {

                    for (let i = 0; i <= count; i++) {

                        a.appendChild( document.importNode( sel.querySelector(':scope > .slugtable_template_swapiBtn_templateother').content, true ) );
                    }
                }
                else {

                    for (let i = 0; i <= count; i++) {

                        a.appendChild( document.importNode( sel.querySelector(':scope > .slugtable_template_swapiBtn_templatefirst').content, true ) );
                    }
                }
            });

            btnsel.setAttribute('data-count-buttons', (count+1).toString());
        }
    });


    /* Remove column */
    swapinode().addEventListener('click', function(e) {

        let sel = '.swapi_hcm_root[data-swapi-slugtable="swapiBtn"] .slugtable_template_swapiBtn_delcolumn'; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null

        if( sel !== null ) {

            const columnnth = (Array.from(sel.parentNode.parentNode.children).indexOf(sel.parentNode)+1).toString();

            sel = sel.closest('.swapi_hcm_body');  // body

            sel.querySelectorAll(':scope .slugtable_template_swapiBtn_row > :nth-child(' + columnnth + ')').forEach(function(a) {

                a.remove();
            });

            const btnsel = sel.querySelector(':scope > .slugtable_template_swapiBtn_container[data-count-buttons]');
            const count  = parseInt(btnsel.getAttribute('data-count-buttons'));

            btnsel.setAttribute('data-count-buttons', (count-1).toString());
        }
    });

    