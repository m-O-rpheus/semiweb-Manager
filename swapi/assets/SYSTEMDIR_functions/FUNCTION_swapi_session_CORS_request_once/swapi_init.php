<?php


/* Jede Anfrage an CORS.php läuft durch diese Funktion hindurch.

   Prüfe die übermittelten Daten auf Einmaligkeit wie folgt.:
   - Erstelle daraus einen einzigartigen Hash.
   - Prüfe ob dieser Hash noch NICHT in der Session existiert
   - Dann speichere den Hash hinein

/* ========================================================================================================================================================== */

    function swapi_session_CORS_request_once( string $cors_datensatz ): bool {

        // Hash erstellen
        $hash = hash( 'sha3-512', $cors_datensatz . md5( $cors_datensatz ) );


        // Nur weitermachen wenn der CORS Datensatz einzigartig ist, also NICHT im Array steht.
        if ( !array_key_exists( $hash, $_SESSION['core_session_requesthashes'] ) ) {


            // Hash sowie den aktuellen Timestamp in der Session Variable speichern.
            $_SESSION['core_session_requesthashes'][$hash] = time();


            // Durchlaufe alle Datensätze in der Session Variable und Lösche alle die älter als 1 Minute sind!
            foreach ( $_SESSION['core_session_requesthashes'] as $hash => $timestamp ) {

                if ( ($timestamp + 60) < time() ) {

                    unset( $_SESSION['core_session_requesthashes'][$hash] );
                }
            }


            // Alles erfolgreich!
            return (bool) true;
        }


        // CORS Datensatz ist NICHT einzigartig - Fehler
        return (bool) false;
    }


?>