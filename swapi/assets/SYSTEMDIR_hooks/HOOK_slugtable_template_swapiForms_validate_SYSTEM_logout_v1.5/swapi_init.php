<?php


    hook_into_swapi_array('swapi_validate_form_fields', function( array $passthrough ) {

        if ( $passthrough['READONLY_form_name'] === '_SYSTEM_logout' ) {

            
            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., führe eine Aktion aus!
            if ( array_key_exists('swapi_form__SYSTEM_logout_logout', $passthrough['READONLY_all_available_form_fields_with_values'] ) && empty( $passthrough['form_fields_with_error_msg_list'] ) ) {

                if ( isset( MySQL_TABLE_users_get_user_data( $_SESSION['core_session_user'] )['register_activation_status']['yes'] ) === true ) {   // Eingeloggt + Account Aktiviert


                    php_session_logout();


                }
            }
        }

        return (array) $passthrough;

    }, 1);


?>