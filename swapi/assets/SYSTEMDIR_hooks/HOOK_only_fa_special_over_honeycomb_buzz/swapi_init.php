<?php


    /* =========================================================================================================================== */

        /* HINWEIS:
                Dieser Hook wird für CORS_FA_FormAction.php aufgerufen!

                Der Aufbau dieser Datei muss ähnlich dem Hook 'swapi_module_honeycomb_buzz_template' 'READONLY_slugtable' 'swapiForms' sein!

    /* =========================================================================================================================== */



    hook_into_swapi_array('swapi_module_honeycomb_buzz_only_fa_special', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiForms' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


            $arr_fieldset  = array();
            $arr_row       = array();
            $arr_attribute = array();

            foreach ( $passthrough['READONLY_inputfields'] as $itemKEY => $itemARR ) {


                // ------ fieldset ------
                if ( array_key_exists( 'swapiformsFieldset', $itemARR['itemdataset'] ) ) {

                    $selstr = '';
                    foreach ( $itemARR['itemselector'] as $str ) {
                        $selstr .= $str;
                        if ( strpos( $str, 'SLUGTABLE_TEMPLATE_CLASS__fieldset' ) !== false ) { break; }
                    }

                    $arr_fieldset[$selstr] = array( 'new_itemkey' => $itemKEY, 'new_itemvalue' => $itemARR['itemvalue'] );
                }



                // ------ row ------
                else if ( array_key_exists( 'swapiformsFieldType', $itemARR['itemdataset'] ) ) {

                    $selstr = '';
                    foreach ( $itemARR['itemselector'] as $str ) {
                        $selstr .= $str;
                        if ( strpos( $str, 'SLUGTABLE_TEMPLATE_CLASS__row' ) !== false ) { break; }
                    }

                    $arr_row[$selstr] = array( 'new_itemkey' => $itemKEY, 'new_itemvalue' => $itemARR['itemvalue'], 'new_itemtype' => $itemARR['itemdataset']['swapiformsFieldType'] );
                }



                // ------ attribute ------
                else if ( array_key_exists( 'swapiformsAttributeName', $itemARR['itemdataset'] ) || array_key_exists( 'swapiformsAttributeValue', $itemARR['itemdataset'] ) ) {

                    $selstr = '';
                    foreach ( $itemARR['itemselector'] as $str ) {
                        $selstr .= $str;
                        if ( strpos( $str, 'SLUGTABLE_TEMPLATE_CLASS__attribute' ) !== false ) { break; }
                    }

                    $arr_attribute[$selstr][] = array( 'new_itemkey' => $itemKEY, 'new_itemvalue' => $itemARR['itemvalue'], 'new_itemattribute' => ( ( array_key_exists( 'swapiformsAttributeName', $itemARR['itemdataset'] ) ) ? 'name' : 'value' ) );
                }
            }



            $final_arr = array();

            foreach ( $arr_fieldset as $selstr1 => $arr1 ) {

                // row ---------------------------------------
                foreach ( $arr_row as $selstr2 => $arr2 ) {

                    if ( strpos( $selstr2, $selstr1 ) !== false ) {

                        // attribute ---------------------------------------
                        $input_attr = array();

                        foreach ( $arr_attribute as $selstr3 => $arr3 ) {

                            if ( strpos( $selstr3, $selstr2 ) !== false ) {

                                $input_attr_name  = (string) '';
                                $input_attr_value = (string) '';

                                foreach ( $arr3 as $attribute_name_value ) {

                                    if ( $attribute_name_value['new_itemattribute'] == 'name' ) {

                                        $input_attr_name = (string) $attribute_name_value['new_itemvalue'];
                                    }
                                    else {

                                        $input_attr_value = (string) $attribute_name_value['new_itemvalue'];
                                    }
                                }

                                $input_attr[$input_attr_name] = $input_attr_value;
                            }
                        }
                        // attribute ---------------------------------------

                        $input_attr['__APPEND__type'] = (string) $arr2['new_itemtype'];
                        $input_attr['__APPEND__id']   = (string) 'swapi_form_' . $passthrough['READONLY_formnaming'] . '_' . $arr2['new_itemvalue'];

                        $final_arr[] = $input_attr;
                    }
                }
                // row ---------------------------------------

            }



            $passthrough['return'] = (array) $final_arr;

            
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 1);


?>