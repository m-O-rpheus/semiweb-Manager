

    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */
    

    // ====== Helperfunction ======
    const request_receive_obj = function( action, slugtable, unique, identifiercode ) {

        return { 'hcmaction': action, 'hcmslugtable': slugtable, 'hcmunique': unique, 'hcmidentifiercode': identifiercode };
    };





    // ====== Click on: ( almost everywhere Request to Server ) ======
    swapinode().addEventListener('click', function(e) {

        let sel = '.swapi_hcm_root .swapi_btn'; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null
    
        if( sel !== null ) {

            const p = sel.closest('.swapi_hcm_root');



            // ------ Click on: Template Element ( #paste ) ------
            if ( sel.classList.contains('swapi_hcm_choosen') ) {

                swapi_objdetail_submit( Object.assign(request_receive_obj( 'paste', p.dataset.swapiSlugtable, p.dataset.swapiUnique, p.dataset.swapiIdentifiercode ), { 'hcmnaming': sel.parentNode.dataset.swapiNaming }) );
            }



            // ------ Click on: Delete Button ( #delete ) ------
            else if ( sel.classList.contains('swapi_hcm_delete') ) {

                swapi_objdetail_submit( Object.assign(request_receive_obj( 'delete', p.dataset.swapiSlugtable, p.dataset.swapiUnique, p.dataset.swapiIdentifiercode ), { 'hcmnaming': sel.parentNode.dataset.swapiNaming }) );
            }



            // ------ Click on: Confirmation Button ( #confirmation ) ------
            else if ( sel.classList.contains('swapi_hcm_confirmation') ) {

                swapi_objdetail_submit( Object.assign(request_receive_obj( 'confirmation', p.dataset.swapiSlugtable, p.dataset.swapiUnique, p.dataset.swapiIdentifiercode ), { 'hcmnaming': sel.dataset.swapiNaming }) );
            }



            // ------ Click on: Save Button ( #save ) ------
            else if ( sel.classList.contains('swapi_hcm_savebtn') ) {


                // ====================================================
                // ============ READ INPUT FIELDS FROM DOM ============ begin
                // ====================================================

                    const pastesel = p.querySelector(':scope .swapi_hcm_paste');

                    let i   = 0;
                    let obj = {};

                    // ###1. --- Iterate through all input fields in the DOM
                    pastesel.querySelectorAll(':scope .swapi_field').forEach(function(a) {

                        let item      = '{{item' + i + '}}';
                        let nodecopy  = a;
                        let structure = [];

                        // ###2. --- Write input item reference to DOM - Dieser Bereich könnte auch an das Ende der forEach geschrieben werden, dann aber erzeugt man eine Inkositsenz bei hcmItem vor und nach dem Speichern!
                        i++;
                        a.dataset.hcmItem = item;

                        // ###3. --- Determine the HTML structure nesting of each input field as well as the template elements
                        do {

                            if ( nodecopy.classList.contains('swapi_hcm_paste') ) {

                                structure.unshift('ROOT');
                                break;                                  // Abbruch der Schleife!
                            }
                            else if ( nodecopy.classList.value.includes('SLUGTABLE_TEMPLATE_CLASS__') ) {

                                structure.unshift('>{{' + Array.from(nodecopy.classList).find(function (cn) { return cn.includes('SLUGTABLE_TEMPLATE_CLASS__'); }) + '}}__pos' + (Array.from(nodecopy.parentNode.children).indexOf(nodecopy)).toString());
                            }
                            else {

                                structure.unshift('>pos' + (Array.from(nodecopy.parentNode.children).indexOf(nodecopy)).toString());
                            }

                            nodecopy = nodecopy.parentNode;             // Sofern die do while noch nicht Abgebrochen wurde, eine Ebene nach Oben für den nächsten durchlauf!

                        } while ( true );


                        
                        // ###4. --- Read content of input fields
                        if ( a.classList.contains('swapi_field_selection') ) {

                            obj[item] = { 'itemstructure': structure.join(''), 'itemdataset': a.dataset, 'itemvalue': a.checked.toString() };
                        }
                        else if ( a.classList.contains('swapi_field_files') ) {
        
                            // Input File gibt es aktuell nicht!
                        }
                        else {
        
                            obj[item] = { 'itemstructure': structure.join(''), 'itemdataset': a.dataset, 'itemvalue': a.value.toString() };
                        }
                    });


                    // ###5. --- Create final object and send to server
                    const finalobj = { 'htmlsnapshot_only_master': pastesel.innerHTML, 'inputfields_both': obj };

                    swapi_objdetail_submit( Object.assign(request_receive_obj( 'save', p.dataset.swapiSlugtable, p.dataset.swapiUnique, p.dataset.swapiIdentifiercode ), { 'hcmnaming': pastesel.querySelector(':scope .swapi_hcm_head .swapi_field').value.toString(), 'hcmsave': finalobj }) );
                    
                // ====================================================
                // ============ READ INPUT FIELDS FROM DOM ============ end
                // ====================================================

            }



            // ------ Click on: Unset Button ( #unset ) ------
            else if ( sel.classList.contains('swapi_hcm_unset') ) {

                sel.parentNode.parentNode.remove();
            }



            // ------ Click on: Export Button ( #export ) ------
            else if ( sel.classList.contains('swapi_hcm_exportbtn') ) {

                let streamnaming  = p.querySelector(':scope [data-hcm-naming]').value;
                
                let streamfield   = {'naming': streamnaming};
                let streamcounter = 0;

                p.querySelector(':scope .swapi_hcm_body').querySelectorAll(':scope .swapi_field').forEach(function(a) {

                    streamcounter++;
            
                    if ( a.classList.contains('swapi_field_selection') ) {
            
                        streamfield['field'+streamcounter.toString()] = a.checked.toString();
                    }
                    else if ( a.classList.contains('swapi_field_files') ) {
            
                        // Input File gibt es aktuell nicht!
                    }
                    else {
            
                        streamfield['field'+streamcounter.toString()] = a.value.toString();
                    }
                });

                let elem = document.createElement('a');
                elem.setAttribute('href', 'data:text/plain;charset=UTF-8;base64,' + btoa(   encodeURIComponent(JSON.stringify(streamfield)))   );
                elem.setAttribute('download', 'swapi_hcm_exportfile_' + p.getAttribute('data-swapi-slugtable') + '_' + streamnaming + '.txt');
                elem.style.display = 'none';

                document.body.appendChild(elem);
                
                elem.click();
                
                document.body.removeChild(elem);
            }



            // ------ Click on: Import Button Toggle ( #import ) ------
            else if ( sel.classList.contains('swapi_hcm_importbtn') ) {

                sel.nextElementSibling.classList.toggle('swapi_hcm_hide');
            }



            // ------ Click on: Move Up ( #move_up ) or Move Down ( #move_down ) via else ------
            else {

                const margintransition = function( move1, move2 ) {

                    move1.setAttribute('style', '--swapi-height-margin:' + move2.offsetHeight + 'px 0px 0px');
                    move2.setAttribute('style', '--swapi-height-margin:-' + (move2.offsetHeight + move1.offsetHeight) + 'px 0px ' + move1.offsetHeight + 'px');

                    window.requestAnimationFrame(function() {
                        move1.classList.add('margintransition');
                        move2.classList.add('margintransition');
                    });
                };

                if ( sel.classList.contains('swapi_hcm_up') ) {

                    let move_this  = sel.parentNode.parentNode.parentNode;
                    let move_other = move_this.previousElementSibling;
    
                    if ( move_other ) {
                        move_this.parentNode.insertBefore( move_this, move_other );
                        margintransition( move_this, move_other );
                    }
                }
                else if ( sel.classList.contains('swapi_hcm_down') ) {

                    let move_this  = sel.parentNode.parentNode.parentNode;
                    let move_other = move_this.nextElementSibling;
    
                    if ( move_other ) {
                        move_this.parentNode.insertBefore( move_other, move_this );
                        margintransition( move_other, move_this );
                    }
                }
            }
        }
    });



    // ====== FOR: #move_up and #move_down ======
    swapinode().addEventListener('transitionend', function(e) {

        if ( e.target.classList.value.includes('SLUGTABLE_TEMPLATE_CLASS__') ) {

            e.target.removeAttribute('style');
            e.target.classList.remove('margintransition');
        }
    });





    // ====== OBJDETAIL: Receive from Server ======
    swapinode().addEventListener('swapi_objdetail_callback', function(e) {

        if ( e.detail.cb.hasOwnProperty('hcmslugtable') && e.detail.cb.hasOwnProperty('hcmunique') && e.detail.cb.hasOwnProperty('hcmidentifiercode') ) {

            const p = swapinode().querySelector(':scope .swapi_hcm_root[data-swapi-slugtable="' + e.detail.cb.hcmslugtable + '"][data-swapi-unique="' + e.detail.cb.hcmunique + '"]');

            if ( p !== null && e.detail.cb.hcmidentifiercode === p.dataset.swapiIdentifiercode ) {



                // ------ Receive ( #paste ) ------
                if ( e.detail.cb.hcmaction === 'paste' ) {


                    // ====================================================
                    // ========== WRITE BACK INPUT FIELDS TO DOM ========== begin
                    // ====================================================

                        const pastesel = p.querySelector(':scope .swapi_hcm_paste');

                        // ###1. --- Delete existing content
                        pastesel.innerHTML = '';

                        // ###2. --- If there is already an HTML template in the database, load it. Otherwise use the template template
                        if ( e.detail.cb.hcmresult.hasOwnProperty('htmlsnapshot_only_master') && e.detail.cb.hcmresult.hasOwnProperty('inputfields_both') ) {

                            // ###3. --- HTML DOM write back
                            pastesel.innerHTML = e.detail.cb.hcmresult['htmlsnapshot_only_master'];

                            // ###4. --- Iterate through all input fields in the DOM
                            pastesel.querySelectorAll(':scope .swapi_field').forEach(function(a) {
                                
                                if ( a.classList.contains('swapi_field_selection') ) {

                                    a.checked = ( ( e.detail.cb.hcmresult['inputfields_both'][a.dataset.hcmItem].itemvalue.toString() === 'true' ) ? true : false );
                                }
                                else if ( a.classList.contains('swapi_field_files') ) {
                
                                    // Input File gibt es aktuell nicht!
                                }
                                else {

                                    a.value = e.detail.cb.hcmresult['inputfields_both'][a.dataset.hcmItem].itemvalue.toString();
                                }
                            });
                        }
                        else {

                            pastesel.appendChild( document.importNode( p.querySelector(':scope .swapi_hcm_template').content, true ) );  // Add new Content ( Template )
                        }



                        const headersel = p.querySelector(':scope .swapi_hcm_header');

                        // ###5. --- Delete existing header
                        headersel.innerHTML = '';

                        // ###6. --- Output the status (date time) from the template and write it to the HTML DOM
                        if ( headersel.hasAttribute('data-template-hcm-time') ) {

                            let date  = new Date( parseInt( headersel.getAttribute('data-template-hcm-time') ) );
                            let stand = 'Current stand of the template: ' + date.toLocaleDateString('de-DE') + ' ' + date.toLocaleTimeString('de-DE');

                            headersel.insertAdjacentHTML('beforeend', '<div class="swapi_hcm_time">' + stand + '</div>');
                        }

                        // ###7. --- Check if template is outdated and write it to the HTML DOM
                        if ( headersel.hasAttribute('data-template-hcm-hash') ) {

                            let obsolete = 'The template is out of date, please update this form';

                            if ( headersel.getAttribute('data-template-hcm-hash') === p.querySelector(':scope .swapi_hcm_template').content.querySelector(':scope [data-template-hcm-hash]').getAttribute('data-template-hcm-hash') ) {

                                obsolete = 'The template is up to date';
                            }

                            headersel.insertAdjacentHTML('beforeend', '<div class="swapi_hcm_hash">' + obsolete + '</div>');
                        }

                        // ###8. --- Set Naming into DOM
                        headersel.setAttribute('data-template-hcm-naming', p.querySelector(':scope [data-hcm-naming]').value );


                    // ====================================================
                    // ========== WRITE BACK INPUT FIELDS TO DOM ========== end
                    // ====================================================

                }



                // ------ Receive ( #delete or #save ) ------
                else if ( e.detail.cb.hcmaction === 'delete' || e.detail.cb.hcmaction === 'confirmation' || e.detail.cb.hcmaction === 'save' ) {

                    p.querySelector(':scope .swapi_hcm_msg').innerHTML = e.detail.cb.hcmresult.state;
                }


                
            }
        }
    });



    // ------ Change: Import File ( #import ) ------
    swapinode().addEventListener('change', function(e) {

        if( e.target && e.target.classList.contains('swapi_field_file') && e.target.parentNode.parentNode.classList.contains('swapi_hcm_importfile') ) {

            const p = e.target.closest('.swapi_hcm_root');

            const file   = e.target.files[0];
            const reader = new FileReader();

            reader.readAsText(file); 
            reader.onload = function(a) {

                let streamfield   = JSON.parse(decodeURIComponent(a.target.result));
                let streamcounter = 0;

                p.querySelector(':scope [data-hcm-naming]').value = streamfield.naming;

                delete streamfield.naming;

                p.querySelector(':scope .swapi_hcm_body').querySelectorAll(':scope .swapi_field').forEach(function(a) {

                    streamcounter++;
                                            
                    if ( a.classList.contains('swapi_field_selection') ) {
            
                        a.checked = ( ( streamfield['field'+streamcounter.toString()] === 'true' ) ? true : false );
                    }
                    else if ( a.classList.contains('swapi_field_files') ) {
            
                        // Input File gibt es aktuell nicht!
                    }
                    else {
            
                        a.value = streamfield['field'+streamcounter.toString()].toString();
                    }
                });
            };
        }
    });


    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */

