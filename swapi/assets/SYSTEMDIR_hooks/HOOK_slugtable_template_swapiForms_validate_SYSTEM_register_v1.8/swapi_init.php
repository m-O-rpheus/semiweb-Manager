<?php


    hook_into_swapi_array('swapi_validate_form_fields', function( array $passthrough ) {

        if ( $passthrough['READONLY_form_name'] === '_SYSTEM_register' ) {

            $email = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_register_usr']  ?? '';
            $pwd1  = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_register_pwd1'] ?? '';
            $pwd2  = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_register_pwd2'] ?? '';


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., Prüfe ob beide Passwörter Identisch sind!
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {

                if ( $pwd1 != $pwd2 ) {

                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_register_pwd1'] = 'Bitte geben Sie zwei mal das Identische Passwort ein!';
                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_register_pwd2'] = 'Bitte geben Sie zwei mal das Identische Passwort ein!';
                }
            }


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., Prüfe ob die E-Mail Adresse und das Passwort unterschiedlich sind (beide dürfen nicht gleich sein!)
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {

                if ( $email == $pwd1 ) {

                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_register_pwd1'] = 'Das Passwort darf nicht ihre E-Mail Adresse sein!';
                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_register_pwd2'] = 'Das Passwort darf nicht ihre E-Mail Adresse sein!';
                }
            }


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., führe eine Aktion aus!
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {
        
                if ( php_session_is_logged_in() === false ) {   // Ausgeloggt



                    // OPTIONAL: Entweder, Lese den 'register_activation_status' des Users aus der Datenbank aus. User hat seine Registrierung bereits bestätigt ( 'register_activation_status' == true )
                    if ( isset( MySQL_TABLE_users_get_user_data( $email )['register_activation_status']['yes'] ) === true ) {                       

                        // ------ Sende Info Mail das man schon Registriert ist und man sich doch bitte Einloggen soll.
                        swapi_mail( 'SWAPI Registrieren', 'Jemand oder Sie selbst haben versucht, sich in unserem System zu Registrieren.' . swapi_mail_TEMPLATE_br(2) . 'Ihre E-Mail Adresse ist jedoch bereits Registriert. Klicken Sie bitte den folgenden Link um sich anzumelden:' . swapi_mail_TEMPLATE_br(2) . swapi_mail_TEMPLATE_button( 'Anmelden', $GLOBALS['GLOBAL_self_uri'] . '/nochmachen.iframe.php' ), $email );  
                    }

                    // OPTIONAL: Oder, User hat seine Registrierung noch nicht bestätigt ( 'register_activation_status' == false )
                    else {                                                                 

                        // ------ Pseudo Code Generieren - Sende per Mail, und Trage in die Datenbank ein!
                        $code = swapi_generate_uniquestr(63);  // Wichtig: Darf nicht länger als 63 zeichen sein wegen MySQL Begrenzung!

                        // ------ Noch kein users Eintrag mit der E-Mail Adresse in der Datenbank vorhanden, lege an. Oder der User steht bereits in der Datenbank,
                        // ------ hat aber seinen Account noch immer nicht bestätigt und hat erneut versucht sich zu Registrieren, Überschreibe. ( 'register_activation_status' == false )
                        // ------ Per if Prüfe ob die Datenbank aktion erfolgreich war!
                        if ( MySQL_TABLE_users_create_table_and_insert_or_update_user_row( $email, $pwd1 ) ) {

                            // ------ Pseudo E-Mail Code in der Datenbank speichern
                            if ( MySQL_TABLE_users_update_user_column_value( $email, 'email_code', base64_encode( $code ) ) ) {

                                // ------ Sende Bestätigungs Mail ( Erst Registrierung ) oder ( Erneute Registrierung )         
                                swapi_mail( 'SWAPI Registrieren', 'Vielen Dank für Ihre Registrierung.' . swapi_mail_TEMPLATE_br(2) . 'Um Ihre Registrierung abzuschließen, klicken Sie bitte den folgenden Link:' . swapi_mail_TEMPLATE_br(2) . swapi_mail_TEMPLATE_button( 'Registrierung abschließen', $GLOBALS['GLOBAL_self_uri'] . '/swapi.mailverification.php?p=' . rawurlencode( base64_encode( mj_aesGCM_encrypt_v5( ['type' => '_SYSTEM_register', 'mail' => $email, 'code' => $code ], GLOBAL_CORE_MASTERKEY_MAILLINK ) ) ) ), $email );
                            }
                        }
                    }


                
                }
            }
        }

        return (array) $passthrough;

    }, 1);


?>