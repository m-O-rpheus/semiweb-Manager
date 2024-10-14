<?php


/* Erstellt eine HTML Bild Vorlage. Das Bild muss im Kunden Ordner '/image' Liegen.

        MODULE_image_simple( string src, alt, true|false )

        - string  src:             Der Dateiname des Bildes
        - string  alt:             Alternativer Bild Text
        - bool    true|false:
                  :    :.. Gibt das Bild entwender direkt als src 'Pfad' aus, Vorteil Browser Caching. Nachteil keine mj_aesGCM Verschlüsselung. (*)
                  :....... Gibt das Bild als Base64 aus. Vorteil mj_aesGCM Verschlüsselung. Nachteil kein Caching, muss jedes mal neu geladen werden!


                (*) Wenn das Bild direkt als src 'Pfad' ausgegeben wird. Dann gibt es die Option über srcset, verschiedene Auflösungen zu definieren.
                Die srcset Auflösungen werden dynamisch erzeugt, anhand der verfügbaren Bilder die auf dem Server liegen.

                Der Dateiname des Hauptbildes lautet:
                - mike-kononov-lFv0V3_2H6s-unsplash.jpg

                Die Alternativen Auflösungen werden durch die Ergänzung des Dateinamens mittels '--1600w' gekennzeichnet, 1600 Steht hier für die Breite des Bildes in Pixeln
                - mike-kononov-lFv0V3_2H6s-unsplash--1600w.jpg
                - mike-kononov-lFv0V3_2H6s-unsplash--800w.jpg
                - mike-kononov-lFv0V3_2H6s-unsplash--400w.jpg

/* ========================================================================================================================================================== */

    function MODULE_image_simple( string $src, string $alt = '', bool $base64_encode = false ): string {
        
        $func_responsive = function( string $src, string $src_path, string $src_uri, string $src_extension, string $src_width, array $allowed_extensions ): array {

            $src_filename = (string) pathinfo( $src_path.$src, PATHINFO_FILENAME );

            $set   = array();
            $set[] = (string) ($src_uri.$src . ' ' . $src_width. 'w');

            foreach ( scandir( $src_path ) as $res ) {

                if ( is_string( $res ) && file_exists( $src_path.$res ) && swapi_validate_minimalist( $res ) ) {    // Check if the responsive image exists within the customer folder and if the file name of the image has a valid format!

                    $res_extension = (string) pathinfo( $src_path.$res, PATHINFO_EXTENSION );

                    if ( in_array( $res_extension, $allowed_extensions ) ) {                                        // Check if the responsive image has a valid extension

                        $res_filename  = (string) pathinfo( $src_path.$res, PATHINFO_FILENAME );

                        if ( str_starts_with( $res_filename, $src_filename . '--' ) && $src_extension === $res_extension ) {
                            
                            $set[] = (string) ($src_uri.$res . ' ' . str_replace( $src_filename . '--', '', $res_filename ));
                        }
                    }
                }
            }
        
            return (array) array('srcset' => implode( ',', $set ), 'sizes' => '(max-width:' . $src_width. 'px) 100vw, ' . $src_width. 'px');
        };



        $allowed_extensions = (array) array( 'gif', 'jpg', 'jpeg', 'png', 'svg', 'webp' );

        /* hook */ $allowed_extensions = (array) register_swapi_hook_point_passthrough_array( 'swapi_module_single_image_allowedextensions', ['return' => $allowed_extensions, 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return'] )['return'];



        $src_path = (string) swapi_paths()['PATH_dir_customers_image'];
        
        if ( is_string( $src ) && file_exists( $src_path.$src ) && swapi_validate_minimalist( $src ) ) {            // Check if the source image exists within the customer folder and if the file name of the image has a valid format!

            $src_extension = (string) pathinfo( $src_path.$src, PATHINFO_EXTENSION );

            if ( in_array( $src_extension, $allowed_extensions ) ) {                                                // Check if the source image has a valid extension

                $src_width  = (string) strval( getimagesize( $src_path.$src )[0] );                                 // Determine the exact image -width-  of the source image
                $src_height = (string) strval( getimagesize( $src_path.$src )[1] );                                 // Determine the exact image -height- of the source image                       

                if ( $base64_encode === true ) {                                                                    // The source image should be base64 coded and encrypted?
    
                    $src_uri = (string) 'data:' . mime_content_type( $src_path.$src ) . ';base64,' . base64_encode( file_get_contents( $src_path.$src ) );
                    $res_set = (array)  array();
                }
                else {                                                                                              // Do not base64 encode the source image - Only here are several srcsets transferred!
        
                    $src_uri = (string) swapi_paths_2_url('PATH_dir_customers_image');
                    $res_set = (array)  $func_responsive( (string) $src, (string) $src_path, (string) $src_uri, (string) $src_extension, (string) $src_width, (array) $allowed_extensions );
                    $src_uri = (string) $src_uri.$src;
                }
        
    
                // Note: Due to the loading time, loading lazy should always remain - But this can cause flickering
                $html = (string) '<span' . swapi_prepare_attribute( ['class' => ['swapi_img_container']] ) . '>';
                    $html .= (string) '<picture>';
                        $html .= (string) '<img' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_img'], 'alt' => $alt, 'decoding' => 'async', 'loading' => 'lazy', 'style' => ['width:50vw;', 'height:auto;'], 'src' => $src_uri, 'width' => $src_width, 'height' => $src_height], $res_set ) ) . '>';
                    $html .= (string) '</picture>';
                $html .= (string) '</span>';
    

                /* hook */ $html = (string) register_swapi_hook_point_passthrough_array( 'swapi_module_single_image_container', ['html' => $html, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'html', 'READONLY_alt' => $alt, 'READONLY_src' => $src_uri] )['html'];
    

                $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_image_simple');


                return (string) $html;
            }
            else {

                return (string) swapi_ERRORMSG( '[MODULE_image_simple] The file has not an allowed image extension!' );
            }
        }
        else {

            return (string) swapi_ERRORMSG( '[MODULE_image_simple] The specified image could not be found or has an invalid file name!' );
        }
    }

    
?>