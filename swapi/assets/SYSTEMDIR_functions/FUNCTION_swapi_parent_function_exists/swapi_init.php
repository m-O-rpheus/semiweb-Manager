<?php


    function swapi_parent_function_exists( string $function_name ): bool {

        return (bool) in_array( $function_name, array_column( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 0 ), 'function'), true );
    }


?>