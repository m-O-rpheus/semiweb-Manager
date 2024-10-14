<?php


    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiForms' && str_starts_with( $passthrough['READONLY_formnaming'], '_SYSTEM_' ) && $passthrough['READONLY_formnaming'] !== '_SYSTEM_logout' ) {

            $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_input_form_branding'], 'title' => 'a part of semiweb ecosystem', 'style' => ['--size:120px;']] ) . '></div>';

            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'swapi_add_branding_before_input_form');

            $passthrough['return'] .= (string) $html;
        }

        return (array) $passthrough;

    }, 10100);


?>