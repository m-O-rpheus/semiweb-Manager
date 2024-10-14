

    const funcname = 'data-swapi-js-val-innerhtml-passthrough-func';

    let previous_val;
    let previous_e;

    ['beforeinput','input'].forEach(function(evt) {

        swapinode().addEventListener(evt, function(e) {
            
            const sel = e.target;

            if ( sel.dataset.swapiOverrideStyle === 'area_autogrow' ) {
    
                if ( sel.hasAttribute(funcname) && sel.getAttribute(funcname) !== '' && typeof window[sel.getAttribute(funcname)] === 'function' ) {  // true if data-attr and function exists
    
                    if (e.type === 'input') {

                        sel.parentNode.nextSibling.innerHTML = window[sel.getAttribute(funcname)](sel, sel.value, previous_val, e, previous_e);
                    }
                    else {

                        previous_val = sel.value;
                        previous_e   = e;
                    }
                }
                else if (e.type === 'input') {

                    sel.parentNode.nextSibling.innerHTML = sel.value;
                }
            }
        });
    });

    