 

/*
    Using the JavaScript function

        swapi_input_json_insert_obj("JSON_FIELD_NAME(data-swapi-id)", obj);

    a JavaScript object can be written on the fly into the JSON field value (not shows in dom). It is saved base64 encoded in text field.

    This function is not responsible for the return after the submit
*/

    window.swapi_input_json_insert_obj = function swapi_input_json_insert_obj( data_swapi_id, obj ) {

        swapinode().querySelector(':scope .swapi_field[data-swapi-id="' + data_swapi_id + '"]').value = btoa( JSON.stringify( obj ) );
    };

   