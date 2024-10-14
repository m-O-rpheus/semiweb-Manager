 

    /* 19.10.2022 - SWAPI System anpassen - Window Größe berechnen und Variablen in class .swapi setzen */
    /* ========================================================================================================================================================== */
    function paNel_initial_window_size() {

        swapinode().style.setProperty('--paNel-mainwidth',  String(Math.round(swapinode().clientWidth)) + 'px');
        swapinode().style.setProperty('--paNel-mainheight', String(Math.round(swapinode().clientHeight)) + 'px');
    }

    window.addEventListener('resize', function(e) {

        paNel_initial_window_size();

    }, true);

    paNel_initial_window_size();





    /* 19.10.2022 - SWAPI System anpassen - Windows innerhalb der Viewports verschieben, sowie speichere die Window Positionen in einem seperaten <style id="paNel_pos"> Tag */
    /* ========================================================================================================================================================== */
    const paNel_pos = document.createElement('style');
    paNel_pos.id = 'paNel_pos';
    swapinode().querySelector(':scope .swapi_root').appendChild(paNel_pos);
    const paNel_sheet = paNel_pos.sheet;

    function paNel_sheet_insert_or_update( swapi_scroll_page, custom_properties_name, custom_properties_value ) {

        let cssRules = paNel_sheet.cssRules;

        for ( let i = 0; i < cssRules.length; i++ ) {

            // Rule Exists then Delete
            if ( cssRules[i].cssText.includes(swapi_scroll_page) && cssRules[i].cssText.includes(custom_properties_name) ) {

                paNel_sheet.deleteRule(i);
            }
        }

        // Add New Rule
        paNel_sheet.insertRule('.swapi_scroll[data-swapi-scroll-page="' + swapi_scroll_page + '"]{' + custom_properties_name + ':' + custom_properties_value + '}', 0);
    }


    // Definieren der MouseDown variablen
    let wintarget               = null;
    let winmousetype            = '';
    let mouseXdown              = 0;
    let mouseYdown              = 0;
    let panelWindowWidth        = 0;
    let panelWindowHeight       = 0;
    let panelWindowX            = 0;
    let panelWindowY            = 0;

    let tempPanelWindowWidth    = 0;
    let tempPanelWindowHeight   = 0;

    const panelWindowMinWidth   = 200;
    const panelWindowMinHeight  = 200;


    // MouseDown
    swapinode().addEventListener('mousedown', function(e) {

        if( e.target && e.target.hasAttribute('data-panel-mousedown') ) {
            
            wintarget             = e.target.closest('.swapi_scroll');

            if ( wintarget.classList.contains('paNel_maximize') === false ) {

                winmousetype      = e.target.dataset.panelMousedown;
                mouseXdown        = e.pageX;
                mouseYdown        = e.pageY;
                panelWindowWidth  = parseInt(getComputedStyle(wintarget).getPropertyValue('width'), 10);            /* Wegen --paNel-windowwidth:  calc kann hier kein CSS Custom Properties verwendet werden */
                panelWindowHeight = parseInt(getComputedStyle(wintarget).getPropertyValue('height'), 10);           /* Wegen --paNel-windowheight: calc kann hier kein CSS Custom Properties verwendet werden */
                panelWindowX      = parseInt(getComputedStyle(wintarget).getPropertyValue('--paNel-windowx'), 10);
                panelWindowY      = parseInt(getComputedStyle(wintarget).getPropertyValue('--paNel-windowy'), 10);

                console.log(panelWindowWidth);
            }
        }
    });


    // MouseUp
    swapinode().addEventListener('mouseup', function(e) {

        wintarget    = null;
        winmousetype = '';
    });


    // MouseMove
    swapinode().addEventListener('mousemove', function(e) {

        if ( wintarget !== null && winmousetype !== '' ) {

            let target = wintarget.dataset.swapiScrollPage;

            if ( winmousetype === 'move' ) {


                // Move
                paNel_sheet_insert_or_update( target, '--paNel-windowy', String(Math.round((panelWindowY + ((e.pageY - mouseYdown) * 2)))) + 'px');
                paNel_sheet_insert_or_update( target, '--paNel-windowx', String(Math.round((panelWindowX + ((e.pageX - mouseXdown) * 2)))) + 'px');
            }
            else {


                // Resize Y
                if ( winmousetype === 'resizetop' || winmousetype === 'resizetopleft' || winmousetype === 'resizetopright' ) {

                    tempPanelWindowHeight = ( panelWindowHeight - (e.pageY - mouseYdown) );
                }

                else if ( winmousetype === 'resizebottom' || winmousetype === 'resizebottomleft' || winmousetype === 'resizebottomright' ) {

                    tempPanelWindowHeight = ( panelWindowHeight - ((e.pageY - mouseYdown) * -1) );
                }

                if ( winmousetype === 'resizetop' || winmousetype === 'resizetopleft' || winmousetype === 'resizetopright' || winmousetype === 'resizebottom' || winmousetype === 'resizebottomleft' || winmousetype === 'resizebottomright' ) {

                    if ( tempPanelWindowHeight >= panelWindowMinHeight ) {

                        paNel_sheet_insert_or_update( target, '--paNel-windowy',      String(Math.round((panelWindowY + (e.pageY - mouseYdown)))) + 'px');
                        paNel_sheet_insert_or_update( target, '--paNel-windowheight', String(Math.round(tempPanelWindowHeight)) + 'px');
                    }
                }


                // Resize X
                if ( winmousetype === 'resizeleft' || winmousetype === 'resizetopleft' || winmousetype === 'resizebottomleft' ) {

                    tempPanelWindowWidth = ( panelWindowWidth - (e.pageX - mouseXdown) );
                }

                else if ( winmousetype === 'resizeright' || winmousetype === 'resizetopright' || winmousetype === 'resizebottomright' ) {

                    tempPanelWindowWidth = ( panelWindowWidth - ((e.pageX - mouseXdown) * -1) );
                }

                if ( winmousetype === 'resizeleft' || winmousetype === 'resizetopleft' || winmousetype === 'resizebottomleft' || winmousetype === 'resizeright' || winmousetype === 'resizetopright' || winmousetype === 'resizebottomright' ) {

                    if ( tempPanelWindowWidth >= panelWindowMinWidth ) {

                        paNel_sheet_insert_or_update( target, '--paNel-windowx',     String(Math.round((panelWindowX + (e.pageX - mouseXdown) ))) + 'px');
                        paNel_sheet_insert_or_update( target, '--paNel-windowwidth', String(Math.round(tempPanelWindowWidth)) + 'px');
                    }
                }
            }
        }
    });

    

    /* Maximize */
    function paNel_maximize() {

        swapinode().querySelectorAll(':scope .swapi_scroll').forEach(function(e) {

            if ( getComputedStyle(e).getPropertyValue('--paNel-windowmaximize') === 'auto' ) {
    
                e.classList.add('paNel_maximize');
            }
            else {
    
                e.classList.remove('paNel_maximize');
            }
        });
    }

    swapinode().addEventListener('click', function(e) {

        if( e.target && e.target.tagName === 'A' && e.target.parentNode.classList.contains('paNel_win_topbar_maximize') ) {

            let sel = e.target.closest('.swapi_scroll');

            if ( sel.classList.contains('paNel_maximize') === false ) {

                paNel_sheet_insert_or_update( sel.dataset.swapiScrollPage, '--paNel-windowmaximize', 'auto');  /* true: Missbrauche CSS Custom Properties um den Maximize-Status zu speichern */
            }
            else {

                paNel_sheet_insert_or_update( sel.dataset.swapiScrollPage, '--paNel-windowmaximize', 'none');  /* false: Missbrauche CSS Custom Properties um den Maximize-Status zu speichern */
            }

            paNel_maximize();
        }
    });

    swapinode().addEventListener('swapi_after_pagechange', function(e) {

        paNel_maximize();
    });

    