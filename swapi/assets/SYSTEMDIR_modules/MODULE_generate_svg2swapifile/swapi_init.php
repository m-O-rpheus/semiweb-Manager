<?php


    function MODULE_generate_svg2swapifile(): string {

        $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_generate_svg2swapifile']] ) . '>';

            $html .= MODULE_field_default(array( ['type' => 'file', 'label' => 'Select icon (.svg)', 'accept' => '.svg'], ['type' => 'text', 'label' => 'CSS Selector', 'value' => '.swapi', 'data-field' => 'selector'], ['type' => 'text', 'label' => 'Custom Properties Naming', 'value' => 'swapi', 'data-field' => 'naming'] ));

        $html .= '</div>';

        $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_generate_svg2swapifile');

        return (string) $html;
    }

    
?>