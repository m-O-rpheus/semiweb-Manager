<?php


    // [MySQL_TABLE_users] - Erstellt die Benutzer Tabelle falls nicht vorhanden und fügt den Benutzer hinzu.
    // Liefert true|false ob dies erfolgreich war!
    // --- Wird ausschließlich in "swapi_validate_form_fields__SYSTEM_register HOOK" eingesetzt ---
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_users_create_table_and_insert_or_update_user_row( string $email, string $pwd ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            $pwd = hash( 'sha3-512', $pwd );

            $sql = trim("

                CREATE TABLE IF NOT EXISTS users (
                    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    email VARCHAR(254) NOT NULL PRIMARY KEY,
                    pwd VARCHAR(128) NOT NULL,
                    disable BOOLEAN DEFAULT false,
                    role  VARCHAR(254) NOT NULL,
                    email_code VARCHAR(254) NULL,
                    register_activation_status BOOLEAN DEFAULT false,
                    lostpwd_temp VARCHAR(128) NULL,
                    last_email_sent VARCHAR(254) NOT NULL,
                    userinfo LONGTEXT NULL,
                    emailnotification_concat_json LONGTEXT NULL
                );
                
                INSERT INTO users (created, email, pwd, role, email_code, lostpwd_temp, last_email_sent, userinfo, emailnotification_concat_json)
                VALUES (CURRENT_TIMESTAMP(), '" . $conn->real_escape_string($email) . "', '" . $pwd . "', 'user', '', '', '', '" . json_encode( array() ) . "', '')
                ON DUPLICATE KEY UPDATE created = CURRENT_TIMESTAMP(), pwd = '" . $pwd . "', email_code = '" . $conn->real_escape_string($email) . "', lostpwd_temp = '', last_email_sent = ''
                
            ");
    
            if ( $conn->multi_query($sql) === true ) {
    
                $return = (bool) true;
            }
        }

        $conn->close();

        return (bool) $return;
    }





    // [MySQL_TABLE_users] - Prüft ob die E-Mail Adresse des "users" bereits in der Datenbank existiert
    // Liefert true|false ob dies erfolgreich war!
    // --- Wird in "swapi_validate_form_fields__SYSTEM_lostpwd HOOK" eingesetzt ---
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_users_email_exists_in_users_table( string $email ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            if( $conn->query( "SHOW TABLES LIKE 'users'" )->num_rows === 1 ) {  // if query is SELECT set this for PHP8.1

                $row = $conn->query( "SELECT email FROM users WHERE email = '" . $conn->real_escape_string($email) . "'" );
                
                if ( $row !== false && $row->num_rows > 0 ) {  // num_rows if query is SELECT
        
                    $return = (bool) true;
                }
            }
        }

        $conn->close();

        return (bool) $return;
    }





    // [MySQL_TABLE_users] - Prüft ob das Passwort zu der E-Mail Adresse übereinstimmt
    // Liefert true|false ob dies erfolgreich war!
    // --- Wird in "swapi_validate_form_fields__SYSTEM_login HOOK" und "swapi_validate_form_fields__SYSTEM_pwdchange HOOK" eingesetzt ---
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_users_email_and_pwd_is_ok( string $email, string $pwd ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            if( $conn->query( "SHOW TABLES LIKE 'users'" )->num_rows === 1 ) {  // if query is SELECT set this for PHP8.1

                $row = $conn->query( "SELECT pwd FROM users WHERE email = '" . $conn->real_escape_string($email) . "'" );
                
                if ( $row !== false && $row->num_rows > 0 ) {  // num_rows if query is SELECT
        
                    while( $result = $row->fetch_assoc() ) {
        
                        if (
                            is_array( $result )
                            &&
                            array_key_exists( "pwd", $result ) && is_string( $result["pwd"] ) && hash( 'sha3-512', $pwd ) == $result["pwd"]
                        ) {
        
                            $return = (bool) true;
                            break;  // break only in while if sql has WHERE once clause
                        }
                    }
                }
            }
        }

        $conn->close();

        return (bool) $return;
    }





    // [MySQL_TABLE_users] - Gibt die MySQL Felder "created, disable, role, email_code, register_activation_status, lostpwd_temp, userinfo" des Ausgewählen Benutzers als Formatiertes Array Zurück
    // Liefert bei Erfolg ein Array mit den MySQL Spalten
    // Liefert bei MySQL Fehler ein Leeres Array
    // --- Wird in diversen Dateien eingesetzt ---
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_users_get_user_data( string $email ): array {

        $return = (array) array();

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            if( $conn->query( "SHOW TABLES LIKE 'users'" )->num_rows === 1 ) {  // if query is SELECT set this for PHP8.1

                $row = $conn->query( "SELECT created, disable, role, email_code, register_activation_status, lostpwd_temp, userinfo FROM users WHERE email = '" . $conn->real_escape_string($email) . "'" );
            
                if ( $row !== false && $row->num_rows > 0 ) {  // num_rows if query is SELECT
        
                    while( $result = $row->fetch_assoc() ) {
        
                        if ( is_array( $result ) ) {
        
                            if ( array_key_exists( "userinfo", $result ) ) {
        
                                $result["userinfo"] = json_decode( $result["userinfo"], true );
                            }
    
                            if ( array_key_exists( "register_activation_status", $result ) ) {
        
                                $result["register_activation_status"] = ( ( $result["register_activation_status"] == 0 ) ? array( 'no' => 'no' ) : array( 'yes' => 'yes' ) );
                            }
    
                            $return = (array) $result;
                            break;  // break only in while if sql has WHERE once clause
                        }           
                    }
                }
            }
        }

        $conn->close();

        return (array) $return;
    }





    // [MySQL_TABLE_users] - Updated den den Wert einer MySQL Spalte eines Users
    // Liefert true|false ob dies erfolgreich war!
    // --- Wird in diversen Dateien eingesetzt ---
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_users_update_user_column_value( string $email, string $columnname, string $value ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            $sql = trim("

                UPDATE users SET " . $conn->real_escape_string($columnname) . " = '" . $conn->real_escape_string($value) . "' WHERE email = '" . $conn->real_escape_string($email) . "'
                
            ");
        
            if ( $conn->query($sql) === true ) {
        
                $return = (bool) true;
            }
        }

        $conn->close();

        return (bool) $return;
    }





    // [MySQL_TABLE_users] - Hinzufügen eines Wertes in die Spalte "emailnotification_concat_json" des Users. Innerhalb dieser Spalte wird somit ein halber Pseudo JSON Zusammengebaut
    // Liefert true|false ob dies erfolgreich war!
    // --- Wird in "swapi_mail" eingesetzt ---
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_users_append_user_emailnotification( string $email, array $append ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            $sql = trim("

                UPDATE users SET emailnotification_concat_json = CONCAT(emailnotification_concat_json, '" . $conn->real_escape_string('"'.microtime(false).'":'.json_encode($append).',') . "') WHERE email = '" . $conn->real_escape_string($email) . "'
                
            ");
        
            if ( $conn->query($sql) === true ) {
        
                $return = (bool) true;
            }
        }

        $conn->close();

        return (bool) $return;
    }





    // [MySQL_TABLE_users] - Baue aus dem Wert der Spalte "emailnotification_concat_json" einen entgültigen validen JSON Zusammen. Anschließend decodiere diesen und gebe ihn als Array zurück
    // Liefert bei Erfolg ein gefülltes Array der Spalte emailnotification_concat_json.
    // Liefert bei MySQL Fehler ein Leeres Array
    // ----------------------------------------------------------------------------------------------------------------------
    /*function MySQL_TABLE_users_get_user_emailnotification( string $email ): array {

        $return = (array) array();

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            if( $conn->query( "SHOW TABLES LIKE 'users'" )->num_rows === 1 ) {  // if query is SELECT set this for PHP8.1

                $row = $conn->query( "SELECT emailnotification_concat_json FROM users WHERE email = '" . $conn->real_escape_string($email) . "'" );
                
                if ( $row !== false && $row->num_rows > 0 ) {  // num_rows if query is SELECT

                    while( $result = $row->fetch_assoc() ) {

                        if (
                            is_array( $result )
                            &&
                            array_key_exists( "emailnotification_concat_json", $result )
                        ) {

                            $return = (array) json_decode( '{' . trim( $result["emailnotification_concat_json"], ',' ) . '}', true );
                            break;  // break only in while if sql has WHERE once clause
                        }
                    }
                }
            }
        }

        $conn->close();

        return (array) $return;
    }*/


?>