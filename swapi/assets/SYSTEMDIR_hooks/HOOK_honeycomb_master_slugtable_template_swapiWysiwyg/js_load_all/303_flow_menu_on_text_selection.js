

    // read the saved formatting for the selected area and write it back to flowbar
    // --------------------------------------------------------------------------------------------------
    let selection_timer = null;

    const funcwysiwyg_fillmenu = function(selectionstart, selectionend, content) {

        clearTimeout(selection_timer);

        selection_timer = setTimeout(function() {

            if ( content.querySelector(':scope .swapi_wysiwyg_flow_wrap') !== null ) {  // selection_timer Bugfix - Prüfe ob das flow menu noch da ist!

                const cf_obj = JSON.parse(content.querySelector(':scope [data-swapiwysiwyg="content_format"]').value);

                let flow_tags = [];
                let flow_attr = [];

                for(let i = selectionstart; i < selectionend; i++ ) {
        
                    let c = funcwysiwyg_indicator(i);
                    let flow_tags_i = 'flow_reset';
                    let flow_attr_i = {};
        
                    if ( cf_obj.hasOwnProperty(c) && Array.isArray(cf_obj[c]) && cf_obj[c].length === 2 ) {

                        flow_tags_i = cf_obj[c][0];
                        flow_attr_i = cf_obj[c][1];
                    }
        
                    flow_tags.push(flow_tags_i);
                    flow_attr.push(flow_attr_i);
                }
        
                const btnnamesarr = Array.from(new Set(flow_tags));  // Remove duplicate entries
                const btnnameslen = btnnamesarr.length;
                const sel         = content.querySelector(':scope .swapi_wysiwyg_flow_attr_append');

                funcwysiwyg_resetbtnactive(content, btnnamesarr, btnnameslen);
                
                sel.innerHTML = '';  // delete attribute fields
    
                // This if is only executed if the marked area contains attributes
                if ( btnnameslen === 1 && typeof flow_attr[0] !== 'undefined' ) {
    
                    const keys = Object.keys(flow_attr[0]);

                    if ( keys.length > 0 ) {

                        keys.forEach(function() {   // add attribute fields in loop
        
                            sel.appendChild( document.importNode( sel.closest('.swapi_hcm_body').querySelector(':scope > .swapi_wysiwyg_attr_tmpl').content, true ) );
                        });
    
                        let j = 0;

                        keys.forEach(function(i) {  // add values to attribute fields in loop
    
                            j++;

                            let childsel = sel.querySelector(':scope .swapi_wysiwyg_attr_main:nth-child('+j+')');

                            if( childsel !== null ) {

                                childsel.querySelector(':scope [data-swapiwysiwyg="attr_name"]').value = i;
                                childsel.querySelector(':scope [data-swapiwysiwyg="attr_value"]').value = flow_attr[0][i];
                            }    
                        });
                    }
                }
            }

        }, 100);
    };



    // Trigger selection change event. If the choice is true, add class and navigation menu, otherwise remove this
    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_seltrigger = function() {

        let sel = swapinode().querySelector(':scope .wysiwyg_focus [data-swapiwysiwyg="content_area"]:focus');

        if ( sel !== null ) {  // only if the bar is open and the textarea with the data attribute [data-swapiwysiwyg="content_area"] has the :focus

            const selectionstart = parseInt(sel.selectionStart, 10);
            const selectionend   = parseInt(sel.selectionEnd, 10);

            if ( selectionend > selectionstart ) {

                const content    = sel.closest('.SLUGTABLE_TEMPLATE_CLASS__content');
                const flowbar    = content.querySelector(':scope .swapi_wysiwyg_content_flow');
        
                // [Add] class .wysiwyg_selected to this content
                content.classList.add('wysiwyg_selected');
        
                // [Add] template to .swapi_wysiwyg_content_flow in this content
                if ( flowbar.firstChild === null ) {
        
                    flowbar.appendChild( document.importNode( content.closest('.swapi_hcm_body').querySelector(':scope > .swapi_wysiwyg_flow_tmpl').content, true ) );
                }
        
                // [Add] read the saved formatting for the selected area and write it back to flowbar
                funcwysiwyg_fillmenu(selectionstart, selectionend, content);
        
                // [Trigger Event] on autogrow
                content.querySelector(':scope [data-swapiwysiwyg="content_area"]').dispatchEvent(new CustomEvent('input', { bubbles: true, detail: { 'event': 'EVselection', 'selectionstart': selectionstart, 'selectionend': selectionend } }));
            }
            else {

                funcwysiwyg_hideflowselections(sel.closest('.swapi_hcm_body'));
            }
        }
    };

    // Event: selectionchange (Firefox) 
    swapinode().addEventListener('selectionchange', function() {
        funcwysiwyg_seltrigger();
    });

    // Event: selectionchange (Other Browser) 
    document.addEventListener('selectionchange', function() {
        funcwysiwyg_seltrigger();
    });



    // Special position for Flow Menu Bar
    // --------------------------------------------------------------------------------------------------
    const observer = new MutationObserver(function(mutations) {

        mutations.forEach(function(mutation) {

            if ( mutation.target && mutation.target.classList.contains('swapi_area_autogrow_resize') && mutation.target.closest('[data-swapi-slugtable="swapiWysiwyg"]') !== null ) {

                const content  = mutation.target.closest('.SLUGTABLE_TEMPLATE_CLASS__content');

                if ( content !== null ) {

                    const flowspan = content.querySelector(':scope .swapi_wysiwyg_flow_span');

                    if ( flowspan !== null ) {
            
                        content.querySelector(':scope .swapi_wysiwyg_flow_wrap').style.setProperty('--top', (flowspan.offsetHeight + flowspan.offsetTop) + 'px');  // Berechne Position der Flow Menu Bar
                    }
                }
            } 
        });
    });

    observer.observe(swapinode(), {childList: true, subtree: true, attributes: false, characterData: false});

