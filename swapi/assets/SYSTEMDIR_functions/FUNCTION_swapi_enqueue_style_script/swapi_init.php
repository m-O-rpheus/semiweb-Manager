<?php


    // swapi_enqueue_style
    function swapi_enqueue_style( array $patharr, string $tagattribute = '' ): string {

        if ( swapi_parent_function_exists( 'CORS_LL_LazyLoad' ) ) {

            $string = '';

            foreach ( $patharr as $path ) {
    
                $path = rtrim( $path, DIRECTORY_SEPARATOR );
    
                if ( file_exists( $path ) ) {
    
                    $ext = pathinfo( $path, PATHINFO_EXTENSION );
    
                    if ( empty( $ext ) ) {                      // Directory
        
                        foreach ( scandir( $path ) as $file ) {
            
                            if ( (string) pathinfo( $file, PATHINFO_EXTENSION ) === 'css' ) {
                    
                                if ( file_exists( $path . DIRECTORY_SEPARATOR . $file ) ) {
        
                                    $string .= file_get_contents( $path . DIRECTORY_SEPARATOR . $file );
                                }
                            }
                        }
                    }
                    else if ( (string) $ext === 'css' ) {       // File
    
                        $string .= file_get_contents( $path );
                    }
                }
            }
    
            $tagattribute = ( !empty( $tagattribute ) ) ? ' ' . trim( htmlspecialchars( $tagattribute, ENT_NOQUOTES ) ) : '';
    
            return (string) '<style' . $tagattribute . '>' . $string . '</style>';
        }
        else {

            return (string) '';
        }
    }



    // swapi_enqueue_script
    function swapi_enqueue_script( array $patharr, string $tagattribute = '' ): string {

        if ( swapi_parent_function_exists( 'CORS_LL_LazyLoad' ) ) {

            $string = '';

            foreach ( $patharr as $path ) {
    
                $path = rtrim( $path, DIRECTORY_SEPARATOR );
    
                if ( file_exists( $path ) ) {
    
                    $ext = pathinfo( $path, PATHINFO_EXTENSION );
    
                    if ( empty( $ext ) ) {                      // Directory
        
                        foreach ( scandir( $path ) as $file ) {
            
                            if ( (string) pathinfo( $file, PATHINFO_EXTENSION ) === 'js' ) {
                    
                                if ( file_exists( $path . DIRECTORY_SEPARATOR . $file ) ) {
        
                                    $string .= file_get_contents( $path . DIRECTORY_SEPARATOR . $file );
                                }
                            }
                        }
                    }
                    else if ( (string) $ext === 'js' ) {        // File
    
                        $string .= file_get_contents( $path );
                    }
                }
            }
    
            $tagattribute = ( !empty( $tagattribute ) ) ? ' ' . trim( htmlspecialchars( $tagattribute, ENT_NOQUOTES ) ) : '';
    
            return (string) '<script' . $tagattribute . '>' . $string . '</script>';
        }
        else {

            return (string) '';
        }
    }


?>