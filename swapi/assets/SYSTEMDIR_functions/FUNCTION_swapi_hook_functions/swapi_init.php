<?php

    $GLOBALS['swapi_hook'] = (array) array();



/* ========================================================================================================================================================== */

    /* HOOK POINT REGISTRIEREN */
    /*
        Developer Ausgabe für innerhalb der Funktion
        echo '<pre>' . print_r( $GLOBALS['swapi_hook'][$hookname], true ) . '</pre>';
        echo '<pre>' . print_r( $passthrough, true ) . '</pre>';

        Performence Optimierung 29.10.2023

    */
    /*function register_swapi_hook_point_passthrough_array( string $hookname, array $passthrough = array() ): array {

        // Eintrag für den Hook-Punkt hinzufügen, enthält das 'passthrough'-Array und die Priorität 0
        $GLOBALS['swapi_hook'][$hookname][] = array( 'passthrough' => $passthrough, 'prio' => (int) 0 );

        // Sortieren der Hook-Funktionen nach Priorität
        usort( $GLOBALS['swapi_hook'][$hookname], function( $a, $b ) { return $a['prio'] <=> $b['prio']; });

        // Das 'passthrough'-Array des ersten Eintrags im Hook-Array extrahieren
        $passthrough = array_shift( $GLOBALS['swapi_hook'][$hookname] )['passthrough'];

        // Überprüfen, ob weitere Hook-Funktionen vorhanden sind
        if ( !empty( $GLOBALS['swapi_hook'][$hookname] ) ) {

            // Alle Hook-Funktionen durchlaufen und das 'passthrough'-Array erweitern
            foreach ( $GLOBALS['swapi_hook'][$hookname] as $hook_point_loop ) {

                $passthrough = (array) $hook_point_loop['hookfunction']( $passthrough );
            }
        }
        
        // Das ineinandergehakte 'passthrough'-Array zurückgeben
        return $passthrough;
    }*/

    function register_swapi_hook_point_passthrough_array( string $hookname, array $passthrough = array() ): array {

        // Proceed only if hook points with this name have been defined in 'hook_into_swapi_array'.
        if ( array_key_exists( $hookname, $GLOBALS['swapi_hook'] ) === true ) {

            // Sort in ascending order. The <=> operator returns 0 if the values are equal, -1 if the left value is smaller, and 1 if the right value is smaller.
            usort( $GLOBALS['swapi_hook'][$hookname], function( $a, $b ) { return $a[1] <=> $b[1]; });  //  ArrayKey 1 = priority

            // Loop through all 'swapi_hook_add_filter' in ascending order, while expanding the 'passthrough' variable with each iteration.
            foreach ( $GLOBALS['swapi_hook'][$hookname] as $array ) {

                $passthrough = (array) $array[0]( $passthrough );  // ArrayKey 0 = callback
            }
        }

        return (array) $passthrough;
    }

/* ========================================================================================================================================================== */







/* ========================================================================================================================================================== */

    /* EIN-HOOK FUNKTION REGISTRIEREN */
    /*OLD function hook_into_swapi_array( string $hookname, callable $hookfunction, int $priority = 1 ): void {

        $GLOBALS['swapi_hook'][$hookname][] = array( 'hookfunction' => $hookfunction, 'prio' => (int) (($priority < 1) ? 1 : $priority) );
    }*/

    function hook_into_swapi_array( string $hookname, callable $callback, int $priority = 1 ): void {

        // If no hook point with this name has been defined yet, create an empty array.
        if ( array_key_exists( $hookname, $GLOBALS['swapi_hook'] ) === false ) {

            $GLOBALS['swapi_hook'][$hookname] = (array) array();
        }

        // Add in the stack into the GLOBALS array
        $GLOBALS['swapi_hook'][$hookname][] = array( $callback, ( ( $priority < 1 ) ? 1 : $priority ) );  // ArrayKey 0 = callback, ArrayKey 1 = priority
    }

/* ========================================================================================================================================================== */



?>