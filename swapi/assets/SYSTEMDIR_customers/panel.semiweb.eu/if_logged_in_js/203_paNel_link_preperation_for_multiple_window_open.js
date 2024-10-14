

    function paNel_link_preperation() {


        // Durchlaufe alle Fenster aber nicht WALLPAPER_AND_TASKBAR oder MAINMENU speichere diese in einem JavaScript Object
        // -------------------------------------------------
        let links = {};

        swapinode().querySelectorAll(':scope .swapi_scroll:not(.paNel_WAT):not(.paNel_M)').forEach(function(e) {

            links[e.getAttribute('data-swapi-scroll-page')] = true;
        });


        // Annonyme funktion Registrieren
        // -------------------------------------------------
        let append_links_from_hrefs = function(e) {

            if ( !e.hasAttribute('data-panel-old-unload') ) {

                e.setAttribute('data-panel-old-unload', e.getAttribute('data-swapi-unload'));
            }

            return Object.assign({}, {[e.getAttribute('data-panel-old-unload')]: true}, links);
        };


        // Durchlaufe alle Links im MAINMENU
        // -------------------------------------------------
        swapinode().querySelectorAll(':scope .paNel_mainmenu [data-swapi-unload]').forEach(function(e) {

            let new_links = append_links_from_hrefs(e);

            e.setAttribute('data-swapi-unload', Object.keys(new_links).join('___') );
        });


        // Durchlaufe alle Schließen Buttons
        // -------------------------------------------------
        swapinode().querySelectorAll(':scope .paNel_win_topbar_close [data-swapi-unload]').forEach(function(e) {

            let new_links = append_links_from_hrefs(e);

            delete new_links[e.getAttribute('data-panel-old-unload')];  // Entferne das Fenster selbst aus dem Objekt!

            if ( Object.keys(new_links).length === 0 ) {                // Ist das Object leer, setzt Dashboard ein!

                new_links['_LOGGEDIN_dashboard.php'] = true;
            }

            e.setAttribute('data-swapi-unload', Object.keys(new_links).join('___') );
        });
    }



    swapinode().addEventListener('swapi_after_pagechange', function(e) {

        paNel_link_preperation();
    });

    paNel_link_preperation();

