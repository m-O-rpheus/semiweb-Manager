<?php


    /*
        Add extra class to .swapi_scroll
        --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    */
    hook_into_swapi_array('swapi_prepare_attribute_array', function( array $passthrough ) {

        if ( array_key_exists( 'data-swapi-scroll-page', $passthrough['return'] ) && array_key_exists( 'class', $passthrough['return'] ) && in_array( 'swapi_scroll', $passthrough['return']['class'] ) ) {

            if ( $passthrough['return']['data-swapi-scroll-page'] === 'WALLPAPER_AND_TASKBAR' ) {

                $passthrough['return']['class'][] = 'paNel_WAT';
            }
            else if ( $passthrough['return']['data-swapi-scroll-page'] === 'MAINMENU' ) {

                $passthrough['return']['class'][] = 'paNel_M';
            }
        }

        return (array) $passthrough;

    }, 1);


?>