<?php


    hook_into_swapi_array('swapi_validate_form_fields', function( array $passthrough ) {

        if ( $passthrough['READONLY_form_name'] === '_SYSTEM_pwdchange' ) {

            $pwd1 = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_pwdchange_pwd1'] ?? '';
            $pwd2 = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_pwdchange_pwd2'] ?? '';
            $pwd3 = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_pwdchange_pwd3'] ?? '';


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., Prüfe ob zu der E-Mail das richtige Passwort eingegeben wurde!
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {

                if ( MySQL_TABLE_users_email_and_pwd_is_ok( $_SESSION['core_session_user'], $pwd1 ) !== true ) {

                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_pwdchange_pwd1'] = 'Bitte geben Sie das richtige Passwort ein!';
                }
            }


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., Prüfe ob beide Passwörter Identisch sind!
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {

                if ( $pwd2 != $pwd3 ) {

                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_pwdchange_pwd2'] = 'Bitte geben Sie zwei mal das Identische Passwort ein!';
                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_pwdchange_pwd3'] = 'Bitte geben Sie zwei mal das Identische Passwort ein!';
                }
            }


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., Prüfe ob die E-Mail Adresse und das Passwort unterschiedlich sind (beide dürfen nicht gleich sein!)
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {

                if ( $_SESSION['core_session_user'] == $pwd2 ) {

                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_pwdchange_pwd2'] = 'Das Passwort darf nicht ihre E-Mail Adresse sein!';
                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_pwdchange_pwd3'] = 'Das Passwort darf nicht ihre E-Mail Adresse sein!';
                }
            }


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., führe eine Aktion aus!
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {
        
                if ( php_session_is_logged_in() === true && isset( MySQL_TABLE_users_get_user_data( $_SESSION['core_session_user'] )['register_activation_status']['yes'] ) === true ) {   // Eingeloggt + Account Aktiviert
                




                    // ------ Neues Passwort in die Datenbank speichern.
                    if ( MySQL_TABLE_users_update_user_column_value( $_SESSION['core_session_user'], 'pwd', hash( 'sha3-512', $pwd2 ) ) ) {

                        // Passwort ändern Valide
                    }
                    else {

                        // Fehler beim Eintragen des neuen Passwortes in die Datenbank
                    }



                    
                
                }
            }        
        }

        return (array) $passthrough;

    }, 1);


?>