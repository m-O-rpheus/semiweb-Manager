<?php

    
    /*

        Wandelt eine Textarea die über 'MODULE_field_default' erzeugt wurde, in eine Textarea mit automatisch wachsener Höhe um. Dazu wird ein behelfs Element '.swapi_area_autogrow_resize' verwendet.
        Um diesen Hook zu laden, vergebe der Textarea über 'MODULE_field_default' oder über 'swapiForms' hierzu einfach das folgendes data- Attribute:
        
                data-swapi-override-style='area_autogrow'


        Die Eingaben der Textarea werden mit jedem Tastendruck gelesen und in das behelfs Element '.swapi_area_autogrow_resize' geklont. Um in diesen Callback Event Prozess via JavaScript einzugreifen (OPTIONAL),
        vergebe bitte ein weiteres data- Attribute namens:

                data-swapi-js-val-innerhtml-passthrough-func="YOUR_CUSTOM_PASSTHROUGH_FUNCTON_NAME"


        Sowie die JavaScript function mit dem Identischen Namen:

                window.YOUR_CUSTOM_PASSTHROUGH_FUNCTON_NAME = function YOUR_CUSTOM_PASSTHROUGH_FUNCTON_NAME( thissel, value, previousvalue, event, previousevent ) {

                    console.log('PASSTHROUGH');

                    return value;
                };


        Anschließend wird der Callback Prozess immer durch diese function geleitet. Wodurch Manipulationen innerhalb des '.swapi_area_autogrow_resize' Elements ermöglicht werden.
        Außerdem kann der Callback Prozess jederzeit über dispatchEvent getriggert werden:

                swapinode().querySelector(':scope [data-swapi-js-val-innerhtml-passthrough-func="YOUR_CUSTOM_PASSTHROUGH_FUNCTON_NAME"]').dispatchEvent(new CustomEvent('input', { bubbles: true, detail: { 'pass':'data' } }));

    */

    hook_into_swapi_array('swapi_module_swapi_field_default_inputloop', function( array $passthrough ) {

        if (
            array_key_exists( 'type', $passthrough['READONLY_attribute'] ) && $passthrough['READONLY_attribute']['type'] === 'textarea'
            &&
            array_key_exists( 'data-swapi-override-style', $passthrough['READONLY_attribute'] ) && $passthrough['READONLY_attribute']['data-swapi-override-style'] === 'area_autogrow'
        ) {

            $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_area_autogrow']] ) . '>';
                $html .= $passthrough['return'];
                $html .= '<div' . swapi_prepare_attribute( ['class' => ['swapi_area_autogrow_resize'], 'aria-hidden' => 'true'] ) . '></div>';
            $html .= '</div>';


            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'swapi_override_style_area_autogrow');
            $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'swapi_override_style_area_autogrow');


            $passthrough['return'] = $html;
        }

        return (array) $passthrough;

    }, 1 );


?>