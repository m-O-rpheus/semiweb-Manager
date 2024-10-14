<?php


    hook_into_swapi_array('swapi_customer_template_html_inside_swapi_section_container', function( array $passthrough ) {

        if ( php_session_is_logged_in() ) {


            $tasks = array();

            $window_generator = function( string $window_id, string $window_content ) use ( &$tasks ) : string {

                $window_unique = swapi_generate_uniquehash();  // # Identify togetherness between '.paNel_task' and '.paNel_win_embed'.

                $window_icon = paNel_icon_from_name( $window_id );

                $tasks[$window_id] = (string) paNel_task( (string) $window_id, (string) $window_icon, (string) $window_unique );

                return (string) paNel_win_embed( (string) $window_id, (string) $window_icon, (string) $window_unique, (string) $window_content );
            };



            /*
                Per javascript werden mehrere Links durch das Trennzeichen '___' getrennt übermittelt. Diese werden gefiltert, um nach Strings zu suchen, die das Trennzeichen enthalten.
                Der String wird mit explode() aufgeteilt und die einzelnen Seiten werden in den Multipage-Stapel hinzugefügt. Anschließend wird der ursprüngliche Eintrag gelöscht.

                Dieser Bereich kann eventuell mit den den HOOK HOOK_swapi_directory_templates_css_js_helper_v2.11 ausgelagert werden.
                --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            */
            $window_ids = array();

            if ( is_array( $passthrough['html'] ) && count( $passthrough['html'] ) === 1 ) {

                $first_key = array_key_first( $passthrough['html'] );

                if ( strpos( $first_key, '___' ) !== false ) {

                    foreach ( explode( '___', $first_key ) as $window_id ) {

                        $window_ids[$window_id] = true;
                    }

                    unset( $passthrough['html'][$first_key] );
                }
            }

            if ( !empty( $window_ids ) ) {

                $dir = (string) swapi_paths()['PATH_dir_customers_templates'];

                foreach ( array_keys( $window_ids ) as $window_id ) {

                    if ( swapi_validate_minimalist( $window_id ) && file_exists( $dir . $window_id ) ) {
        
                        ob_start();
                        $is_include = (bool) true;
                        require ( $dir . $window_id );
                        $content = (string) ob_get_clean();
    
                        $passthrough['html'][$window_id] = (string) $content;
                    }
                }
            }


            
            /*
                Die Fenster vom Start Screen Löschen!
                --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            */
            if ( array_key_exists( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_dashboard'], $passthrough['html'] ) ) {

                unset( $passthrough['html'][$GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_dashboard']] );
            }
            if ( array_key_exists( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], $passthrough['html'] ) ) {

                unset( $passthrough['html'][$GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index']] );
            }



            /*
                Durchlaufe alle Seiten im Multipage-Stapel und verpacke sie in ein neues HTML-DOM, das die Windows repräsentiert.
                --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            */
            if ( !empty( $passthrough['html'] ) ) {

                foreach ( $passthrough['html'] as $window_id => $window_content ) {

                    $passthrough['html'][$window_id] = (string) $window_generator( $window_id, $window_content );
                }
            }



            /*
                Füge WALLPAPER_AND_TASKBAR sowie MAINMENU ebenfalls mit zum Multipage-Stapel hinzu.
                --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            */
            if ( !array_key_exists( 'WALLPAPER_AND_TASKBAR', $passthrough['html'] ) ) {

                $html = paNel_loggedin_embed( implode( '', $tasks) );

                $html .= swapi_enqueue_style([ swapi_paths()['PATH_dir_customers_root'] . 'if_logged_in_css' ], 'paNel_loggedin');
                $html .= swapi_enqueue_script([ swapi_paths()['PATH_dir_customers_root'] . 'if_logged_in_js' ], 'paNel_loggedin');

                $passthrough['html']['WALLPAPER_AND_TASKBAR'] = $html;
            }

            if ( !array_key_exists( 'MAINMENU', $passthrough['html'] ) ) {

                $passthrough['html']['MAINMENU'] = paNel_mainmenu();
            }
        }
        

        return (array) $passthrough;

    }, 20);


?>