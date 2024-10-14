<?php

/* CORS_ObjDetail */
/* ========================================================================================================================================================== */

function CORS_OD_ObjDetail( array $array_object ): array {


    /* Nachdem in der Datei 'CORS.php' alles Valide ist, Springe HIER her! Führe weitere Validierungen durch. Speziell ObjectDetail */
    /* ========================================================================================================================================================== */


    if ( is_array( $array_object['ODsObj'] ) ) {

        $result_array = (array) array();

        $result_array['ODcbOBJ'] = (array) register_swapi_hook_point_passthrough_array( 'swapi_objdetail_evaluation', ['objToJS' => array(), 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'objToJS', 'READONLY_objFromJS' => $array_object['ODsObj'], 'READONLY_customer' => $GLOBALS['GLOBAL_customer'] ] )['objToJS'];
    
        if ( !is_array( $result_array['ODcbOBJ'] ) || empty( $result_array['ODcbOBJ']) ) {

            $result_array['ODcbOBJ'] = (array) array();
        }
        
        return (array) $result_array;  // Falls Array Leer == Manipulationsversuch!
    }

    return (array) array();  // Leeres Array == Manipulationsversuch! Erzeugt eine FakeCallback Antwort und gibt diese an JS zurück daraufhin lädt dann die Webseite neu)
}


?>