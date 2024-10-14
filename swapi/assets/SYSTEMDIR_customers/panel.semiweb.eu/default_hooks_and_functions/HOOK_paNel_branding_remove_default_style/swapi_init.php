<?php


    /*
        Remove --size:120px; defined in the standard specification
        --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    */
    hook_into_swapi_array('swapi_prepare_attribute_array', function( array $passthrough ) {

        if ( array_key_exists( 'style', $passthrough['return'] ) && array_key_exists( 'class', $passthrough['return'] ) && in_array( 'swapi_input_form_branding', $passthrough['return']['class'] ) ) {

            unset( $passthrough['return']['style'] );
        }

        return (array) $passthrough;

    }, 1);


?>