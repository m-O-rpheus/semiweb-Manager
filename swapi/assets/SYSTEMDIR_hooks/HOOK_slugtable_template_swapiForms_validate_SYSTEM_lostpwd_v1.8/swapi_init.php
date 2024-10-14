<?php


    hook_into_swapi_array('swapi_validate_form_fields', function( array $passthrough ) {

        if ( $passthrough['READONLY_form_name'] === '_SYSTEM_lostpwd' ) {

            $email = $passthrough['READONLY_all_available_form_fields_with_values']['swapi_form__SYSTEM_lostpwd_mail'] ?? '';


            // ERWEITERT: Liegt bisher keine Fehlermeldung vor..., führe eine Aktion aus!
            if ( empty( $passthrough['form_fields_with_error_msg_list'] ) ) {

                if ( MySQL_TABLE_users_email_exists_in_users_table( $email ) === true && php_session_is_logged_in() === false ) {   // E-Mail ist bereits in der Datenbank + Ausgeloggt



                    // ------ Pseudo Code Generieren - Sende per Mail, und Trage in die Datenbank ein!
                    $code = swapi_generate_uniquestr(63);  // Wichtig: Darf nicht länger als 63 zeichen sein wegen MySQL Begrenzung!

                    // ------ Pseudo E-Mail Code in der Datenbank speichern
                    if ( MySQL_TABLE_users_update_user_column_value( $email, 'email_code', base64_encode( $code ) ) ) {

                        // ------ Neues Passwort erstellen
                        $newpwd = swapi_generate_uniquestr(16);  // Passwort länge 16 Zeichen

                        // ------ Neues Passwort in die Temporäre MySQL Spalte speichern.
                        if ( MySQL_TABLE_users_update_user_column_value( $email, 'lostpwd_temp', hash( 'sha3-512', $newpwd ) ) ) {

                            // --- Sende Mail zum Passwort Reset
                            swapi_mail( 'SWAPI Passwort Zurücksetzen', 'Ihr Temporäres Passwort wurde erstellt und lautet <br><br>' . htmlentities( $newpwd, ENT_QUOTES, 'UTF-8' ) . '<br><br>Bitte bestätigen Sie das neue Passwort indem Sie den folgenden Link klicken.' . swapi_mail_TEMPLATE_br(2) . swapi_mail_TEMPLATE_button( 'Temporäres Passwort bestätigen', $GLOBALS['GLOBAL_self_uri'] . '/swapi.mailverification.php?p=' . rawurlencode( base64_encode( mj_aesGCM_encrypt_v5( ['type' => '_SYSTEM_lostpwd', 'mail' => $email, 'code' => $code ], GLOBAL_CORE_MASTERKEY_MAILLINK ) ) ) ) . swapi_mail_TEMPLATE_br(2) . 'Sollten Sie kein neues Passwort angefordert haben, so können Sie diese E-Mail Ignorieren.', $email );
                        }
                    }


                    
                }
            }
        }

        return (array) $passthrough;

    }, 1);


?>