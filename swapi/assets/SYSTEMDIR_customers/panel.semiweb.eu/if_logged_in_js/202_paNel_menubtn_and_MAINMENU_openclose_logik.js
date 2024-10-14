

    /* Open MAINMENU */
    paNel_event_listener('click', '.paNel_menubtn', function(sel, t) {

        sel.closest('.swapi_section').querySelector(':scope > .paNel_M').classList.add('paNel_menuactive');
    });


    /* Close MAINMENU */
    paNel_event_listener('click', '.paNel_menuactive', function(sel, t) {

        sel.classList.remove('paNel_menuactive');
    });

    