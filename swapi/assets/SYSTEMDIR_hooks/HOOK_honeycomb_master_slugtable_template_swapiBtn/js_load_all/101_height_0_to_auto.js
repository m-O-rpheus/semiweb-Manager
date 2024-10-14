

    /* Click on example button - animate 0 to auto */
    swapinode().addEventListener('click', function(e) {

        let sel = '.swapi_hcm_root[data-swapi-slugtable="swapiBtn"] .slugtable_template_swapiBtn_example'; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null

        if( sel !== null ) {

            sel = sel.parentNode;

            let newclass = false;

            if ( sel.classList.contains('slugtable_template_swapiBtn_btnopen') === false ) {

                newclass = true;
            }

            sel.closest('.swapi_hcm_body').querySelectorAll(':scope .slugtable_template_swapiBtn_stateopen').forEach(function(a) {

                a.classList.remove('slugtable_template_swapiBtn_stateopen');
            });

            sel.closest('.swapi_hcm_body').querySelectorAll(':scope .slugtable_template_swapiBtn_btnopen').forEach(function(a) {

                a.classList.remove('slugtable_template_swapiBtn_btnopen');
            });

            if ( newclass ) {

                sel.parentNode.classList.add('slugtable_template_swapiBtn_stateopen');
                sel.classList.add('slugtable_template_swapiBtn_btnopen');
            }
        }
    });

    