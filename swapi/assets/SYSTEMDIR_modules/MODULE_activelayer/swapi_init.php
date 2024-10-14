<?php


/* Definiert einen Link 

        MODULE_activelayer();

        Das Modul 'MODULE_activelayer' ist ein Werkzeug für Webentwickler und Designer, das einen optischen Layer definiert, der ausschließlich für die Verbesserung der Usability (Benutzerfreundlichkeit)
        einer Webseite oder Anwendung verantwortlich ist.

        Bei einen Klick auf das entsprechende Element wird ein :active Style angewendet!

        Damit das Modul funktioniert, muss das übergeordnete Element (das sogenannte 'Parent element') die Klasse 'swapi_active_layer_parent' haben.
        Diese Klasse gibt dem Modul die notwendigen CSS Informationen, um den Layer korrekt darzustellen.

/* ========================================================================================================================================================== */

    function MODULE_activelayer(): string {

        $html = (string) '<span' . swapi_prepare_attribute( ['class' => ['swapi_active_layer']] ) . '></span>';

        $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_activelayer');

        return (string) $html;
    }

    
?>