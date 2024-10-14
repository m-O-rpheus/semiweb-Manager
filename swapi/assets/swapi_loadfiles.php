<?php


    function swapi_loadfiles( string $dir_parent ): void {      // $dir_parent includes DIRECTORY_SEPARATOR at end

        if ( is_dir( $dir_parent ) ) {                                  // only Directory

            if ( file_exists( $dir_parent ) ) {                         // only Directory exists

                foreach ( scandir( $dir_parent ) as $dir_child ) {
    
                    if ( is_dir( $dir_parent . $dir_child ) ) {         // only Directory
    
                        $file_path = ( $dir_parent . $dir_child . DIRECTORY_SEPARATOR . 'swapi_init.php' );  // $dir_parent includes DIRECTORY_SEPARATOR at end
    
                        if ( file_exists( $file_path ) ) {              // only Directory exists
        
                            require_once ( $file_path );
                        }
                    }
                }
            }
        }
    }


?>