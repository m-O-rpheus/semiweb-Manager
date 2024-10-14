

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_registerclickevent = function(cssclass, callback) {

        swapinode().addEventListener('click', function(e) {

            let sel = cssclass; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null
    
            if( sel !== null ) {
    
                callback(sel, e.target);
            }
        });
    };
    // --------------------------------------------------------------------------------------------------

