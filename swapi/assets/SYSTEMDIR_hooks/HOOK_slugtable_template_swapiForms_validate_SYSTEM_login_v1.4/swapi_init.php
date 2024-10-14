<?php


    hook_into_swapi_array('swapi_validate_form_fields', function( array $passthrough ) {

        if ( $passthrough['READONLY_form_name'] === '_SYSTEM_login' ) {

            $email = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_login_usr'] ?? '';
            $pwd   = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_login_pwd'] ?? '';
        
        
            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., Prüfe ob zu der E-Mail das richtige Passwort eingegeben wurde! - Gibt es die E-Mail Adresse nicht, wird ebenfalls der Fehler (bitte gebe das richtige Passwort ein) ausgegeben.
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {
        
                if ( MySQL_TABLE_users_email_and_pwd_is_ok( $email, $pwd ) === false ) {
        
                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_login_pwd'] = 'Bitte geben Sie das richtige Passwort ein!';
                }
            }
        
        
            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., Prüfe ob der Benutzeraccount schon Aktiviert ist.
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {
        
                if ( isset( MySQL_TABLE_users_get_user_data( $email )['register_activation_status']['yes'] ) === false ) {
        
                    $passthrough['form_fields_with_error_msg_list']['swapi_form__SYSTEM_login_pwd'] = 'Ihr Account ist noch nicht Aktiviert. Bitte Überprüfen Sie Ihr E-Mail Postfach und bestätigen Sie die Registrierung!';
                }
            }
        
        
            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., führe eine Aktion aus!
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {


                php_session_login( $email );

                
            }       
        }

        return (array) $passthrough;

    }, 1);


?>