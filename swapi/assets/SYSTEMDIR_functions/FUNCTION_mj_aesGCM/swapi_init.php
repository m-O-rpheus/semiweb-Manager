<?php


/* MJ AES GCM Encrypt und Decrypt v5 ( Binary String + CompressionStream ) */
/* ========================================================================================================================================================== */


    // Erzeugt aus einem Array einen Verschlüsselten Blob. Dieser kann wahlweise mit oder ohne GZIP Komprimierung erfolgen!
    function mj_aesGCM_encrypt_v5( array $mjv_array, string $mjv_password, bool $gzip = false ): string {
        
        $plaintextstr = json_encode( $mjv_array );

        if ( $plaintextstr === false || json_last_error() !== JSON_ERROR_NONE ) {

            MySQL_TABLE_errorlog_save( 'mj_aesGCM_encrypt_v5', 'ERROR: json_encode Error', '' );
        }

        if ( $gzip === true ) {                     // Gzip CompressionStream Support?

            $plaintextstr = gzencode( $plaintextstr, 9, FORCE_GZIP );
        }

        $cipher        = 'aes-256-gcm';
        $ivBin         = openssl_random_pseudo_bytes( openssl_cipher_iv_length( $cipher ) );
        $tag           = '';                        // will be filled by openssl_encrypt

        $mjv_cipherblob = openssl_encrypt( $plaintextstr, $cipher, hash( 'sha256', $mjv_password, true ), OPENSSL_RAW_DATA, $ivBin, $tag, '', 16 );

        return (string) (bin2hex($ivBin).$mjv_cipherblob.$tag);
    }



    

    // Erzeugt aus einem Verschlüsselten Blob ein Array. Kann sowohl EINFACHE als auch GZIP Komprimierte Blobs Entschlüsseln.
    function mj_aesGCM_decrypt_v5( string $mjv_cipherblob, string $mjv_password ): array {

        $mjv_array     = array();
        $cipher        = 'aes-256-gcm';
        $ivBin         = hex2bin( substr( $mjv_cipherblob, 0, 24 ) );
        $mjv_cipherblob = substr( $mjv_cipherblob, 24 );

        $plaintextstr  = strval( openssl_decrypt( substr( $mjv_cipherblob, 0, -16 ), $cipher, hash( 'sha256', $mjv_password, true ), OPENSSL_RAW_DATA, $ivBin, substr( $mjv_cipherblob, -16 ) ) );

        if ( !empty( $plaintextstr ) ) {

            $gzip = @gzdecode( $plaintextstr );     // Gzip CompressionStream Support?

            if ( $gzip !== false ) {                                                        
    
                $plaintextstr = $gzip;
            }
    
            $jsonarr = json_decode( $plaintextstr, true );
    
            if ( $jsonarr !== null && json_last_error() === JSON_ERROR_NONE && !empty( $jsonarr ) && is_array( $jsonarr ) ) {
    
                $mjv_array = $jsonarr;
            }
        }

        return (array) $mjv_array;
    }


?>