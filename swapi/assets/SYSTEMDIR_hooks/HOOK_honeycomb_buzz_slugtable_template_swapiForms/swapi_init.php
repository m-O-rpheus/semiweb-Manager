<?php


    /*
                1-9999   Hook Content beforebegin
            10001-19999  Hook Content afterbegin
            20001-29999  Hook Content beforeend
            30001-39999  Hook Content afterend


            <!-- beforebegin -->
            <form>
                <!-- afterbegin -->
                foo
                <!-- beforeend -->
            </form>
            <!-- afterend -->
    */

    



    // Define container open tag
    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiForms' ) {

            $passthrough['return'] .= (string) '<form' . swapi_prepare_attribute( ['class' => ['swapi_input_form'], 'name' => $passthrough['READONLY_formnaming']] ) . '>';
        }

        return (array) $passthrough;

    }, 10000);

    
    
    // Define container close tag
    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiForms' ) {

            $passthrough['return'] .= (string) '</form>';
        }

        return (array) $passthrough;

    }, 30000);



    // Define container swapiForms Content
    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiForms' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            $arr_fieldset  = array();
            $arr_row       = array();
            $arr_attribute = array();
            $submitlabel   = '';

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


                
                // ------ submit labeling ------
                else if ( array_key_exists( 'swapiformsSubmitLabeling', $itemARR['itemdataset'] ) ) {

                    $submitlabel = $itemARR['itemvalue'];
                }
            }



            // ===========================================================================================================================================
            // ====================================================== generate formular HTML output ======================================================
            // ===========================================================================================================================================
            $required_fieldsets = (bool) false;


            // ===== <fieldset> loop BEGIN =====
            $html = (string) '';

            foreach ( $arr_fieldset as $selstr1 => $arr1 ) {


                // row ---------------------------------------
                $rowcount   = (int) 0;
                $itemtype   = (string) '';
                $inputgroup = (array) array();

                foreach ( $arr_row as $selstr2 => $arr2 ) {

                    if ( strpos( $selstr2, $selstr1 ) !== false ) {

                        $rowcount++;


                        // attribute ---------------------------------------
                        $input_attr = array();

                        foreach ( $arr_attribute as $selstr3 => $arr3 ) {

                            if ( strpos( $selstr3, $selstr2 ) !== false ) {

                                $input_attr_name  = '';
                                $input_attr_value = '';

                                foreach ( $arr3 as $attribute_name_value ) {

                                    if ( $attribute_name_value['new_itemattribute'] === 'name' ) {

                                        $input_attr_name = $attribute_name_value['new_itemvalue'];
                                    }
                                    else {

                                        $input_attr_value = $attribute_name_value['new_itemvalue'];
                                    }
                                }

                                $input_attr[$input_attr_name] = $input_attr_value;
                            }
                        }
                        // attribute ---------------------------------------


                        if ( array_key_exists( 'required', $input_attr ) ) {

                            $required_fieldsets = (bool) true;
                        }


                        $itemtype = (string) $arr2['new_itemtype'];
                        $input_attr['data-swapi-id'] = (string) 'swapi_form_' . $passthrough['READONLY_formnaming'] . '_' . $arr2['new_itemvalue'];



                        // hook - Eingabefeld value überschreiben oder vorbelegen mittels Hook - (Standardwert des Eingabefeldes!) ---------------------------------------
                        if ( array_key_exists( 'value', $input_attr ) ) {

                            $input_val_before_hook = (string) $input_attr['value'];
                        }
                        else {

                            $input_val_before_hook = (string) '';
                        }

                        $input_val_after_hook = (string) register_swapi_hook_point_passthrough_array( 'swapi_default_value_form_field_loop', ['value' => $input_val_before_hook, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'value', 'READONLY_data_swapi_id' => $input_attr['data-swapi-id'], 'READONLY_defined_attribute' => $input_attr, 'READONLY_type' => $itemtype, 'READONLY_form_name' => $passthrough['READONLY_formnaming'], 'READONLY_customer' => $GLOBALS['GLOBAL_customer']] )['value'];

                        if ( $input_val_before_hook !== $input_val_after_hook ) {  // Per hook wurde anderer value gesetzt - Erstelle value oder Überschreibe value

                            $input_attr['value'] = (string) $input_val_after_hook;
                        }
                        // hook - Eingabefeld value überschreiben oder vorbelegen mittels Hook - (Standardwert des Eingabefeldes!) ---------------------------------------



                        if ( $itemtype === 'json' ) {

                            // Kodiere die Eingabe via Hook in einen Base64 String!
                            if ( array_key_exists( 'value', $input_attr ) && !empty( $input_attr['value'] ) ) {

                                $input_val_for_json = (string) base64_encode( $input_attr['value'] );
                            }
                            else {

                                $input_val_for_json = (string) '';
                            }
                            
                            // ===== input json
                            $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_special_adjustment_input_json' ], 'MODULE_honeycomb_buzz_swapiForms_special_adjustment_input_json');

                            $inputgroup[] = (array) array( 'type' => 'text', 'value' => $input_val_for_json, 'data-swapi-id' => $input_attr['data-swapi-id'], 'data-swapi-json-js-helper' => 'swapi_input_json_insert_obj("' . $input_attr['data-swapi-id'] . '", obj);', 'hidden' => 'hidden' );
                        }
                        else {

                            // ===== input default
                            $inputgroup[] = (array) array_merge( $input_attr, array( 'type' => $itemtype ) );
                        }
                    }
                }
                // row ---------------------------------------



                $inputwrapper = (string) 'none';
                $legend       = (string) htmlentities($arr1['new_itemvalue'], ENT_QUOTES);
                $classes      = (array)  array( (string) 'swapi_input_row' );


                if ( $rowcount > 1 ) {

                    $classes[] = (string) 'swapi_input_row_group';
                }
                else {

                    $classes[] = (string) 'swapi_input_row_' . $itemtype;
                }



                if ( !empty( $legend ) ) {

                    if ( substr( $legend, 0, strlen( 'wrap_' ) ) === 'wrap_' ) {

                        $inputwrapper = (string) 'wrap';
                        $legend       = (string) substr( $legend, strlen( 'wrap_' ) );
                        $classes[]    = (string) 'swapi_wrap';
                    }
                    else if ( substr( $legend, 0, strlen( 'dropdown_' ) ) === 'dropdown_' ) {

                        $inputwrapper = (string) 'dropdown';
                        $legend       = (string) substr( $legend, strlen( 'dropdown_' ) );
                        $classes[]    = (string) 'swapi_dropdown';
                    }
                }


                $html .= (string) '<fieldset' . swapi_prepare_attribute( ['class' => $classes, 'style' => ['--swapi-input-count:'.$rowcount.';']] ) . '>';

                    if ( !empty( $legend ) && $inputwrapper !== 'none' ) {   // fieldset naming is not empty and is not none -> Append legend

                        $html .= (string) '<legend>' . $legend . '</legend>';
                    }

                    $html .= (string) MODULE_field_default( $inputgroup, $inputwrapper );

                $html .= (string) '</fieldset>';
            }
            // ===== <fieldset> loop END =====



            $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_submit', 'swapi_active_layer_parent'], 'name' => $passthrough['READONLY_formnaming'], 'type' => 'submit'] ) . '>' . MODULE_activelayer() . htmlentities($submitlabel, ENT_QUOTES) . '</button>';

            if ( $required_fieldsets === true ) {

                $html .= (string) '<p' . swapi_prepare_attribute( ['class' => ['swapi_input_required']] ) . '>' . swapi_required( '* Pflichtfeld' ) . '</p>';
            }



            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_honeycomb_buzz_'.$passthrough['READONLY_slugtable']);

            $passthrough['return'] .= (string) $html;
            
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 20000);


?>