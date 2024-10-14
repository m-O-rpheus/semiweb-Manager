<?php


    hook_into_swapi_array('swapi_prepare_attribute_array', function( array $passthrough ) {

        if ( array_key_exists( 'class', $passthrough['return'] ) && is_array( $passthrough['return']['class'] ) && in_array( 'swapi_field', $passthrough['return']['class'] ) && array_key_exists( 'type', $passthrough['return'] ) ) {


            $allowed_attribute_per_type = array(

                'text'     => array('type', 'class', 'autocomplete', 'maxlength', 'minlength', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'aria-label'),
                'email'    => array('type', 'class', 'autocomplete', 'maxlength', 'minlength', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'aria-label'),
                'number'   => array('type', 'class', 'max', 'min', 'name', 'placeholder', 'step', 'readonly', 'required', 'aria-label'),
                'range'    => array('type', 'class', 'max', 'min', 'name', 'step', 'required', 'aria-label'),
                'tel'      => array('type', 'class', 'autocomplete', 'maxlength', 'minlength', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'aria-label'),
                'url'      => array('type', 'class', 'autocomplete', 'maxlength', 'minlength', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'aria-label'),
                'search'   => array('type', 'class', 'autocomplete', 'maxlength', 'minlength', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'aria-label'),
                'password' => array('type', 'class', 'autocomplete', 'maxlength', 'minlength', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'aria-label'),
                'textarea' => array('type', 'class', 'maxlength', 'minlength', 'name', 'placeholder', 'readonly', 'aria-label'),
                'radio'    => array('type', 'class', 'name', 'required', 'aria-label'),
                'checkbox' => array('type', 'class', 'name', 'required', 'aria-label'),
                'file'     => array('type', 'class', 'accept', 'name', 'multiple', 'required', 'aria-label'),

            );



            // Delete all invalid attributes from the array
            if ( !array_key_exists( $passthrough['return']['type'], $allowed_attribute_per_type ) ) {

                $passthrough['return']['type'] = 'text';
            }

            foreach ( array_keys( $passthrough['return'] ) as $attribute_name ) {

                if ( substr( $attribute_name, 0, 5 ) !== 'data-' && !in_array( $attribute_name, $allowed_attribute_per_type[$passthrough['return']['type']] ) ) {

                    unset( $passthrough['return'][$attribute_name] );
                }
            }
            


            // Remove type='textarea'
            if ( array_key_exists( 'type', $passthrough['return'] ) && $passthrough['return']['type'] === 'textarea' ) {

                unset( $passthrough['return']['type'] );
            }
            
            
        }

        return (array) $passthrough;

    }, 1);


?>