<?php

    // Datei überarbeitet Q2 2022!


    // [MySQL_TABLE_honeycomb] - Hole ein Array welches alle Naming enthält, von der MySQL Tabelle slugtable
    // Liefert bei Erfolg: Ein Array aller MySQLnaming
    // Liefert bei Fehler: Ein Leeres Array
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_honeycomb_get_all_naming( string $MySQLslugtable ): array {

        $return = (array) array();
            
        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            // ------
            $MySQLslugtable   = (string) $conn->real_escape_string("HONEY_".swapi_sanitize_minimalist($MySQLslugtable));
            // ------

            if( $conn->query( "SHOW TABLES LIKE '" . $MySQLslugtable . "'" )->num_rows === 1 ) {  // if query is SELECT set this for PHP8.1

                $row = $conn->query( "SELECT MySQLnaming FROM " . $MySQLslugtable );
            
                if ( $row !== false && $row->num_rows > 0 ) {  // num_rows if query is SELECT
        
                    while( $result = $row->fetch_assoc() ) {

                        if ( 
                            is_array( $result )
                            &&
                            array_key_exists( "MySQLnaming", $result ) && is_string( $result["MySQLnaming"] ) && !empty( $result["MySQLnaming"] )
                        ) {

                            $return[] = (string) $result["MySQLnaming"];
                        }
                    }
                }
            }
        }

        $conn->close();

        return (array) $return;
    }





    // [MySQL_TABLE_honeycomb] - Hinzufügen einer neuen Zeile oder Updaten einer vorhandenen Zeile. MySQLcompilation muss ein PHP Array sein!
    // Liefert bei Erfolg: true
    // Liefert bei Fehler: false
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_honeycomb_create_table_and_insert_or_update_row( string $MySQLslugtable, string $MySQLnaming, array $MySQLcompilation ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            // ------
            $MySQLslugtable   = (string) $conn->real_escape_string("HONEY_".swapi_sanitize_minimalist($MySQLslugtable));
            $MySQLnaming      = (string) $conn->real_escape_string(swapi_sanitize_minimalist($MySQLnaming));
            $MySQLcompilation = (string) $conn->real_escape_string(base64_encode(mj_aesGCM_encrypt_v5($MySQLcompilation,GLOBAL_CORE_MASTERKEY_HONEYCOMBMYSQL,true)));
            // ------

            $sql = trim("

                CREATE TABLE IF NOT EXISTS " . $MySQLslugtable . " (
                    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    disable BOOLEAN DEFAULT false,
                    MySQLnaming VARCHAR(254) NOT NULL PRIMARY KEY,
                    MySQLcompilation LONGTEXT NULL,
                    MySQLextrastorage LONGTEXT DEFAULT ''
                );

                INSERT INTO " . $MySQLslugtable . " (created, MySQLnaming, MySQLcompilation)
                VALUES (CURRENT_TIMESTAMP(), '" . $MySQLnaming . "', '" . $MySQLcompilation . "')
                ON DUPLICATE KEY UPDATE MySQLnaming = '" . $MySQLnaming . "', MySQLcompilation = '" . $MySQLcompilation . "'
                
            ");

            if ( $conn->multi_query($sql) === true ) {

                $return = (bool) true;
            }
        }

        $conn->close();
        
        return (bool) $return;
    }





    // [MySQL_TABLE_honeycomb] - Gibt das gespeicherter PHP Array von "MySQLnaming" zurück!
    // Liefert bei Erfolg: Ein Array MySQLcompilation
    // Liefert bei Fehler: Ein Leeres Array
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_honeycomb_get_row_from_naming( string $MySQLslugtable, string $MySQLnaming ): array {

        $return = (array) array();

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            // ------
            $MySQLslugtable   = (string) $conn->real_escape_string("HONEY_".swapi_sanitize_minimalist($MySQLslugtable));
            $MySQLnaming      = (string) $conn->real_escape_string(swapi_sanitize_minimalist($MySQLnaming));
            // ------

            if( $conn->query( "SHOW TABLES LIKE '" . $MySQLslugtable . "'" )->num_rows === 1 ) {  // if query is SELECT set this for PHP8.1

                $row = $conn->query( "SELECT MySQLcompilation FROM " . $MySQLslugtable . " WHERE MySQLnaming = '" . $MySQLnaming . "'" );
                
                if ( $row !== false && $row->num_rows > 0 ) {  // num_rows if query is SELECT

                    while( $result = $row->fetch_assoc() ) {

                        if ( 
                            is_array( $result )
                            &&
                            array_key_exists( "MySQLcompilation", $result ) && is_string( $result["MySQLcompilation"] ) && !empty( $result["MySQLcompilation"] )
                        ) {

                            $MySQLcompilation = mj_aesGCM_decrypt_v5(strval(base64_decode($result["MySQLcompilation"],true)),GLOBAL_CORE_MASTERKEY_HONEYCOMBMYSQL);

                            if ( is_array( $MySQLcompilation ) && !empty( $MySQLcompilation ) ) {

                                $return = (array) $MySQLcompilation;

                                break;  // break only in while if sql has WHERE once clause
                            }
                        }
                    }
                }
            }
        }

        $conn->close();

        return (array) $return;
    }





    // [MySQL_TABLE_honeycomb] - Löschen einer Zeile aus der MySQL
    // Liefert bei Erfolg: true
    // Liefert bei Fehler: false
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_honeycomb_delete_row_with_naming( string $MySQLslugtable, string $MySQLnaming ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            // ------
            $MySQLslugtable   = (string) $conn->real_escape_string("HONEY_".swapi_sanitize_minimalist($MySQLslugtable));
            $MySQLnaming      = (string) $conn->real_escape_string(swapi_sanitize_minimalist($MySQLnaming));
            // ------

            $sql = "DELETE FROM " . $MySQLslugtable . " WHERE MySQLnaming = '" . $MySQLnaming . "'";
        
            if ( $conn->query($sql) === true ) {

                $return = (bool) true;
            }
        }

        $conn->close();
        
        return (bool) $return;
    }


?>