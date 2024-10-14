<?php


    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */


    function MODULE_honeycomb_buzz( string $MySQLslugtable, string $MySQLnaming, array $advanced = array() ): array|string {

        $MySQLslugtable = (string) swapi_sanitize_minimalist($MySQLslugtable);  // Remove invalid characters from the 'MySQLslugtable'
        
        $db_content = (array) MySQL_TABLE_honeycomb_get_row_from_naming( $MySQLslugtable, $MySQLnaming );

        if (
            !empty( $db_content ) && is_array( $db_content )
            &&
            array_key_exists( 'inputfields_both', $db_content )     && is_array(  $db_content['inputfields_both'] )     && !empty( $db_content['inputfields_both'] )
            &&
            array_key_exists( 'formnaming_no_master', $db_content ) && is_string( $db_content['formnaming_no_master'] ) && !empty( $db_content['formnaming_no_master'] )
        ) {

            if ( $MySQLslugtable === 'swapiForms' && swapi_parent_function_exists( 'CORS_FA_FormAction' ) === true ) {  // Only for 'CORS_FA_FormAction.php'

                /* hook */ return (array) register_swapi_hook_point_passthrough_array( 'swapi_module_honeycomb_buzz_only_fa_special', ['return' => [], 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return', 'READONLY_slugtable' => $MySQLslugtable, 'READONLY_formnaming' => $db_content['formnaming_no_master'], 'READONLY_advanced' => $advanced, 'READONLY_inputfields' => $db_content['inputfields_both'] ] )['return'];
            }
            else {

                /* hook */ return (string) register_swapi_hook_point_passthrough_array( 'swapi_module_honeycomb_buzz_template', ['return' => '', 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'return', 'READONLY_slugtable' => $MySQLslugtable, 'READONLY_formnaming' => $db_content['formnaming_no_master'], 'READONLY_advanced' => $advanced, 'READONLY_inputfields' => $db_content['inputfields_both'] ] )['return'];
            }
        }
        else {
            
            return (string) swapi_ERRORMSG( '[MODULE_honeycomb_buzz] the name &quot;' . $MySQLnaming . '&quot; does not exist in the database' );
        }
    }


    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */
    

?>