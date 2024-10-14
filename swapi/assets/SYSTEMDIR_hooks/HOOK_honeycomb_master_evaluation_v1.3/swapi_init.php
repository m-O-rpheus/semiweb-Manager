<?php


    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */


    hook_into_swapi_array('swapi_objdetail_evaluation', function( array $passthrough ) {


        // ====== Module Identifier über die Session weitergeben ======
        $identifiercodehash = (string) '';

        if ( true === ( array_key_exists( 'MODULE_honeycomb_master_identifiercode', $_SESSION ) && is_string( $_SESSION['MODULE_honeycomb_master_identifiercode'] ) && !empty( $_SESSION['MODULE_honeycomb_master_identifiercode']) ) ) {

            $identifiercodehash = (string) hash('sha3-224', $_SESSION['MODULE_honeycomb_master_identifiercode']);
        }





        if (
            array_key_exists( 'hcmaction',         $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmaction'] )         && !empty( $passthrough['READONLY_objFromJS']['hcmaction'] )
            &&
            array_key_exists( 'hcmslugtable',      $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmslugtable'] )      && !empty( $passthrough['READONLY_objFromJS']['hcmslugtable'] )
            &&
            array_key_exists( 'hcmunique',         $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmunique'] )         && !empty( $passthrough['READONLY_objFromJS']['hcmunique'] )
            &&
            array_key_exists( 'hcmidentifiercode', $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmidentifiercode'] ) && $passthrough['READONLY_objFromJS']['hcmidentifiercode'] === $identifiercodehash
        ) {

            $result = (array) array();



            // ------ ( #paste ) ------
            if ( $passthrough['READONLY_objFromJS']['hcmaction'] === 'paste') {

                if ( array_key_exists( 'hcmnaming', $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmnaming'] ) && !empty( $passthrough['READONLY_objFromJS']['hcmnaming'] ) ) {

                    $result = (array) MySQL_TABLE_honeycomb_get_row_from_naming( $passthrough['READONLY_objFromJS']['hcmslugtable'], $passthrough['READONLY_objFromJS']['hcmnaming'] );
                }
            }



            // ------ ( #delete ) ------
            if ( $passthrough['READONLY_objFromJS']['hcmaction'] === 'delete') {

                if ( array_key_exists( 'hcmnaming', $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmnaming'] ) && !empty( $passthrough['READONLY_objFromJS']['hcmnaming'] ) ) {

                    $result = (array) array( 'state' => 'are you sure you want to delete the entry (' . $passthrough['READONLY_objFromJS']['hcmnaming'] . ')? <a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_confirmation'], 'data-swapi-naming' => $passthrough['READONLY_objFromJS']['hcmnaming']] ) . '>' . MODULE_activelayer() . 'yes</a>' );
                }
                else {

                    $result = (array) array( 'state' => 'Confirmation error - Wrong Naming' ); 
                }
            }



            // ------ ( #confirmation ) ------
            if ( $passthrough['READONLY_objFromJS']['hcmaction'] === 'confirmation') {

                if ( array_key_exists( 'hcmnaming', $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmnaming'] ) && !empty( $passthrough['READONLY_objFromJS']['hcmnaming'] ) ) {

                    if ( MySQL_TABLE_honeycomb_delete_row_with_naming( $passthrough['READONLY_objFromJS']['hcmslugtable'], $passthrough['READONLY_objFromJS']['hcmnaming'] ) === true ) {
    
                        $result = (array) array( 'state' => 'The deletion of the entry (' . $passthrough['READONLY_objFromJS']['hcmnaming'] . ') was successful. Please reload Page!' ); 
                    }
                    else {

                        $result = (array) array( 'state' => 'Delete error - Database' ); 
                    }
                }
                else {

                    $result = (array) array( 'state' => 'Delete error - Wrong Naming' ); 
                }
            }



            // ------ ( #save ) ------
            if ( $passthrough['READONLY_objFromJS']['hcmaction'] === 'save') {

                if ( array_key_exists( 'hcmnaming', $passthrough['READONLY_objFromJS'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmnaming'] ) && !empty( $passthrough['READONLY_objFromJS']['hcmnaming'] ) ) {

                    if ( array_key_exists( 'hcmsave', $passthrough['READONLY_objFromJS'] ) && is_array( $passthrough['READONLY_objFromJS']['hcmsave'] ) && !empty( $passthrough['READONLY_objFromJS']['hcmsave'] ) ) {

                        if (
                            array_key_exists( 'htmlsnapshot_only_master',   $passthrough['READONLY_objFromJS']['hcmsave'] ) && is_string( $passthrough['READONLY_objFromJS']['hcmsave']['htmlsnapshot_only_master'] )   && !empty( $passthrough['READONLY_objFromJS']['hcmsave']['htmlsnapshot_only_master'] )
                            &&
                            array_key_exists( 'inputfields_both', $passthrough['READONLY_objFromJS']['hcmsave'] ) && is_array(  $passthrough['READONLY_objFromJS']['hcmsave']['inputfields_both'] ) && !empty( $passthrough['READONLY_objFromJS']['hcmsave']['inputfields_both'] )
                        ) {

                            $validation           = (bool) true;
                            $formnaming_no_master = (string) '';

                            foreach ( $passthrough['READONLY_objFromJS']['hcmsave']['inputfields_both'] as $itemKEY => $itemARR ) {

                                if (
                                    !is_string( $itemKEY ) || !preg_match( '/^\{\{item[0-9]+\}\}$/', $itemKEY )
                                    ||
                                    !is_array( $itemARR ) || empty( $itemARR )
                                    ||
                                    !array_key_exists( 'itemstructure', $itemARR ) || !is_string( $itemARR['itemstructure'] ) || empty( $itemARR['itemstructure'] )
                                    ||
                                    !array_key_exists( 'itemdataset',   $itemARR ) || !is_array(  $itemARR['itemdataset'] )     // Hier kein Empty
                                    ||
                                    !array_key_exists( 'itemvalue',     $itemARR ) || !is_string( $itemARR['itemvalue'] )       // Hier kein Empty
                                ) {

                                    $validation = (bool) false;
                                }

                                if ( $validation === true ) {

                                    $passthrough['READONLY_objFromJS']['hcmsave']['inputfields_both'][$itemKEY] += array('itemselector' => preg_split('/(\{\{SLUGTABLE_TEMPLATE_CLASS__[a-z]+\}\}__pos[0-9]+\>)/', $itemARR['itemstructure'], -1, PREG_SPLIT_DELIM_CAPTURE ));

                                    unset( $passthrough['READONLY_objFromJS']['hcmsave']['inputfields_both'][$itemKEY]['itemstructure'] );  // Delete 'itemstructure' da es auch über  implode('', $array['itemselector']) erzeugt werden kann

                                    if ( array_key_exists( 'hcmNaming', $itemARR['itemdataset'] ) ) {  // Es gibt nur ein Feld welches data-hcm-naming enthält!

                                        $formnaming_no_master = (string) $itemARR['itemvalue'];
                                    }
                                }
                            }

                            if ( $validation === true ) {

                                $passthrough['READONLY_objFromJS']['hcmsave']['formnaming_no_master'] = (string) $formnaming_no_master;      // Write formnaming_no_master into array!

                                if ( MySQL_TABLE_honeycomb_create_table_and_insert_or_update_row( $passthrough['READONLY_objFromJS']['hcmslugtable'], $passthrough['READONLY_objFromJS']['hcmnaming'], $passthrough['READONLY_objFromJS']['hcmsave'] ) === true ) {

                                    $result = (array) array( 'state' => 'Save entry (' . $passthrough['READONLY_objFromJS']['hcmnaming'] . ') successful. Please reload Page!' ); 
                                }
                                else {
            
                                    $result = (array) array( 'state' => 'Save error - Database' ); 
                                }
                            }
                            else {

                                $result = (array) array( 'state' => 'Save error - Wrong data compilation of JavaScript 3' );
                            }
                        }
                        else {

                            $result = (array) array( 'state' => 'Save error - Wrong data compilation of JavaScript 2' );
                        }
                    }
                    else {

                        $result = (array) array( 'state' => 'Save error - Wrong data compilation of JavaScript 1' );
                    }
                }
                else {

                    $result = (array) array( 'state' => 'Save error - Please fill out the Naming field' );
                }
            }



            $passthrough['objToJS'] = $passthrough['READONLY_objFromJS'] + array( 'hcmresult' => (array) $result );
        }

        return (array) $passthrough;

    }, 1);

    
    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */


?>