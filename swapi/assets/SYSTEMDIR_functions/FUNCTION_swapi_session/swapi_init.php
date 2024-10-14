<?php


/* Startet eine neue Session (swapi.js.php) oder nimmt eine vorhandene Session wieder auf (CORS.php)

   Kurze Erklärung:
   ----------------

        - Wird die (swapi.js.php) aufgerufen, so wird bei jedem Aufruf IMMER eine 'neue Session' gestartet (const mjv_global_initial_session), selbst wenn bereits schon eine Session existiert.
          Da sich diese existierende SessionID im LocalStorage befindet worauf PHP keinen Zugriff hat, kann diese erst nach dem PageLoad per JavaScript gelesen werden (const mjv_stor = func_get_browserstorage...).
          Ist dort also eine SessionID registiert, so überschreibt JavaScript die 'neue Session' durch die vorhandene aus dem LocalStorage. Und das bei jedem Reload
          oder jeder Anfrage aufs neue.

        - Wird schlussendlich dann die (CORS.php) aufgerufen so erhält diese Anfrage per $_POST immer den aktuellen 'session_id_encrypted' 'neue Session' oder 
          wiederaufgenommene 'Session aus LocalStorage'.


    Erklärung der Kern Variablen:
    -----------------------------

        $_SESSION['core_session_id']            (string) Enthält die aktuelle SessionID

        $_SESSION['core_session_id_encrypted']  (string) ...

        $_SESSION['core_session_starttime']     (string) Enthält einen Timestamp wann diese Session ursprünglich gestartet wurde!

        $_SESSION['core_session_aeskey']        (string) Erzeugt aus der Verwendeten SessionID und einem Salt das Passwort für die AES Verschlüsselung!

        $_SESSION['core_session_temporary']     (bool)   Setzt den Wert bei Aufruf der (swapi.js.php) auf true. Wird bei Aufruf der (CORS.php) auf false geändert.
                                                         Ist im LocalStorage bereits eine SessionID vorhanden. Bleibt der Wert somit immer true (Da diese initiale Session nicht benutzt wird).
                                                         Bei true wird diese Session also nur Temporär für die (swapi.js.php) verwendet und kann somit früher verworfen werden!

        $_SESSION['core_session_loggedin']      (bool)   Enthält den Eingeloggt Status true|false. Wird beim erstmaligen Start der Session immer false sein!

        $_SESSION['core_session_user']          (string) ...

        $_SESSION['core_session_requesthashes'] (array)  ...

        $_SESSION['core_session_reload']        (string) Die Variable wird bei Neu Initialisierung der Session auf (string) '0' gesetzt. Die Anfrage an die CORS.php überschreibt diesen Wert 'EINMALIG', durch einen
                                                         aktuelleren Timestamp der von JavaScript kommt. Der Timestamp hat keinerlei bedeutung sondern dient nur als Identifier.
                                                         Kurz: Die Variable wird nur beim 'betreten' oder beim 'neu laden' der Webseite 'EINMALIG' geändert.
                                                         So kann unterschieden werden ob die Anfrage an die CORS.php 'Neu' oder aus einem 'vorhandenen Viewport' geschehen ist!


    Erklärung der Session Speicher:
    -------------------------------

        $_SESSION['core_session_datastorage_between_reloads']   (array) Erzeugt einen Session Datenspeicher. Die Daten werden bleiben solange erhalten bis die Webseite neu geladen wird (Auch wenn Browserstorage gesetzt ist)


    Input/Output:
    -------------

        Die Funktion 'swapi_start_or_continue_session' erwartet einen 'EmptyString' oder einen 'session_id_encrypted'.

        Die Rückgabe erfolgt dann entsprechend:
        - Bei Parameter 'EmptyString':              Erzeuge eine komplett neue Session und lege einige Session Variablen an:      return (bool) true
        - Bei Parameter 'session_id_encrypted':     Versuche eine Session wieder aufzunehmen. Sofern dies möglich ist:            return (bool) true

        Bei generellen Fehler der Funktion, gebe false zurück:                                                                    return (bool) false


/* ========================================================================================================================================================== */

    function swapi_start_or_continue_session( string $session_id_encrypted = '' ): bool {


        // Settings
        $global_sessionID_length  = (int)    110;                                                                   // Länge der SessionID
        $global_sessionID_expire  = (int)    (3600 * 24);                                                           // Ablaufzeit der Session (1 Tag) nachder der User Automatisch ausgeloggt wird.
                                                                                                                    // :.. Erzeugt dann beim nächsten Aufruf der Seite eine neue Session schreibe auch den LocalStorage neu!


        // PHP Session ini
        ini_set('session.use_cookies', 0);
        ini_set('session.use_only_cookies', 0);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.sid_length', $global_sessionID_length);                        // länge der SessionID
        ini_set('session.sid_bits_per_character', 6);                                   // a-zA-Z0-9-,
        ini_set('session.gc_maxlifetime', $global_sessionID_expire);                    // 1440 = Standardwert - Überschreibe den Standardwert
        ini_set('session.gc_probability', 1);                                           // 1    = Standardwert
        ini_set('session.gc_divisor', 100);                                             // 100  = Standardwert



        // ============ Parameter Leer: Neue Session Starten (swapi.js.php)! ============
        // ==============================================================================

        if ( empty( $session_id_encrypted ) ) {      

            // [Neue Session Starten] - Mache weiter, wenn die Session erfolgreich gestartet wurde...
            if ( session_start() === true ) {     

                // ...Definiere SESSION Variablen
                $_SESSION['core_session_id']                          = (string) session_id();
                $_SESSION['core_session_id_encrypted']                = (string) base64_encode( mj_aesGCM_encrypt_v5( ['sid' => $_SESSION['core_session_id']], GLOBAL_CORE_MASTERKEY_SESSION ) );
                $_SESSION['core_session_starttime']                   = (string) strval( time() );
                $_SESSION['core_session_aeskey']                      = (string) $_SESSION['core_session_id_encrypted'].'.'.swapi_generate_uniquestr(64);  // Zufälliges Passwort erzeugen!
                $_SESSION['core_session_temporary']                   = (bool)   true;
                $_SESSION['core_session_loggedin']                    = (bool)   false;
                $_SESSION['core_session_user']                        = (string) 'ghost';


                // wird in swapi_session_CORS_request_once.php benötigt!
                $_SESSION['core_session_requesthashes']               = (array)  array();


                // wird in swapi_session_CORS_initialstart_io.php benötigt!
                $_SESSION['core_session_reload']                      = (string) '0';
                $_SESSION['core_session_datastorage_between_reloads'] = (array)  array();
                $_SESSION['core_session_tagname']                     = (string) '';

                // Rückgabe
                return (bool) true;
            }
        }


        // ============ Parameter gesetzt: Vorhandene Session wiederaufnehmen (CORS.php)! ============
        // ===========================================================================================

        else {                                          

            // [Vorhandene Session wiederaufnehmen] - Mache weiter, wenn die Verschlüsselte SessionID der Base64 Syntax entspricht...
            if ( swapi_validate_base64chars( $session_id_encrypted ) ) {

                $continue_session_id = mj_aesGCM_decrypt_v5( strval( base64_decode( $session_id_encrypted, true ) ), GLOBAL_CORE_MASTERKEY_SESSION )['sid'];

                // [Vorhandene Session wiederaufnehmen] - Mache weiter, wenn das Entschlüsseln erfolgreich ist, somit der String nicht Leer ist...
                if ( !empty( $continue_session_id ) ) {

                    // [Vorhandene Session wiederaufnehmen] - Mache weiter, wenn die SessionID Validierung erfolgreich ist...
                    if ( swapi_validate_sid( $continue_session_id ) && strlen( $continue_session_id ) === $global_sessionID_length ) {

                        // [Vorhandene Session wiederaufnehmen] - Mache weiter, wenn die SessionID erfolgreich gesetzt wurde...
                        if ( session_id( $continue_session_id ) !== false ) {
                            
                            // [Vorhandene Session wiederaufnehmen] - Mache weiter, wenn die Session erfolgreich gestartet wurde...
                            if ( session_start() === true ) {
                                
                                // [Vorhandene Session wiederaufnehmen] - Mache weiter, wenn die wiederaufgenommene Session der gesetzten SessionID entspricht...
                                if ( $continue_session_id == session_id() ) {

                                    // [Vorhandene Session wiederaufnehmen] - Mache weiter, Prüfe ob alle 'core_session_*' Variablen vorhanden sind!
                                    if (
                                        array_key_exists( 'core_session_id', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_id_encrypted', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_starttime', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_aeskey', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_temporary', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_loggedin', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_user', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_requesthashes', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_reload', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_datastorage_between_reloads', $_SESSION )
                                        &&
                                        array_key_exists( 'core_session_tagname', $_SESSION )
                                    ) {

                                        // ...Überschreibe SESSION Variablen
                                        $_SESSION['core_session_temporary'] = (bool) false;

                                        // Rückgabe
                                        return (bool) true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Rückgabe
        return (bool) false;
    }



    function php_session_is_logged_in(): bool {

        if ( $_SESSION['core_session_loggedin'] === true && $_SESSION['core_session_user'] !== 'ghost' ) {

            return (bool) true;
        }

        return (bool) false;
    }



    function php_session_login( string $user_email ): void {

        if ( php_session_is_logged_in() === false ) {   // Ausgeloggt

            $_SESSION['core_session_loggedin'] = true;
            $_SESSION['core_session_user']     = $user_email;
        }
    }



    function php_session_logout(): void {

        if ( php_session_is_logged_in() === true ) {    // Eingeloggt

            $_SESSION['core_session_loggedin'] = false;
            $_SESSION['core_session_user']     = 'ghost';
        }
    }
    

?>