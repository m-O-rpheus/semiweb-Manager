<?php


/* Bestätigung der E-Mails */

/* ========================================================================================================================================================== */


    // Includes
    $ROOTDIR = dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
    require_once $ROOTDIR . 'swapi_config.php';
    require_once $ROOTDIR . 'swapi_loadfiles.php';
    swapi_loadfiles( $ROOTDIR . GLOBAL_CORE_SYSTEMDIR_functions . DIRECTORY_SEPARATOR );





// Ausgabe Reset
$mainhtml = 'Dieser Link ist abgelaufen!';



// Prüfe auf GET Parameter "p"
if ( is_array( $_GET ) && array_key_exists( "p", $_GET ) && is_string( $_GET["p"] ) ) {

    // Prüfe auf gültigen base64
    if ( swapi_validate_base64chars( $_GET["p"] ) ) {

        $mail_link = mj_aesGCM_decrypt_v5( strval( base64_decode( $_GET["p"], true ) ), GLOBAL_CORE_MASTERKEY_MAILLINK );

        if (
            !empty( $mail_link ) && is_array( $mail_link )
            &&
            array_key_exists( "type", $mail_link )   && is_string( $mail_link["type"] )
            &&
            array_key_exists( "mail", $mail_link )   && is_string( $mail_link["mail"] ) && swapi_validate_email( $mail_link["mail"] )
            &&
            array_key_exists( "code", $mail_link )   && is_string( $mail_link["code"] ) && swapi_validate_uniquestr( $mail_link["code"] )
            &&
            MySQL_TABLE_users_email_exists_in_users_table( $mail_link["mail"] ) === true
            &&
            MySQL_TABLE_users_get_user_data( $mail_link["mail"] )["email_code"] == base64_encode( $mail_link["code"] )                        // Der Code im JSON ist mit dem in der Datenbank identisch!
        ) {



            // IST: "_SYSTEM_lostpwd" und PRÜFE: ob die E-Mail Adresse bereits in der Datenbank existiert. Mache nur dann weiter...
            if ( $mail_link["type"] == '_SYSTEM_lostpwd' ) {

    // begin ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


                // --- Hole die MySQL User Zeile
                $user_row = MySQL_TABLE_users_get_user_data( $mail_link["mail"] );


                // --- Prüfe ob das Temporäre Passwort in der Datenbank nicht leer ist!
                if ( !empty( $user_row["lostpwd_temp"] ) ) {


                    // --- Dann nehme das Temporäre Passwort und Überschreibe das Aktuelle Passwort damit
                    if ( MySQL_TABLE_users_update_user_column_value( $mail_link["mail"], "pwd", $user_row["lostpwd_temp"] ) ) {


                        // --- Anschließend lösche das Temporäre Passwort aus der Datenbank!
                        if ( MySQL_TABLE_users_update_user_column_value( $mail_link["mail"], "lostpwd_temp", "" ) ) {

                            $mainhtml = 'Ihr Passwort wurde erfolgreich zurückgesetzt.<br>Bitte kehren Sie zur Webseite zurück, um sich anzumelden!<br>Dieses Fenster schließt sich automatisch (NOCH MACHEN)';
                        }
                    }
                }

                
    // end -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            }


            // IST: "_SYSTEM_register" und PRÜFE: ob die E-Mail Adresse bereits in der Datenbank existiert. Mache nur dann weiter...
            else if ( $mail_link["type"] == '_SYSTEM_register' ) {

    // begin ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


                // --- Lese den "register_activation_status" des Users aus der Datenbank aus. User hat seine Registrierung noch nicht bestätigt ( "register_activation_status" == false )
                if ( isset( MySQL_TABLE_users_get_user_data( $mail_link["mail"] )['register_activation_status']['no'] ) ) {


                    // --- activation_status auf true setzen
                    if ( MySQL_TABLE_users_update_user_column_value( $mail_link["mail"], "register_activation_status", "1" ) ) {

                        $mainhtml = 'Vielen Dank das Sie sich Registriert haben.<br>Bitte kehren Sie zur Webseite zurück, um sich anzumelden!<br><br>Dieses Fenster schließt sich automatisch (NOCH MACHEN)';
                    }
                }


    // end -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            }


            // ... Hier Weitere else if Answeiungen ...



            // Entferne den E-Mail Code. Hier benötigt man keine true false Rückmeldung
            MySQL_TABLE_users_update_user_column_value( $mail_link["mail"], "email_code", "" );
        }
    }
}


?><!DOCTYPE html>
<html lang='de' id='swapi_mailverification'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0'>
        <meta name='apple-mobile-web-app-capable' content='yes'>
        <title>SWAPI</title>
    </head>
    <body>
        <main>
<?php echo $mainhtml; ?>
        </main>
    </body>
</html>