<?php

/* CORS_FormAction */
/* ========================================================================================================================================================== */

function CORS_FA_FormAction( array $array_object ): array {


    /* Nachdem in der Datei 'CORS.php' alles Valide ist, Springe HIER her! Führe weitere Validierungen durch. Speziell Formular */
    /* ========================================================================================================================================================== */


    if (
        is_array( $array_object )
        &&
        array_key_exists( 'FAsForm', $array_object )   && is_string( $array_object['FAsForm'] ) && !empty( $array_object['FAsForm'] ) && swapi_validate_minimalist( $array_object['FAsForm'] )
        &&
        array_key_exists( 'FAsFields', $array_object ) && is_array( $array_object['FAsFields'] )
    ) {


        // Lese die Festlegung der Formularfelder aus der Datenbank! - Anhand des Formular Names welcher von JS übertragen wurde!
        $in_db = (array) MODULE_honeycomb_buzz( 'swapiForms', $array_object['FAsForm'] );

        if ( is_array( $in_db ) && !empty( $in_db ) ) {  // only true if 'MODULE_honeycomb_buzz' is valide, min 1x input field inside formular required!


            $js_fields_array             = array();
            $js_clean_fields_value_array = array();


            // 1. Füge zum neuen Array alle Eingabefelder hinzu, die von JavaScript übertragen wurden!
            if ( !empty( $array_object['FAsFields'] ) ) {

                foreach ( $array_object['FAsFields'] as $jsobjektkey => $jsobjektvalue ) {

                    if ( is_string( $jsobjektkey ) && is_string( $jsobjektvalue ) ) {

                        $js_fields_array[$jsobjektkey] = $jsobjektkey;


                        // Schreibe die Formularfeld Werte in ein neues Array, das nur verwendet wird. Wenn die Vergleiche aus 1. und 2. Valide sind.
                        // Das hat den vorteil, das eventuelle Fake Eingaben nicht enthalten sind!
                        $js_clean_fields_value_array[$jsobjektkey] = $jsobjektvalue;  
                    }
                }
            }


            // 2. Füge zum neuen Array allen Eingabefelder hinzu, die zu dem Formular in der Datenbank gespeichert sind.
            $db_fields_array = array();

            foreach ( $in_db as $field_attr_arr ) {

                if ( is_array( $field_attr_arr ) && array_key_exists( '__APPEND__id',  $field_attr_arr ) ) {  // PHP8
    
                    $db_fields_array[$field_attr_arr['__APPEND__id']] = $field_attr_arr['__APPEND__id']; 
                }
            }


            // 1. und 2. Vergleiche beide Arrays ob diese Exakt identisch sind!
            // Mache nur weiter, wenn diese Exakt identisch sind. Also die Eingabefelder die von JavaScript übermittelt wurden, mit denen die in der Datenbank vorhanden sind, übereinstimmen!

            // ==  TRUE if $a and $b have the same key/value pairs.
            // === TRUE if $a and $b have the same key/value pairs in the same order and of the same types.


            asort($js_fields_array);    // Passe die Sortierung vorab an, wegen den Formularfeld Gruppen!
            asort($db_fields_array);    // Passe die Sortierung vorab an, wegen den Formularfeld Gruppen!


            if ( $js_fields_array === $db_fields_array ) {
        


                /* Ab hier erfolgt die Detailierte Prüfung welche den eingetragenen Formularwert mit den vergebenen Attributen in der Datenbank gegenprüft.
                    D.h. wurde in ein Pflichtfeld auch wirklich ein Wert Eingetragen? Handelt es sich im Feld type='email' tatsächlich um eine gültige E-Mail Adresse? usw...
                    Erzeuge ab hier eine Fehlernachricht, die an das Frontend zurückgegeben wird.

                    Folgende Attribute werden mit Überprüft:

                    TYPE: text ------- ( maxlength,minlength,pattern,required )             ALLES i.O.
                    TYPE: tel -------- ( maxlength,minlength,pattern,required )             ALLES i.O.
                    TYPE: search ----- ( maxlength,minlength,pattern,required )             ALLES i.O.
                    TYPE: password --- ( maxlength,minlength,pattern,required )             ALLES i.O.
                    TYPE: email ------ ( maxlength,minlength,pattern,required, + value )    ALLES i.O.
                    TYPE: url -------- ( maxlength,minlength,pattern,required, + value )    ALLES i.O.

                    TYPE: radio ------ ( required )                                         ALLES i.O.
                    TYPE: checkbox --- ( required )                                         ALLES i.O.

                    TYPE: number ----- ( max,min,STEP NOCH MACHEN,required, + value )       N.i.O.
                    TYPE: range ------ ( max,min,STEP NOCH MACHEN,required, + value )       N.i.O.
                    TYPE: textarea --- ( maxlength,minlength )                              ALLES i.O.

                    TYPE: file ------- ( accept,multiple,required )                         ALLES i.O.

                /* ========================================================================================================================================================== */

                $valid_invalid_auflistung  = array();            // Nur wenn das array() keine Werte Enthält also empty dann ist alles Valide!!!



                // Erzeuge einen Hook Einstiegspunkt um die Fehler Texte zu ändern!
                // ------------------------------------------------------------------------------------------------
                $form_error_msg_array = array(
                    'general_error'              => 'Allgemeiner Formularfehler',
                    'required_textline'          => 'Dieses Feld ist ein Pflichtfeld. Bitte gebe etwas ein!',
                    'required_selection'         => 'Dieses Feld ist ein Pflichtfeld. Bitte selektiere es um fortzufahren!',
                    'required_number'            => 'Dieses Feld ist ein Pflichtfeld. Bitte gib eine Zahl ein!',
                    'required_file'              => 'Dieses Feld ist ein Pflichtfeld. Bitte wähle eine Datei aus!',
                    'invalid_textline_minlength' => 'Bitte geben Sie mindestens <{minlength}> Zeichen ein!',
                    'invalid_textline_maxlength' => 'Bitte geben Sie maximal <{maxlength}> Zeichen ein! Sonderzeichen wie Beispielsweise Ä Ö Ü benötigen mehr Platz',
                    'invalid_pattern'            => 'Bitte tätigen Sie eine Eingabe, die folgendem Pattern <{pattern}> entspricht!',
                    'invalid_email'              => 'Bitte geben Sie eine gültige E-Mail Adresse ein! Deine eingetragene E-Mail: <{email}>',
                    'invalid_url'                => 'Bitte geben Sie eine gültige Webseite/URL ein! Deine eingetragene URL: <{url}>',
                    'invalid_number_min'         => 'Bitte geben Sie mindestens eine Zahl von <{min}> ein!',
                    'invalid_number_max'         => 'Bitte geben Sie maximal eine Zahl von <{max}> ein!',
                    'invalid_number_step'        => 'Bitte geben Sie eine Zahl ein, die durch <{step}> teilbar ist!',
                    'invalid_file_multiple'      => 'Bitte wähle nur eine Datei aus!',
                    'invalid_file_accept'        => 'Nicht erlaubtes Dateiformat, bitte wähle eine andere Datei aus!',
                    'invalid_json_string'        => 'Eingabefeld JSON: JSON String konnte nicht Decodiert werden!',
                    'invalid_json_base64'        => 'Eingabefeld JSON: Base64 konnte nicht Decodiert werden. Bitte vergewissern Sie sich das der JSON String Base64 kodiert ist!',
                );

                /* hook */ $form_error_msg_array = (array) register_swapi_hook_point_passthrough_array( 'swapi_default_validation_error_messages', ['form_error_msg_array' => $form_error_msg_array, 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'form_error_msg_array', 'READONLY_available_placeholders' => ['<{minlength}>', '<{maxlength}>', '<{pattern}>', '<{email}>', '<{url}>', '<{min}>', '<{max}>'] ] )['form_error_msg_array'];
                // ------------------------------------------------------------------------------------------------

                

                $field_attr_arr = array();  // Setze auf Leer, falls es sich von oben vererben sollte.

                foreach ( $in_db as $field_attr_arr ) {

                    if ( is_array( $field_attr_arr ) && array_key_exists( '__APPEND__id',  $field_attr_arr ) ) {  // PHP8
                    
                        if ( is_array( $js_clean_fields_value_array ) && array_key_exists( $field_attr_arr['__APPEND__id'], $js_clean_fields_value_array ) ) {  // PHP8

                            $value = (string) strval( $js_clean_fields_value_array[$field_attr_arr['__APPEND__id']] );


                            // $value                               =>   Enthält den Wert (value) aus dem jeweiligen Eingabefeld!
                            // $field_attr_arr                      =>   Enthält das komplette Attribute Array zu dem jeweiligen Eingabefeld, wie es in der Datenbank steht.
                            // $field_attr_arr['__APPEND__id']      =>   Enthält die jeweilige Eingabefeld id 'data-swapi-id'
                            // $field_attr_arr['__APPEND__type']    =>   Enthält den jeweiligen Eingabefeld 'type'


                            // FIELD: text ODER tel ODER search ODER password ODER email ODER url --- ( maxlength,minlength,pattern,required )
                            // ------------------------------------------------------------------------------------------
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('text', 'tel', 'search', 'password', 'email', 'url') ) ) {

                                if ( array_key_exists( 'required', $field_attr_arr ) && $value === '' ) {                                                                           // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['required_textline'];
                                }
                                else if ( array_key_exists( 'maxlength', $field_attr_arr ) && strlen( $value ) > intval( $field_attr_arr['maxlength'] ) ) {                         // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{maxlength}>', $field_attr_arr['maxlength'], $form_error_msg_array['invalid_textline_maxlength'] );
                                }
                                else if ( array_key_exists( 'minlength', $field_attr_arr ) && strlen( $value ) < intval( $field_attr_arr['minlength'] ) ) {                         // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{minlength}>', $field_attr_arr['minlength'], $form_error_msg_array['invalid_textline_minlength'] );
                                }
                                else if ( array_key_exists( 'pattern', $field_attr_arr ) && !preg_match('/^' . $field_attr_arr['pattern'] . '$/', $value ) ) {

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{pattern}>', $field_attr_arr['pattern'], $form_error_msg_array['invalid_pattern'] );
                                }                                
                            }
        

                            // FIELD: email --- ( type )
                            // ------------------------------------------------------------------------------------------
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('email') ) ) {

                                if ( $value !== '' && !swapi_validate_email( $value ) ) {                                                                                           // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{email}>', $value, $form_error_msg_array['invalid_email'] );
                                }
                            }


                            // FIELD: url --- ( type )
                            // ------------------------------------------------------------------------------------------
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('url') ) ) {

                                if ( $value !== '' && !swapi_validate_url( $value ) ) {                                                                                             // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{url}>', $value, $form_error_msg_array['invalid_url'] );
                                }
                            }


                            // FIELD: radio ODER checkbox --- ( required )
                            // ------------------------------------------------------------------------------------------
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('radio', 'checkbox') ) ) {

                                if ( $value === 'false' || $value === 'true' ) {

                                    if ( array_key_exists( 'required', $field_attr_arr ) && $value === 'false' ) {                                                                  // GENERIERE FEHLER

                                        $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['required_selection'];
                                    }
                                }
                                else {                                                                                                                                              // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['general_error'];   // [ Manipulation erkannt, zeige Allgemeiner Formularfehler ]
                                }
                            }


                            // FIELD: number ODER range
                            // ------------------------------------------------------------------------------------------
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('number', 'range') ) ) {

                                if ( array_key_exists( 'required', $field_attr_arr ) && $value === '' ) {                                                                           // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['required_number'];
                                }
                                else {

                                    if ( $value !== '' ) {

                                        if ( is_numeric( $value ) ) {

                                            if ( array_key_exists( 'min', $field_attr_arr ) && floatval( $value ) < floatval( $field_attr_arr['min'] ) ) {                          // GENERIERE FEHLER

                                                $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{min}>', $field_attr_arr['min'], $form_error_msg_array['invalid_number_min'] );
                                            }
                                            else if ( array_key_exists( 'max', $field_attr_arr ) && floatval( $value ) > floatval( $field_attr_arr['max'] ) ) {                     // GENERIERE FEHLER

                                                $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{max}>', $field_attr_arr['max'], $form_error_msg_array['invalid_number_max'] );
                                            }                                            
                                            else if ( array_key_exists( 'step', $field_attr_arr ) && fmod( floatval( $value ), floatval( $field_attr_arr['step'] ) ) !== 0.0 ) {    // GENERIERE FEHLER

                                                $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{step}>', $field_attr_arr['step'], $form_error_msg_array['invalid_number_step'] );
                                            }
                                        }
                                        else {

                                            $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['general_error'];   // [ Manipulation erkannt, zeige Allgemeiner Formularfehler ]
                                        }
                                    }
                                }
                            }


                            // FIELD: textarea
                            // ------------------------------------------------------------------------------------------
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('textarea') ) ) {

                                if ( array_key_exists( 'maxlength', $field_attr_arr ) && strlen( $value ) > intval( $field_attr_arr['maxlength'] ) ) {                              // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{maxlength}>', $field_attr_arr['maxlength'], $form_error_msg_array['invalid_textline_maxlength'] );
                                }
                                else if ( array_key_exists( 'minlength', $field_attr_arr ) && strlen( $value ) < intval( $field_attr_arr['minlength'] ) ) {                         // GENERIERE FEHLER

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) str_replace( '<{minlength}>', $field_attr_arr['minlength'], $form_error_msg_array['invalid_textline_minlength'] );
                                }
                            }
        
        
                            // FIELD: file
                            // ------------------------------------------------------------------------------------------ 
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('file') ) ) {

                                if ( ctype_digit( $value ) ) {              // Enthält nur Zeichen 0-9
        
                                    $file_counter = intval( $value );       // in einen integer umwandeln!

                                    if ( array_key_exists( 'required', $field_attr_arr ) && $file_counter < 1 ) {                                                                   // GENERIERE FEHLER

                                        $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['required_file'];
                                    }
                                    else if ( !array_key_exists( 'multiple', $field_attr_arr ) && $file_counter > 1 ) {                                                             // GENERIERE FEHLER

                                        $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['invalid_file_multiple'];
                                    }
                                    else {

                                        // Erklärung: Nur bei dem Eingabefeld 'file' wird in der CORS Anfrage das zusätzliche Array Element 'FAsFiles' mit übermittelt.
                                        // Sofern also mindestens 1x Datei im Eingabefeld gesetzt ist, so MUSS auch das Array Element 'FAsFiles' existieren. Falls es mehrere File Uploads gibt, werden in diesem Array alle Formularfelder zusammengefasst.
                                        // Prüfe hier also auf die Korrekte Syntax dieses Arrays.
                                        if ( $file_counter >= 1 && array_key_exists( 'FAsFiles',  $array_object ) && is_array( $array_object['FAsFiles'] ) && !empty( $array_object['FAsFiles'] ) ) {

                                            $file_new_b64_array   = (array) array();
                                            $file_mime_type_valid = (bool)  true;

                                            foreach ( $array_object['FAsFiles'] as $key => $fileupload_arr ) {

                                                // Filtere nach dem Array 'files' Elementen, die zu diesem Eingabefeld gehören!
                                                if ( is_array($fileupload_arr) && !empty($fileupload_arr) && is_string($fileupload_arr[0]) && $fileupload_arr[0] === $field_attr_arr['__APPEND__id'] ) {

                                                    if ( count($fileupload_arr) === 3 && is_string($fileupload_arr[1]) && !empty($fileupload_arr[1]) && is_string($fileupload_arr[2]) && !empty($fileupload_arr[2]) ) {

                                                        $file_filename  = trim( $fileupload_arr[1] );
                                                        $file_b64file   = trim( $fileupload_arr[2] );
                                                        $file_mime      = trim( str_replace( 'data:', '', strtok( $file_b64file, ';' ) ) );
                                                        $file_extension = trim( pathinfo( $file_filename, PATHINFO_EXTENSION ) );

                                                        unset( $fileupload_arr[1] );
                                                        unset( $fileupload_arr[2] );

                                                        if ( array_key_exists( 'accept', $field_attr_arr ) ) {

                                                            if ( !in_array( $file_mime, explode( ',', $field_attr_arr['accept'] ) ) ) {

                                                                $file_mime_type_valid = (bool) false;  // Ungültiger MIME Type
                                                            }
                                                        }

                                                        $file_new_b64_array[] = (array) array(
                                                            'READONLY_value_filename'   => $file_filename,
                                                            'READONLY_value_extension'  => $file_extension,
                                                            'READONLY_value_mime'       => $file_mime,
                                                            'READONLY_value_b64file'    => $file_b64file
                                                        );
                                                    }

                                                    unset( $array_object['FAsFiles'][$key] );  // Das 'FAsFiles' Element gehört zu diesem Eingabefeld, lösche es jetzt damit die weiteren Zugriffe schneller gehen!
                                                }
                                            }

                                            if ( $file_mime_type_valid === false ) {

                                                $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['invalid_file_accept'];                 // GENERIERE FEHLER
                                            }
                                            else if ( $file_counter === count( $file_new_b64_array ) ) {
                                                
                                                // Validierung erfolgreich!
                                                // Die Prüfung von 'required', 'multiple', 'accept' sowie MIME als auch die Anzahl der Hochgeladenen Dateien, stimmt exakt mit dem übermittelten JavaScript JSON überein

                                                $value = (array) $file_new_b64_array;   // Dateien Rückgabe als Array. Überschreibe string $value durch array $value ( nur Speziell bei Eingabefeld 'file' )

                                            }
                                            else {

                                                $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['general_error'];   // [ Manipulation erkannt, zeige Allgemeiner Formularfehler ]
                                            }
                                        }
                                        else {

                                            $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['general_error'];   // [ Manipulation erkannt, zeige Allgemeiner Formularfehler ]
                                        }
                                    }
                                    // else Ende
                                }
                                else {

                                    $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['general_error'];   // [ Manipulation erkannt, zeige Allgemeiner Formularfehler ]
                                }

                                if ( !is_array( $value ) ) {

                                    $value = (array) array();   // Bei Fehler leere Array Rückgabe. Überschreibe string $value durch array $value ( nur Speziell bei Eingabefeld 'file' )
                                }
                            }


                            // FIELD: json
                            // ------------------------------------------------------------------------------------------
                            if ( in_array( $field_attr_arr['__APPEND__type'], array('json') ) ) {

                                if ( !empty( $value ) ) {

                                    $value = base64_decode( $value, true );

                                    if ( $value !== false ) {

                                        $typejsonarray = (array) json_decode( $value, true );

                                        if ( $typejsonarray !== null && json_last_error() === JSON_ERROR_NONE ) {

                                            if ( !empty( $typejsonarray ) ) {

                                                $value = (array) $typejsonarray;
                                            }
                                        }
                                        else {

                                            $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['invalid_json_string'];
                                        }
                                    }
                                    else {

                                        $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $form_error_msg_array['invalid_json_base64'];
                                    }
                                }

                                if ( !is_array( $value ) ) {

                                    $value = (array) array();   // Bei Fehler leere Array Rückgabe. Überschreibe string $value durch array $value ( nur Speziell bei Eingabefeld 'json' )
                                }
                            }





                            // Erzeuge einen Hook Einstiegspunkt für weitere Formularfeld prüfungen!
                            // ------------------------------------------------------------------------------------------------
                            $error_msg_string = ( ( array_key_exists( $field_attr_arr['__APPEND__id'], $valid_invalid_auflistung ) ) ? $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] : '' ); 

                            /* hook */ $error_msg_string = (string) register_swapi_hook_point_passthrough_array( 'swapi_validate_form_field_loop', ['error_msg_string' => $error_msg_string, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'error_msg_string', 'READONLY_data_swapi_id' => $field_attr_arr['__APPEND__id'], 'READONLY_defined_attribute_in_db' => $field_attr_arr, 'READONLY_type' => $field_attr_arr['__APPEND__type'], 'READONLY_form_name' => $array_object['FAsForm'], 'READONLY_customer' => $GLOBALS['GLOBAL_customer'], 'READONLY_helper_message' => 'the form field is only valid if the error_msg_string is empty and READONLY_value has the type string for all input fields, except for input file or json where it is an array', 'READONLY_value' => $value] )['error_msg_string'];

                            if ( !empty( $error_msg_string ) ) {                                                             // Rückgabe von error_msg_string enthält einen Wert. Entweder weil bei den vorangehenden Prüfungen eine Fehlermeldung aufgetreten ist, oder weil durch den Hook eine Fehlermeldung eingetragen wurde!
                                $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] = (string) $error_msg_string;     // Schreibe oder Aktualisiere Array Element
                            }

                            else if ( array_key_exists( $field_attr_arr['__APPEND__id'], $valid_invalid_auflistung ) )  {    // Rückgabe von error_msg_string enthält KEINEN Wert. Entweder weil bei den vorangehenden Prüfungen KEINE Fehlermeldung aufgetreten ist, oder weil durch den Hook eine LEERE Fehlermeldung eingetragen wurde!
                                unset( $valid_invalid_auflistung[$field_attr_arr['__APPEND__id']] );                         // Lösche Array Element sofern bereits eines vorhanden ist.
                            }
                            // ------------------------------------------------------------------------------------------------

                        }
                    }
                }


                
                // Erzeuge einen Hook Einstiegspunkt für das gesamte Formular!
                // ------------------------------------------------------------------------------------------------
                /* hook */ $valid_invalid_auflistung = (array) register_swapi_hook_point_passthrough_array( 'swapi_validate_form_fields', ['form_fields_with_error_msg_list' => $valid_invalid_auflistung, 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'form_fields_with_error_msg_list', 'READONLY_form_name' => $array_object['FAsForm'], 'READONLY_all_available_form_fields_with_values' => $js_clean_fields_value_array, 'READONLY_customer' => $GLOBALS['GLOBAL_customer'], 'READONLY_helper_message' => 'the form is only valid if the array is empty'] )['form_fields_with_error_msg_list'];
                // ------------------------------------------------------------------------------------------------



                // Erzeuge die Rückgabe nach JavaScript...
                // 1. Enthält das Array $valid_invalid_auflistung Fehlermeldungen zu Eingabefeldern verpacke diese in HTML, setze zudem die Variable $allvalid auf 'false'.
                $allvalid = (bool) true;

                if ( !empty( $valid_invalid_auflistung ) ) {

                    $allvalid = (bool) false;

                    foreach ( $valid_invalid_auflistung as $field_name => $error_msg ) {

                        $valid_invalid_auflistung[$field_name] = array('state' => 'invalid', 'html' => '<em' . swapi_prepare_attribute( ['class' => ['swapi_form_result', 'swapi_state_invalid']] ) . '>' . trim( $error_msg ) . '</em>');   // HTML 'invalid'
                    }
                }

                // 2. Hänge an das Array $valid_invalid_auflistung Eingabefelder ohne Fehlermeldung und dem HTML valid mit an!
                $all_field_names = array_keys( $js_clean_fields_value_array );

                if ( !empty( $all_field_names ) ) {

                    foreach ( $all_field_names as $field_name ) {

                        if ( !array_key_exists( $field_name, $valid_invalid_auflistung ) ) {

                            $valid_invalid_auflistung[$field_name] = array('state' => 'valid', 'html' => '<em' . swapi_prepare_attribute( ['class' => ['swapi_form_result', 'swapi_state_valid']] ) . '></em>');   // HTML 'valid'
                        }
                    }
                }

                // 3. Callback zurück an JavaScript
                $result_array = (array) array();

                $result_array['FAcbForm']     = (string) '.swapi_input_form[name="' . $array_object['FAsForm'] . '"]';
                $result_array['FAcbAllValid'] = (bool)   $allvalid;
                $result_array['FAcbResult']   = (array)  $valid_invalid_auflistung;


                return (array) $result_array;
            }
            else {

                MySQL_TABLE_errorlog_save( 'CORS_FA_FormAction.php', '#3.. Formular Manipulation: Das Eingabefelder Array aus der Datenbank, und das Eingabefelder Array von Javascript sind nicht Identisch!', '' );
            }      
        }
        else {

            MySQL_TABLE_errorlog_save( 'CORS_FA_FormAction.php', '#2.. Formular Manipulation: Die Datenbank mit dem Formularnamen <form name="FALSCHER NAME"> gibt es nicht!', '' );
        }
    }
    else {

        MySQL_TABLE_errorlog_save( 'CORS_FA_FormAction.php', '#1.. Formular Manipulation: "$array_object" enthält keinen Array Key "form" Dieser wird jedoch immer von JS mit gesendet!', '' );
    }

    return (array) array();  // Leeres Array == Manipulationsversuch! Erzeugt eine FakeCallback Antwort und gibt diese an JS zurück daraufhin lädt dann die Webseite neu)
}


?>