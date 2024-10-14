

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_autogrowcallbackparts = function(obj, cf_field, cf_obj, range_obj, between) {

        /*
            ERKLÄRUNG:

                obj:        Enthält das vollständige Objekt.
                cf_field:   Das selektierte Element in welchem die Formatierungen gespeichert/gelesen werden.
                cf_obj:     Enthält cf_field.value bei EVdefault, EVselection und EVbutton.
                range_obj:  Enhält ein Object welches alle Char Positionen von 'value' enthält.
                between:    Wenn true ist dieser Text gerade ausgewählt.
        */

        const rangesarr = Object.keys(range_obj);

        // Only on Click 'EVbutton' Icon - Add or Remove from/to Object
        if ( between === true && obj.event === 'EVbutton' ) {

            // --- Add or Remove from/to Object begin ---
            if ( obj.flowbtnthis === 'flow_reset' ) {

                rangesarr.forEach(function(i) {                         // Loop troght all ranges...

                    if ( cf_obj.hasOwnProperty(i) ) {

                        delete cf_obj[i];                               // ...Remove from object
                    }
                });
            }
            else {

                rangesarr.forEach(function(i) {                         // Loop troght all ranges...

                    cf_obj[i] = [obj.flowbtnthis, obj.flowbtnattr];     // ...Add override to object
                });
            }

            // --- Write back to inputfield ---
            cf_field.value = JSON.stringify(cf_obj);

            funcwysiwyg_allresettrigger(cf_field.closest('.SLUGTABLE_TEMPLATE_CLASS__content'));
        }


        // Läuft durch jeden einzelnen Buchstaben im ranges Objekt.
        let compare_tag    = '';
        let chars_backend  = '';
        let chars_frontend = '';

        // HINWEIS: rangesarr Enthält bereits die richtige Sortierung.

        rangesarr.forEach(function(i, index) {                          // Loop troght all ranges...

            let this_char     = funcwysiwyg_htmlspecialchars(range_obj[i]);
            let this_tag      = '';
            let this_encode   = '';
            let char_backend  = this_char;
            let char_frontend = this_char;

            // HINWEIS: Folgende Zeichen &<>'" kommen ab dier Stelle hier nicht mehr vor!

            if ( cf_obj.hasOwnProperty(i) && Array.isArray(cf_obj[i]) && cf_obj[i].length === 2 ) {

                this_tag = cf_obj[i][0];
                this_encode = '&'+btoa(JSON.stringify(cf_obj[i]))+'&';
            }

            if ( index === 0 ) {                    // first loop - first char

                compare_tag = this_tag;
                char_backend  = '<b' + ( ( this_tag !== '' ) ? ' class="'+this_tag+'"' : '') + '>' + char_backend;
                char_frontend = '<i' + this_encode + '>' + char_frontend;       // Verwende einen <i> Tag da es über das flow_menu NICHT auswählbar ist!
            }
            else if ( compare_tag !== this_tag ) {  // middle char

                compare_tag = this_tag;
                char_backend  = '</b><b' + ( ( this_tag !== '' ) ? ' class="'+this_tag+'"' : '') + '>' + char_backend;
                char_frontend = '</i><i' + this_encode + '>' + char_frontend;   // Verwende einen <i> Tag da es über das flow_menu NICHT auswählbar ist!
            }

            chars_backend  += char_backend;
            chars_frontend += char_frontend;
        });

        if ( chars_backend !== '' ) {               // last char

            chars_backend  += '</b>';
            chars_frontend += '</i>';                                           // Verwende einen <i> Tag da es über das flow_menu NICHT auswählbar ist!
        }

        // HINWEIS: chars_frontend erzeugt einen Kodierten String der dann mit PHP regex noch in Ordnung gebracht werden muss!

        return [chars_backend, chars_frontend];
    };
    // --------------------------------------------------------------------------------------------------

