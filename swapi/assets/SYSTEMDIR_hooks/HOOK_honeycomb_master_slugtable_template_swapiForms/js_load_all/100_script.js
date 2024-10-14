

    /* Add fieldset, row and attribute */
    swapinode().addEventListener('click', function(e) {

        let sel = '.swapi_hcm_root[data-swapi-slugtable="swapiForms"] .swapi_btn'; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null

        if( sel !== null ) {

            const parent = sel.closest('.swapi_hcm_body');


            // +++++++ fieldset and row +++++++
            if ( sel.parentNode.classList.contains("slugtable_template_swapiForms_add_field") ) {


                // ------- 1. Read Fieldset Labeling from types menu. If is empty generate random string -------
                let labeling = parent.querySelector(':scope [data-swapiforms-fieldset-labeling]').value;

                if ( labeling === '' ) {

                    labeling = 'none_' + Math.random().toString(36).substring(2) + (new Date()).getTime().toString(36);  // Random String
                }


                // ------- 2. Loop through all input fields with the data attribute data-swapiforms-fieldset. If an input with the fieldset label is already found select it, otherwise create a new fieldset from the template -------
                let fieldset_inner = null;

                parent.querySelectorAll(':scope [data-swapiforms-fieldset]').forEach(function(ev) {

                    if ( ev.value === labeling ) {

                        fieldset_inner = ev.closest('.slugtable_template_swapiForms_fieldset');
                    }
                });
   
                if( fieldset_inner === null ) {
    
                    parent.querySelector(':scope > .SLUGTABLE_TEMPLATE_INSERT').appendChild( document.importNode( parent.querySelector(':scope > .SLUGTABLE_TEMPLATE_CLASS__fieldset').content, true ) );

                    fieldset_inner = parent.querySelector(':scope .SLUGTABLE_TEMPLATE_CLASS__fieldset:last-child .slugtable_template_swapiForms_fieldset');

                    fieldset_inner.querySelector(':scope [data-swapiforms-fieldset]').value = labeling;
                }


                // ------- 3. Append row to selected fieldset -------
                fieldset_inner.querySelector(':scope > .SLUGTABLE_TEMPLATE_INSERT').appendChild( document.importNode( parent.querySelector(':scope > .SLUGTABLE_TEMPLATE_CLASS__row').content, true ) );
    

                // ------- 4. Append row type new row -------
                fieldset_inner.querySelectorAll(':scope .SLUGTABLE_TEMPLATE_CLASS__row:last-child [data-swapiforms-field-type]').forEach(function(ev) { 
    
                    ev.setAttribute('data-swapiforms-field-type', sel.getAttribute('data-swapiforms-field-type') );
                });
            }


            // +++++++ attribute +++++++
            else if ( sel.parentNode.classList.contains("slugtable_template_swapiForms_add_attribute") ) {

                sel.parentNode.previousSibling.appendChild( document.importNode( parent.querySelector(':scope > .SLUGTABLE_TEMPLATE_CLASS__attribute').content, true ) );
            }

        }
    });

