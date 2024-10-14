<?php


/* Zeigt einen Cookie Hinweis, und Schreibt bei Click die Aktuelle Session ID in den LocalStorage - Cross-Domain fähig

        MODULE_browserstorage_form();

/* ========================================================================================================================================================== */

    function MODULE_browserstorage_form(): string {

        $html  = (string) '<form' . swapi_prepare_attribute( ['class' => ['swapi_cookie_form']] ) . '>';
            $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_cookie_set', 'swapi_active_layer_parent'], 'data-swapi-ev' => 'add'] ) . '>' . MODULE_activelayer() . 'Cookie setzen</a>';
            $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_cookie_set', 'swapi_active_layer_parent'], 'data-swapi-ev' => 'del'] ) . '>' . MODULE_activelayer() . 'Vorhandenes Cookie entfernen</a>';
        $html .= (string) '</form>';


        $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_browserstorage_form');

        return (string) $html;
    }

    
?>