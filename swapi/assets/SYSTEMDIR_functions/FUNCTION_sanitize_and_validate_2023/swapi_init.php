<?php


    // VALIDATE - base64chars - a-zA-Z0-9+/=
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_validate_base64chars( string $str ): bool {

        return (bool) preg_match( '/^[a-zA-Z0-9\+\/\=]+$/', $str );
    }



    // VALIDATE - sid (Session ID) - a-zA-Z0-9-,
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_validate_sid( string $str ): bool {

        return (bool) preg_match( '/^[a-zA-Z0-9\-,]+$/', $str );
    }



    // VALIDATE & SANITIZE - minimalist (String und Filenames) - a-zA-Z0-9-._
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_validate_minimalist( string $str ): bool {

        return (bool) preg_match( '/^[a-zA-Z0-9\-\._]+$/', $str );
    }

    function swapi_sanitize_minimalist( string $str ): string {

        return (string) preg_replace( '/[^a-zA-Z0-9\-\._]/', '', $str );
    }



    // SANITIZE - azlower - a-z-
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_sanitize_azlower( string $str ): string {

        return (string) preg_replace( '/[^a-z\-]/', '', strtolower( $str ) );
    }



    // VALIDATE - email
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_validate_email( string $str ): bool {

        return (bool) ( filter_var( $str, FILTER_VALIDATE_EMAIL ) && strlen ( $str ) <= 254 );
    }



    // VALIDATE & SANITIZE - url
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_validate_url( string $str ): bool {

        return (bool) filter_var( $str, FILTER_VALIDATE_URL );
    }

    function swapi_sanitize_url( string $str ): string {
        
        return (string) filter_var( $str, FILTER_SANITIZE_URL );
    }



    // GENERATE & VALIDATE - uniquestr - a-zA-Z0-9!#$%()[]{}*+,-:;=?@_~
    // Erzeugt einen kryptographisch sicheren Zufallsstring mit vielen Sonderzeichen. Die länge beträgt mindestens 4 Zeichen. Es wird eine gleichmäßige Verteilung aller Zeichen aus den Gruppen garantiert.
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_generate_uniquestr( int $length ): string {

        if ( $length < 4 ) {

            $length = 4;
        }

        $groups = ['abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '1234567890', '!#$%()[]{}*+,-:;=?@_~'];  // Bestimmte Sonderzeichen werden hier nicht verwendet da diese in HTML etc. eine besondere Bedeutung haben.

        $result = '';

        $forloops = ceil( $length / count($groups) );

        for ($i = 0; $i < $forloops; $i++) {

            foreach ($groups as $group) {  // Entnehme von jeder Gruppe ein zufälliges Zeichen die Schleife läuft somit 4x.

                $result .= $group[random_int(0, strlen($group) - 1)];
            }
        }

        $result = str_shuffle( substr($result, 0, $length) );

        return (string) $result;
    }

    function swapi_validate_uniquestr( string $str ): bool {

        return (bool) ( preg_match( '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\!\#\$%\(\)\[\]\{\}\*\+,\-\:;\=\?@_~]).+$/', $str ) && strlen ( $str ) >= 4 );
    }



    // GENERATE & VALIDATE - uniquehash - a-f0-9
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_generate_uniquehash(): string {

        return (string) hash( 'sha3-224', random_bytes(99) . openssl_random_pseudo_bytes(101) . microtime() );
    }

    function swapi_validate_uniquehash( string $str ): bool {

        return (bool) ( preg_match( '/^[a-f0-9]+$/', $str ) && strlen ( $str ) === 56 );
    }


?>