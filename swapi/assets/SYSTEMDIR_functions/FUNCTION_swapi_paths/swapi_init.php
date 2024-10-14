<?php


    // Gibt ein Array an Systempfaden aus!
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_paths(): array {

        static $pathsarr = array();

        if ( empty( $pathsarr ) ) {

            $ROOTDIR = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;

            $PATH_dir_functions_root = $ROOTDIR . GLOBAL_CORE_SYSTEMDIR_functions . DIRECTORY_SEPARATOR;
            $PATH_dir_hooks_root     = $ROOTDIR . GLOBAL_CORE_SYSTEMDIR_hooks . DIRECTORY_SEPARATOR;
            $PATH_dir_modules_root   = $ROOTDIR . GLOBAL_CORE_SYSTEMDIR_modules . DIRECTORY_SEPARATOR;
            $PATH_dir_customers_root = $ROOTDIR . GLOBAL_CORE_SYSTEMDIR_customers . DIRECTORY_SEPARATOR . $GLOBALS['GLOBAL_customer'] . DIRECTORY_SEPARATOR;

            $pathsarr = (array) array(

                'PATH_dir_root'                             => $ROOTDIR,
                'PATH_dir_functions_root'                   => $PATH_dir_functions_root,
                'PATH_dir_hooks_root'                       => $PATH_dir_hooks_root,
                'PATH_dir_modules_root'                     => $PATH_dir_modules_root,
                'PATH_dir_customers_root'                   => $PATH_dir_customers_root,
                'PATH_dir_customers_css'                    => $PATH_dir_customers_root . 'default_css_load_all' . DIRECTORY_SEPARATOR,
                'PATH_dir_customers_hooks_and_functions'    => $PATH_dir_customers_root . 'default_hooks_and_functions' . DIRECTORY_SEPARATOR,
                'PATH_dir_customers_image'                  => $PATH_dir_customers_root . 'default_image' . DIRECTORY_SEPARATOR,
                'PATH_dir_customers_js'                     => $PATH_dir_customers_root . 'default_js_load_all' . DIRECTORY_SEPARATOR,
                'PATH_dir_customers_templates'              => $PATH_dir_customers_root . 'default_templates' . DIRECTORY_SEPARATOR,
                'PATH_dir_cors_root'                        => $ROOTDIR . 'cors' . DIRECTORY_SEPARATOR,
                'PATH_dir_css-js_once_root'                 => $ROOTDIR . 'css-js_once' . DIRECTORY_SEPARATOR,
                'PATH_dir_frameworks_root'                  => $ROOTDIR . 'frameworks' . DIRECTORY_SEPARATOR, 
                
            );
        }

        return (array) $pathsarr;
    }



    // Wandelt einen Pfad der Rückgabe von 'function swapi_paths()' in eine URL um!
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_paths_2_url( string $PATH_ ): string {

        if ( !empty( $PATH_ ) && array_key_exists( $PATH_, swapi_paths() ) ) {

            $return = swapi_paths()[$PATH_];                                                                                          // Path convert to URL.
            $return = str_replace( DIRECTORY_SEPARATOR, '/', $return );                                                               // Replace Directory Seperators to / Slash.
            $return = '/assets' . explode( 'swapi/assets', $return, 2 )[1];                                                           // Clip Server Path - Only get from assets Path.
            $return = $GLOBALS['GLOBAL_self_uri'] . $return;                                                                          // Get Full URL
            $return = preg_replace_callback( '/[^\x20-\x7f]/', function( $match ) { return rawurlencode( $match[0] ); }, $return );   // Nicht-ASCII-Symbole URL Kodieren!

            return (string) $return;
        }

        return (string) '';
    }



    // Wandelt einen Pfad der Rückgabe von 'function swapi_paths()' in einen 'für Mensch lesbaren' Pfad um. Dient für Erklärungen. Es ist kein realer Pfad!
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_paths_2_path_for_user_explanation( string $PATH_ ): string {

        $return = swapi_paths()[$PATH_];                                                                                          // Path convert to URL.
        $return = str_replace( DIRECTORY_SEPARATOR, '/', $return );                                                               // Replace Directory Seperators to / Slash.
        $return = '__ROOT__' . explode( 'swapi/assets', $return, 2 )[1];                                                          // Clip Server Path - Only get from assets Path.

        return (string) $return;
    }


?>