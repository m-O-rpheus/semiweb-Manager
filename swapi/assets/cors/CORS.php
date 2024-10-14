<?php


/* Bei jeder CORS Anfrage wird dieser Bereich ausgeführt!
   Führt allgemeine Validierungsprüfungen aus und Entschlüsselt den Datensatz der von der swapi.js.php kommt

/* ========================================================================================================================================================== */


    // Includes
    $ROOTDIR = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR;
    require_once $ROOTDIR . 'swapi_config.php';
    require_once $ROOTDIR . 'swapi_loadfiles.php';
    swapi_loadfiles( $ROOTDIR . GLOBAL_CORE_SYSTEMDIR_functions . DIRECTORY_SEPARATOR );



    

    // Header für Binary Datenübertragung
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: inline; filename="swapi.bin"');

    // Header für noCache
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: on, 01 Jan 1970 00:00:00 GMT');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');  // HSTS - Automatically redirect to HTTPS

    // Header für CORS benötigt
    header('Access-Control-Expose-Headers: Content-Disposition');
    header('Access-Control-Allow-Methods: POST');    
    header('Access-Control-Max-Age: 0');





    // Fake Callback auf 'true' Initialisieren - Erklärung siehe unten.
    $fake_callback = true;


    $origin = $_SERVER['HTTP_ORIGIN'];

    if ( is_array( $GLOBALS['GLOBAL_allow_origins'] ) && in_array( $origin, $GLOBALS['GLOBAL_allow_origins'] ) ) {


        // Header für CORS Origin benötigt
        header('Access-Control-Allow-Origin: ' . $origin);       // beschränke auf bestimmte URL



        if (
            is_array( $_POST )  && array_key_exists( 's', $_POST )  && is_string( $_POST['s'] )
            &&
            is_array( $_FILES ) && array_key_exists( 'b', $_FILES ) && $_FILES['b']['name'] === 'swapi.bin' && strpos( $_FILES['b']['type'], 'application/octet-stream' ) !== false            
        ) {


            // Wiederaufnehmen der Session sowie prüfen ob dies Erfolgreich war!
            if ( swapi_start_or_continue_session( $_POST['s'] ) === true ) {


                // Hole den Binären Datensatz
                $mjv_cipherblob = file_get_contents( $_FILES['b']['tmp_name'] );


                // Jede CORS Anfrage ist durch die übermittelten Datensätze einzigartig. Sollte aus irgendeinem Grund die selbe Anfrage mehrmals kommen, Abbruch!
                if ( swapi_session_CORS_request_once( $mjv_cipherblob ) === true ) {


                    // Entschlüsselte den Binären Datensatz sowie prüfen ob dies Erfolgreich war!
                    $data_json_decode = mj_aesGCM_decrypt_v5( $mjv_cipherblob, $_SESSION['core_session_aeskey'] );

                    if (
                        is_array( $data_json_decode )
                        &&
                        array_key_exists( 's', $data_json_decode )  && is_string( $data_json_decode['s'] )  && $data_json_decode['s'] == $_SESSION['core_session_id_encrypted']
                        &&                    
                        array_key_exists( 'c', $data_json_decode )  && is_string( $data_json_decode['c'] )  && is_array( $GLOBALS['GLOBAL_customers'] ) && in_array( $data_json_decode['c'], $GLOBALS['GLOBAL_customers'] )
                        &&
                        array_key_exists( 't', $data_json_decode )  && is_string( $data_json_decode['t'] )  && ctype_digit( $data_json_decode['t'] )       // Doppelt identische CORS Bugfix
                        &&
                        array_key_exists( 'a', $data_json_decode )  && is_array( $data_json_decode['a'] )   && !empty( $data_json_decode['a'] )
                        &&
                        array_key_exists( 'gz', $data_json_decode ) && is_bool( $data_json_decode['gz'] )
                    ) {


                        $GLOBALS['GLOBAL_customer'] = trim( trim( swapi_sanitize_url( $data_json_decode['c'] ) ), '/' );  // Diese Seite wurde im <script> Tag unter dem Parameter ?page= angegeben. Stelle diese GLOBAL bereit!

                        
                        $paths = swapi_paths();


                        // Include CORS_ files
                        require_once $paths['PATH_dir_cors_root'] . 'CORS_FA_FormAction.php';
                        require_once $paths['PATH_dir_cors_root'] . 'CORS_LL_LazyLoad.php';
                        require_once $paths['PATH_dir_cors_root'] . 'CORS_OD_ObjDetail.php';

                        
                        // Wichtig: Reihenfolge beachten!
                        swapi_loadfiles( $paths['PATH_dir_modules_root'] );                    // --1-- Include all 'modules'
                        swapi_loadfiles( $paths['PATH_dir_hooks_root'] );                      // --2-- Include all 'system hooks'
                        swapi_loadfiles( $paths['PATH_dir_customers_hooks_and_functions'] );   // --3-- Include all 'customer hooks and functions'


                        $arr = (array) array('cbARR' => (array) array());   // Leeres Array == Manipulationsversuch! Erzeugt eine FakeCallback Antwort und gibt diese an JS zurück daraufhin lädt dann die Webseite neu)


                        foreach ( $data_json_decode['a'] as $array_object ) {

                            // Initialstart I.O.?
                            if ( is_array( $array_object ) && swapi_session_CORS_initialstart_io( $array_object ) ) {

                                // Reihenfolge von JS übernommen! Zuerst 'ODsObj' dann 'FAsForm' sowie am Ende 'LLsPage'!!!!

                                if ( array_key_exists( 'ODsObj', $array_object ) ) {

                                    $arr['cbARR'][] = (array) CORS_OD_ObjDetail( (array) $array_object );       // Aktion - CORS_OD_ObjDetail!
                                }
                                else if ( array_key_exists( 'FAsForm', $array_object ) ) {
                    
                                    $arr['cbARR'][] = (array) CORS_FA_FormAction( (array) $array_object );      // Aktion - CORS_FA_FormAction!
                                }
                                else if ( array_key_exists( 'LLsPage', $array_object ) ) {

                                    if ( empty( $array_object['LLsPage'] ) ) {                                  // Sollte LLsPage leer sein, setze stattdessen die Startseite!

                                        $array_object['LLsPage'] = $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'];
                                    }

                                    $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) $array_object );        // Aktion - CORS_LL_LazyLoad!
                                }
                            }
                            else {
    
                                MySQL_TABLE_errorlog_save( 'CORS.php', '#8.. Session [core_session_reload] ungültig!', '' );
                                break;
                            }
                        }


                        if ( !empty( $arr['cbARR'] ) ) {

                            if ( count( $data_json_decode['a'] ) === count( $arr['cbARR'] ) ) {

                                foreach ( $arr['cbARR'] as $key => $cbARR ) {

                                    if ( is_array( $cbARR ) ) {


                                        // Modifiziere Rückgabearray: Ist eine Kernfunktion, betrifft Speziell das Formular und Formularelemente!
                                        //                            Führe diesen Abschnitt nur aus wenn das Formular Valide ist!
                                        // -----------------------------------------------------------------------------------------------------------------------------------------
                                        if (
                                            array_key_exists( 'FAcbForm', $cbARR )     && is_string( $cbARR['FAcbForm'] )
                                            &&
                                            array_key_exists( 'FAcbAllValid', $cbARR ) && is_bool( $cbARR['FAcbAllValid'] ) && $cbARR['FAcbAllValid'] === true
                                        ) {
    

                                            // Abschnitt #A: Betrifft Speziell die Formulare '_SYSTEM_login', '_SYSTEM_logout', '_SYSTEM_register' und '_SYSTEM_lostpwd'. Sende die HTML Seite die im Anschluss geöffnet wird, gleich mit der Formular Antwort mit!
                                            // -----------------------------------------------------------------------------------------------------------------------------------------
                                            if ( strpos( $cbARR['FAcbForm'], '_SYSTEM_login' ) !== false ) {
    
                                                // Lösche HTML Speicher sowohl auf dem Server...
                                                if ( array_key_exists( 'html_hash', $_SESSION['core_session_datastorage_between_reloads'] ) ) {
                                                    $_SESSION['core_session_datastorage_between_reloads']['html_hash'] = array();
                                                }

                                                // ... als auch Lösche HTML Speicher auf dem Client
                                                $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) array( 'LLsPage' => $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_dashboard'], 'LLsAndOpen' => true ) ) + array( 'LLcbRenew' => true );
                                            }
    
                                            else if ( strpos( $cbARR['FAcbForm'], '_SYSTEM_logout' ) !== false ) {
    
                                                // Lösche HTML Speicher sowohl auf dem Server...
                                                if ( array_key_exists( 'html_hash', $_SESSION['core_session_datastorage_between_reloads'] ) ) {
                                                    $_SESSION['core_session_datastorage_between_reloads']['html_hash'] = array();
                                                }

                                                // ... als auch Lösche HTML Speicher auf dem Client
                                                $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) array( 'LLsPage' => $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], 'LLsAndOpen' => true ) ) + array( 'LLcbRenew' => true );
                                            }
    
                                            else if ( strpos( $cbARR['FAcbForm'], '_SYSTEM_register' ) !== false ) {
    
                                                $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) array( 'LLsPage' => $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_registersuccess'], 'LLsAndOpen' => true ) );
                                            }
    
                                            else if ( strpos( $cbARR['FAcbForm'], '_SYSTEM_lostpwd' ) !== false ) {
    
                                                $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) array( 'LLsPage' => $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_lostpwdsuccess'], 'LLsAndOpen' => true ) );
                                            }


                                            // Abschnitt #B: Betrifft Speziell das Eingabefeld JSON. Sofern über den Hook ein gefülltes Array angegeben wird, sende die Antwort an das JS Event 'swapi_objdetail_callback'!
                                            // -----------------------------------------------------------------------------------------------------------------------------------------
                                            $swapi_input_json = (array) register_swapi_hook_point_passthrough_array( 'swapi_input_json_objdetail_callback', ['objToJS' => array(), 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'objToJS', 'READONLY_customer' => $GLOBALS['GLOBAL_customer'], 'READONLY_helper_message' => 'objToJS array construction example (the object must be bound to one or more fields): array( "swapi_input_json_data-swapi-id-1" => ["callback1" => "c1", "callback2" => "c2", "callback3" => "c3"], "swapi_input_json_data-swapi-id-2" => ["callback4" => "c4", "callback5" => "c5", "callback6" => "c6"] )' ] )['objToJS'];

                                            if ( !empty( $swapi_input_json ) ) {

                                                $correct_form = true;
    
                                                foreach ( $swapi_input_json as $data_swapi_id => $callbackarray ) {
    
                                                    if ( !array_key_exists( $data_swapi_id, $cbARR['FAcbResult'] ) ) {
    
                                                        $correct_form = false;
                                                        break;
                                                    }
                                                }
    
                                                if ( $correct_form === true ) {
    
                                                    $arr['cbARR'][] = (array) array( 'ODcbOBJ' => array( 'swapi_form' => $cbARR['FAcbForm'] ) + $swapi_input_json );
                                                }
                                            }

                                            break;
                                        }



                                        // Modifiziere Rückgabearray: Ist eine Kernfunktion, wird bei erstem Seitenaufruf via JS func_script_start getriggert
                                        //                            Die Reihenfolge der PHP Anweisungen ist hier Wichtig!
                                        // -----------------------------------------------------------------------------------------------------------------------------------------
                                        else if (
                                            array_key_exists( 'LLcbInit', $cbARR ) && is_int( $cbARR['LLcbInit'] )
                                            &&
                                            array_key_exists( 'LLcbPage', $cbARR ) && is_string( $cbARR['LLcbPage'] )   // Enthält 404 oder die Startseite!
                                        ) {

                                            // Append LLsAndOpen to the section to be called (.swapi_section)
                                            $arr['cbARR'][$key] += array( 'LLcbAndOpen' => true );

                                            // Append and NOT LLsAndOpen depending on! So wird die bei einer gefundenen Seiten Abfrage immer die 404 Seite mitgesendet. Quasi für den Fall der Fälle!
                                            if ( $cbARR['LLcbPage'] === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_404error'] ) {

                                                $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) array( 'LLsPage' => $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], 'LLsInit' => $cbARR['LLcbInit'] ) );
                                            }
                                            else {

                                                $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) array( 'LLsPage' => $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_404error'], 'LLsInit' => $cbARR['LLcbInit'] ) );
                                            }

                                            // Append SWAPI root page and LLsAndOpen (.swapi_root)
                                            $arr['cbARR'][] = (array) CORS_LL_LazyLoad( (array) array( 'LLsPage' => GLOBAL_CORE_SWAPIROOT, 'LLsAndOpen' => true, 'LLsInit' => $cbARR['LLcbInit'] ) );

                                            // Flip array so .swapi_root comes first!
                                            $arr['cbARR'] = array_reverse( $arr['cbARR'] );


                                            // cbARR Enthält ab hier drei Seiten nach folgendem Schema:
                                            // - a) SWAPI_root, + IF PAGE NOT 404 -> b) PAGE,           -> c) 404 Error Page
                                            // - a) SWAPI_root, + IF PAGE 404     -> b) 404 Error Page, -> c) STARTSEITE
                                            
                                            // WICHTIG: JavaScript addiert alle 'LLcbInit' zusammen. Nach aktuellem Stand 30.03.2022 müssen auf JS Seite immer 300 herauskommen

                                            break;
                                        }



                                        // Modifiziere Rückgabearray: Ist eine Kernfunktion, für Preload also die Vorgeladenen HTML Seiten. Um zu überprüfen ob eine bestimmte Seite bereits vorhanden ist erzeuge einen HASH von LLcbHtml.
                                        //                            Wenn der HASH bereits im Speicher vorhanden ist, bedeutet dies, dass die Seite bereits auf der Clientseite vorliegt und es nicht erforderlich ist, sie erneut herunterzuladen.
                                        //                            In diesem Fall kann die Seite aus dem Callback gelöscht werden, um den Callback zu optimieren. Das passiert nur wenn keinerlei Änderung im HTML erfolgt ist!
                                        //                            Es muss sichergestellt werden, das dann auf Clientseite die entsprechende Seite aus den Speicher geladen wird.
                                        // -----------------------------------------------------------------------------------------------------------------------------------------
                                        else if (
                                            array_key_exists( 'LLcbPage', $cbARR ) && is_string( $cbARR['LLcbPage'] )
                                            &&
                                            array_key_exists( 'LLcbHtml', $cbARR ) && is_string( $cbARR['LLcbHtml'] )
                                        ) {

                                            if ( !array_key_exists( 'html_hash', $_SESSION['core_session_datastorage_between_reloads'] ) ) {
                                                $_SESSION['core_session_datastorage_between_reloads']['html_hash'] = array();
                                            }

                                            // Erzeuge Hash
                                            $hash = sha1( $cbARR['LLcbHtml'] ); // Erzeuge einen HASH von LLcbHtml, um zu überprüfen, ob der Seiteninhalt geändert wurde. LLcbHtml ist heir bereits Minifiziert.

                                            // 'LLcbUnload' ist nicht vorhanden und der Hash identisch. Dann lösche die Seite aus dem Array!
                                            if (
                                                !array_key_exists( 'LLcbUnload', $cbARR )
                                                &&
                                                array_key_exists( $cbARR['LLcbPage'], $_SESSION['core_session_datastorage_between_reloads']['html_hash'] )
                                                &&
                                                $_SESSION['core_session_datastorage_between_reloads']['html_hash'][$cbARR['LLcbPage']] === $hash
                                            ) {

                                                unset( $arr['cbARR'][$key] );
                                            }

                                            // Ansonsten Speichere den HASH in der Session!
                                            else {

                                                // 'LLcbUnload' löschen. Da die Info am Client nicht notwendig ist!
                                                if ( array_key_exists( 'LLcbUnload', $arr['cbARR'][$key] ) ) {

                                                    unset( $arr['cbARR'][$key]['LLcbUnload'] );
                                                }

                                                $_SESSION['core_session_datastorage_between_reloads']['html_hash'][$cbARR['LLcbPage']] = $hash;
                                            }
                                        }
                                    }
                                }



                                $arr['cbSTORAGE'] = $_SESSION['core_session_aeskey'];

                                $arr['cbARR'] = array_values( $arr['cbARR'] );                                                      // Array Keys neu Numerieren

                                $fake_callback = false;                                                                             // Fake Callback auf 'false' setzen - Erklärunkg siehe unten..

                                echo mj_aesGCM_encrypt_v5( $arr, $_SESSION['core_session_aeskey'], $data_json_decode['gz'] );       // Rückgabe der CORS Anfrage an JavaScript   

                            }
                            else {

                                MySQL_TABLE_errorlog_save( 'CORS.php', '#7.. Ungültige Array längen!', '' );
                            }
                        }
                        else {

                            MySQL_TABLE_errorlog_save( 'CORS.php', '#6.. "href" Seite nicht gefunden oder Subfile Validierungsfehler!', '' );
                        }
                    }
                    else {

                        MySQL_TABLE_errorlog_save( 'CORS.php', '#5.. (Manipulation erkannt) Probleme mit dem JSON Datensatz!', '' );
                    }
                }
                else {

                    MySQL_TABLE_errorlog_save( 'CORS.php', '#4.. (Manipulation erkannt) Doppelt identische CORS Anfrage!', '' );
                }
            }
            else {

                MySQL_TABLE_errorlog_save( 'CORS.php', '#3.. (Manipulation erkannt) Session ungültig!', '' );
            }  
        }
        else {

            MySQL_TABLE_errorlog_save( 'CORS.php', '#2.. (Manipulation erkannt) Request Parameter ungültig!', '' );
        }
    }
    else {

        MySQL_TABLE_errorlog_save( 'CORS.php', '#1.. (Manipulation erkannt) Origin ungültig!', '' );
    }





    /*  Fake Callback:

        Sofern die CORS Anfrage durch einen Validierungsfehler, 404 Fehler oder sonstigen Fehler nicht korrekt ausgeführt wird gebe einen 'Fake Callback' zurück.
        Dies hat den entscheidenden Vorteil, das bei einem Hacking Angriff der Fehler nicht mehr Reproduziert werden kann!
        JavaScript Löscht daraufhin das BrowserStorage Objekt 'swapiSession', und Lädt die Seite Neu!

        Der 'Fake Callback' ist einfach nur ein zufällig generierter Quatsch von 50 - 10000 Zeichen mit einem unbekannten Zufälligen Passwort
    */
    /* ========================================================================================================================================================== */

    if ( $fake_callback ) {

        echo mj_aesGCM_encrypt_v5( [ bin2hex(random_bytes(rand(50, 10000))) ], swapi_generate_uniquestr(16) );     // FAKE Callback der CORS Anfrage an JavaScript   
    }


?>