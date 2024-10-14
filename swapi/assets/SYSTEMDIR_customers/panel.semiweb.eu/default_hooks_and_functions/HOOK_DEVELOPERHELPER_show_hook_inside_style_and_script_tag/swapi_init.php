<?php



hook_into_swapi_array("swapi_customer_style_after_minify", function( array $passthrough ) {

    if ( !empty( $passthrough['READONLY_tagattribute'] ) ) {

        $passthrough["css"] = '/*

SET HOOK TO REMOVE THIS <STYLE> TAG ( ' . $passthrough['READONLY_tagattribute'] . ' )
--------------------------------------------------------------------------------

hook_into_swapi_array("swapi_customer_style_after_minify", function( array $passthrough ) {

    if ( $passthrough["READONLY_tagattribute"] === "' . $passthrough['READONLY_tagattribute'] . '" ) {

        $passthrough["css"] = "";
    }

    return (array) $passthrough;

}, 1);

*/' . $passthrough["css"];

    }

    return (array) $passthrough;

}, 1);





hook_into_swapi_array("swapi_customer_script_after_minify", function( array $passthrough ) {

    if ( !empty( $passthrough['READONLY_tagattribute'] ) ) {

        $passthrough["javascript"] = '/*

SET HOOK TO REMOVE THIS <SCRIPT> TAG ( ' . $passthrough['READONLY_tagattribute'] . ' )
--------------------------------------------------------------------------------

hook_into_swapi_array("swapi_customer_script_after_minify", function( array $passthrough ) {

    if ( $passthrough["READONLY_tagattribute"] === "' . $passthrough['READONLY_tagattribute'] . '" ) {

        $passthrough["javascript"] = "";
    }

    return (array) $passthrough;

}, 1);

*/' . $passthrough["javascript"];

    }

    return (array) $passthrough;

}, 1);



?>