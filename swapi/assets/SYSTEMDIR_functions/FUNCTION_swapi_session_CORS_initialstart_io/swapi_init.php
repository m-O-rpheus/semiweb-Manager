<?php


/* 

    Kommentar NEU 26.10.2022: Bei dem erstmaligem besuchen der Webseite, sowie bei jedem weiteren Reload Vorgang, wird der einmalige Array Key 'LLsInit' per JS übermittelt, welcher
    einen Timestamp (nur Zahlen) enthält. Beim Reload Vorgang der Seite, wird der timestamp dann auf einen aktuelleren Wert aktualisiert und in der SESSION überschrieben.
    Somit kann erkannt werden, wann die Seite Neugeladen wurde! Prüfe sowie Speichere dies in der Session 'core_session_reload'. Nur wenn alles Korrekt ist mache weiter!

/* ========================================================================================================================================================== */

    function swapi_session_CORS_initialstart_io( array $array_object ): bool {

        if (
            array_key_exists( 'LLsInit', $array_object )
            &&
            array_key_exists( 'LLsPage', $array_object )
            &&
            array_key_exists( 'LLsTag', $array_object ) && is_string( $array_object['LLsTag'] ) && substr($array_object['LLsTag'], 0, 6) === 'swapi-' && swapi_validate_uniquehash( substr($array_object['LLsTag'], 6) )
        ) {

            $js_timestamp_identifier = (string) strval( $array_object['LLsInit'] );                         // Konvertiere nach String

            if ( ctype_digit( $js_timestamp_identifier ) && strlen( $js_timestamp_identifier ) > 8 ) {      // Nur Zahlen und Mehr als 8 Zeichen (Clients mit falsch eingestellter Uhrzeit vor dem 3. March 1973 werden ausgesperrt!)

                if ( $_SESSION['core_session_reload'] !== $js_timestamp_identifier ) {

                    $_SESSION['core_session_reload'] = $js_timestamp_identifier;

                    $_SESSION['core_session_datastorage_between_reloads'] = (array) array();                // Datenspeicher Zurücksetzen!

                    $_SESSION['core_session_tagname'] = (string) $array_object['LLsTag'];

                    // Hier könnte man eine weitere function aufrufen, die immer einmalig zwischen den Reloads ausgeführt wird!
                }
            }
        }


        // Nur wenn Session 'core_session_reload' korrekt gesetzt wurde. D.h. nicht (string) '0' ist, dann Weitermachen. Dies ist nur der Fall wenn die 'swapi.js.php' korrekt aufgerufen wurde!
        if ( ctype_digit( $_SESSION['core_session_reload'] ) && strlen( $_SESSION['core_session_reload'] ) > 8 ) {

            return (bool) true;
        }

        return (bool) false;
    }


?>