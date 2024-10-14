

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_autogrowcallbackkeypress = function(cf_obj, cf_field, thisval, prevval) {

        let mstrarr  = funcwysiwyg_autogrowcallbackposdetection(thisval, prevval);
        let exists   = [];
        let errormsg = '';

        mstrarr[0].forEach(function(i) {

            if ( cf_obj.hasOwnProperty(i) ) {

                exists.push(true);
            }
            else {

                exists.push(false);
            }
        });


        // exists - alle Werte true: Aktion innerhalb eines markierten Bereiches. i.O., alle Werte false: Aktion außerhalb eines markierten Bereiches. i.O., gemischt. n.i.O. Fehler!
        exists = Array.from(new Set(exists));  // Remove duplicate entries

        if ( exists.length === 1 ) {

            if ( mstrarr[3] === 'added' || mstrarr[3] === 'removed' ) {

                let new_cf_obj = {};

                const posnum = parseInt(mstrarr[0][0].replace(/[^0-9]/g, ''), 10);    // 2. parameter = basis 10
                const arr    = Object.keys(cf_obj);
                arr.sort();
                arr.forEach(function(i) {

                    let loopnum = parseInt(i.replace(/[^0-9]/g, ''), 10);             // 2. parameter = basis 10

                    if ( loopnum >= posnum ) {

                        if ( mstrarr[3] === 'added' ) {  // added

                            if ( loopnum === posnum && exists[0] === true ) {

                                new_cf_obj[i] = cf_obj[i];                                                  // Attribute übernehmen bei added
                            }

                            new_cf_obj[funcwysiwyg_indicator(((loopnum + mstrarr[4]) - 1))] = cf_obj[i];    // Aktion added außerhalb eines markierten Bereiches BETA
                        }
                        else {                           // removed
                            
                            new_cf_obj[funcwysiwyg_indicator(((loopnum - mstrarr[4]) - 1))] = cf_obj[i];    // Aktion removed außerhalb eines markierten Bereiches BETA
                        }
                    }
                    else {

                        new_cf_obj[i] = cf_obj[i];
                    }
                });


                /*if ( exists[0] === true ) {

                    console.log( "Aktion innerhalb eines markierten Bereiches. Ab position: " + mstrarr[0][0] + " Verschiebe die nachfolgenden Bereiche +/- "+mstrarr[4]+" ("+mstrarr[3]+") ab hier Vergrößere den aktuellen Bereich in cf_obj" );
                }
                else {

                    console.log( "Aktion außerhalb eines markierten Bereiches. Ab position: " + mstrarr[0][0] + " Verschiebe die nachfolgenden Bereiche +/- "+mstrarr[4]+" ("+mstrarr[3]+") ab hier in cf_obj" );
                }*/


                // Override cf_obj
                cf_obj = new_cf_obj;


                // --- Write back to inputfield ---
                cf_field.value = JSON.stringify(cf_obj);
            }
            //else {

                // consistent
            //}
        }
        else {

            errormsg = 'Diese Operation ist nicht erlaubt.';
        }


        // Reset bei Error
        if (errormsg !== '') {

            alert(errormsg);
            console.log(errormsg);
            cf_field.closest('.SLUGTABLE_TEMPLATE_CLASS__content').querySelector(':scope [data-swapiwysiwyg="content_area"]').value = prevval;
            thisval = prevval;
        }


        // Rückgabe
        return [cf_obj, thisval];
    };
    // --------------------------------------------------------------------------------------------------

