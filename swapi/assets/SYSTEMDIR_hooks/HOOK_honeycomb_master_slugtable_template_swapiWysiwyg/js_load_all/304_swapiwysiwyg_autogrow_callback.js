

    /*
        Diese callback funktion wird getriggert bei:

            -----#1-----   EVdefault     Tastendruck im Eingabefeld                     - default
            -----#2-----   EVdefault     SelectionEnd und wysiwygFocusEnd               - setze same as default
            -----#3-----   EVselection   SelectionStart
            -----#4-----   EVbutton      Click on the .swapi_wysiwyg_flow_btn button
    */
    // --------------------------------------------------------------------------------------------------
    window.swapiwysiwyg_autogrow_callback = function swapiwysiwyg_autogrow_callback( thissel, value, previousvalue, event, previousevent ) {

        const content = thissel.closest('.SLUGTABLE_TEMPLATE_CLASS__content');

        if ( content !== null ) {

            const cf_field = content.querySelector(':scope [data-swapiwysiwyg="content_format"]');
            let   cf_obj   = JSON.parse(cf_field.value);
            let   obj      = {};

            if ( event.detail !== null && typeof event.detail === 'object' && event.detail.hasOwnProperty('event') ) {  // Wird ausgeführt bei 'EVselection' und 'EVbutton'

                obj = event.detail;

                // Bei 'EVselection' ist selectionstart/selectionend bereits im object enthalten. Bei 'EVbutton' noch nicht. Hole die Infos aus dem DOM!
                if ( obj.event === 'EVbutton' ) {

                    obj.selectionstart = parseInt(content.querySelector(':scope [data-wysiwyg-selectionstart]').getAttribute('data-wysiwyg-selectionstart'), 10);  // append to object
                    obj.selectionend   = parseInt(content.querySelector(':scope [data-wysiwyg-selectionend]').getAttribute('data-wysiwyg-selectionend'), 10);      // append to object
                }
            }


            // Dieser 'else if' Bereich wird ausschließlich per Tastendruck (Eingabe) Event gefeuert. Die Variable 'value' enthält an dieser Stelle bereits immer die aktuelle Eingabe!
            // Wird nicht bei 'EVdefault' 'EVselection' 'EVbutton' ausgeführt - Beta Version
            else if ( event.inputType !== undefined && typeof event.inputType === 'string' ) {

                [cf_obj, value] = funcwysiwyg_autogrowcallbackkeypress(cf_obj, cf_field, value, previousvalue);
            }


            if ( !obj.hasOwnProperty('event') ) {  // Da 'obj.event' bereits bei dispatchEvent ['EVselection' und 'EVbutton'] definiert wurde, ist es somit nur bei ['EVdefault'] nicht vorhanden. Setze es dort auch.

                obj.event = 'EVdefault';  // if not exists append to object
            }


            if ( obj.event === 'EVselection' || obj.event === 'EVbutton' ) {

                const flowstart    = funcwysiwyg_autogrowcallbackparts(obj, cf_field, cf_obj, funcwysiwyg_autogrowcallbackrange(0, obj.selectionstart, value.slice(0, obj.selectionstart)), false);
                const flowbetween  = funcwysiwyg_autogrowcallbackparts(obj, cf_field, cf_obj, funcwysiwyg_autogrowcallbackrange(obj.selectionstart, obj.selectionend, value.slice(obj.selectionstart, obj.selectionend)), true);
                const flowend      = funcwysiwyg_autogrowcallbackparts(obj, cf_field, cf_obj, funcwysiwyg_autogrowcallbackrange(obj.selectionend, value.length, value.slice(obj.selectionend)), false);

                funcwysiwyg_autogrowcallbackfrontend(content, flowstart[1] + flowbetween[1] + flowend[1]);  // frontend output

                return funcwysiwyg_autogrowcallbackmark(flowstart[0], flowbetween[0], flowend[0], obj.selectionstart, obj.selectionend);
            }
            else {

                const flowunmarked = funcwysiwyg_autogrowcallbackparts(obj, cf_field, cf_obj, funcwysiwyg_autogrowcallbackrange(0, value.length, value), false);

                funcwysiwyg_autogrowcallbackfrontend(content, flowunmarked[1]);                             // frontend output

                return flowunmarked[0];
            }
        }
    };

