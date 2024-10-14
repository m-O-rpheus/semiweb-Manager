<?php


/* Gibt sofern man eingeloggt ist, eine Begrüßungsnachricht incl Logout Link aus!

        MODULE_loggedin_welcome_message();

/* ========================================================================================================================================================== */

    function MODULE_loggedin_welcome_message(): string {

        if ( php_session_is_logged_in() ) {

            $return = (string) 'Hallo <{user}> (nicht&#32;<{user}>?&#32;<{logout}>)';

            /* hook */ $return = (string) register_swapi_hook_point_passthrough_array( 'swapi_loggedin_welcome_message_text', ['text' => $return, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'text', 'READONLY_available_placeholders' => ['<{user}>', '<{logout}>'] ] )['text'];

            return (string) str_replace( array('<{user}>', '<{logout}>'), array( $_SESSION['core_session_user'], MODULE_hyperlink( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_logout'], 'Abmelden' )), $return );
        }

        return (string) swapi_ERRORMSG( '[MODULE_loggedin_welcome_message] Please log in to use the module' );
    }

    
?>