<?php

    // Datei überarbeitet Q1 2022!


    // [MySQL_TABLE_errorlog] - Systemfehlermeldungen in die Datenbank schreiben. Erstellt die ErrorLog Tabelle. Hier werden alle Fehlermeldungen des Systems gesammelt abgespeichert
    // Liefert true|false ob dies erfolgreich war!
    // --- Wird in diversen Dateien eingesetzt ---
    // ----------------------------------------------------------------------------------------------------------------------
    function MySQL_TABLE_errorlog_save( string $fehler_durch_datei, string $fehler_meldung, string $fehler_hervorgerufen_durch ): bool {

        $return = (bool) false;

        $conn = new mysqli( GLOBAL_CORE_MYSQLHOST, GLOBAL_CORE_MYSQLUSER, GLOBAL_CORE_MYSQLPASS, GLOBAL_CORE_MYSQLDB );

        if( $conn->connect_errno === 0 ) {

            $conn->set_charset('utf8mb4');

            $sql = trim("

                CREATE TABLE IF NOT EXISTS errorlog (
                    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    fehler_durch_datei VARCHAR(254) NULL,
                    fehler_meldung LONGTEXT NULL,
                    fehler_hervorgerufen_durch LONGTEXT NULL
                );
                
                INSERT INTO errorlog (created, fehler_durch_datei, fehler_meldung, fehler_hervorgerufen_durch)
                VALUES (CURRENT_TIMESTAMP(), '" . $conn->real_escape_string($fehler_durch_datei) . "', '" . $conn->real_escape_string($fehler_meldung) . "', '" . $conn->real_escape_string($fehler_hervorgerufen_durch) . "')
                
            ");
        
            if ( $conn->multi_query($sql) === true ) {
        
                $return = (bool) true;
            }
        }

        $conn->close();

        return (bool) $return;
    }

    
?>