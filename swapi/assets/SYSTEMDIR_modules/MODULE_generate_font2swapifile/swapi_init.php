<?php


    function MODULE_generate_font2swapifile(): string {

        $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_generate_font2swapifile']] ) . '>';

            $html .= MODULE_field_default(array( [ 'type' => 'file', 'label' => 'Select font (.woff2)', 'accept' => '.woff2' ] ));

        $html .= '</div>';

        $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_generate_font2swapifile');

        return (string) $html;
    }

    
?>