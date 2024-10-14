

    /* 21.10.2022 - SWAPI System anpassen - Setze class .paNel_active Bei 'click auf .paNel_task_field' sowie 'mousedown auf .paNel_win_js_active_click' */
    /* ========================================================================================================================================================== */
    function paNel_remove_all_set_active() {

        swapinode().querySelectorAll(':scope .paNel_active').forEach(function(e) { 

            e.classList.remove('paNel_active')
        });
    }


    // Click on Taskbar Item
    paNel_event_listener('click', '.paNel_task_field', function(sel, t) {

        paNel_remove_all_set_active();

        let paNel_task_parent = sel.closest('.paNel_task');
        let paNel_win_parent  = swapinode().querySelector(':scope .paNel_win_embed[data-panel-unique="'+paNel_task_parent.getAttribute('data-panel-unique')+'"]').closest('.swapi_scroll');

        paNel_task_parent.classList.add('paNel_active');                                    // set .paNel_active to Taskbar Item
        paNel_win_parent.classList.add('paNel_active');                                     // set .paNel_active to Window
    });


    // Mousedown on Window
    paNel_event_listener('mousedown', '.paNel_win_js_active_click', function(sel, t) {

        paNel_remove_all_set_active();
    
        let paNel_win_parent  = sel.closest('.swapi_scroll');
        let paNel_task_parent = swapinode().querySelector(':scope .paNel_task[data-panel-unique="'+paNel_win_parent.querySelector(':scope .paNel_win_embed').getAttribute('data-panel-unique')+'"]');

        paNel_task_parent.classList.add('paNel_active');                                    // set .paNel_active to Taskbar Item
        paNel_win_parent.classList.add('paNel_active');                                     // set .paNel_active to Window
    });





    /* 21.10.2022 - SWAPI System anpassen - Setze class .paNel_active bei Document Ready */
    /* ========================================================================================================================================================== */
    function paNel_initial_set_active() {

        if ( swapinode().querySelector(':scope .paNel_task:first-child') !== null ) {

            swapinode().querySelector(':scope .paNel_task:first-child').classList.add('paNel_active');      // set .paNel_active to Taskbar Item
        }

        if ( swapinode().querySelector(':scope .swapi_scroll:first-child') !== null ) {

            swapinode().querySelector(':scope .swapi_scroll:first-child').classList.add('paNel_active');    // set .paNel_active to Window
        }
    }

    swapinode().addEventListener('swapi_after_pagechange', function() {

        paNel_initial_set_active();
    });

    paNel_initial_set_active();

