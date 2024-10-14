

    /* 20.01.2023 - EventListener für Standardevents */
    /* ========================================================================================================================================================== */
    function paNel_event_listener(name, cssclass, callback) {

        swapinode().addEventListener(name, function(e) {

            let sel = cssclass; sel = ( e.target.matches(sel) ? e.target : e.target.closest(sel) );  // sel or null
    
            if( sel !== null ) {
    
                callback(sel, e.target);
            }
        });
    };

