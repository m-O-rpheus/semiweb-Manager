<?php


    // Diese Funktion wird ausschlißlich für MODULE_honeycomb_buzz Templates eingesetzt.
    // Erzeuge aus dem flachen Array '$allitems' innerhalb der Template Datei ein recursives Array welches den DOM Baum repräsentiert.
    // Diese Funktion kann möglicherweise für ALLE 'honeycomb_buzz' - 'slugtable_templates' eingesetzt werden. Ist aber aktuell Stand 04.01.2023 noch BETA VERSION
    // ----------------------------------------------------------------------------------------------------------------------
    function honeycomb_buzz_allitems_recursive( array $allitems, closure $callback ) : string {


        $func_itemrecursive = function( array $itemselector_removeone, string $itemkey_passthrough, int $lastslugtablekey_passthrough, int $loopcounter_addone ) use ( &$func_itemrecursive ) : array {

            $loopcounter_addone++;

            $temp_return = array();
            $temp_key    = array_shift( $itemselector_removeone );
    
            if ( !empty( $itemselector_removeone ) ) {
    
                $temp_return[$temp_key] = (array) $func_itemrecursive( (array) $itemselector_removeone, (string) $itemkey_passthrough, (int) $lastslugtablekey_passthrough, (int) $loopcounter_addone );
            }
            else {
    
                $temp_return[$temp_key] = (string) $itemkey_passthrough;
            }

            // if: Wenn der parent array key 'SLUGTABLE_TEMPLATE_CLASS__' enthält, dann verschachtele die children in ein zusätzliches helper array!
            if ( $loopcounter_addone === $lastslugtablekey_passthrough ) {
    
                $temp_return[$temp_key] = array( 'PARENTITEM' => [$itemkey_passthrough], 'RECURSIVE' => $temp_return[$temp_key] );
            }
    
            return (array) $temp_return;
        };


        $func_domloop = function( array $allitems_merged, array $allitems_passthrough, closure $callback ) use ( &$func_domloop ) : string {

            $temp_return = (string) '';

            foreach ( $allitems_merged as $allitems_merged_recursive ) {

                if ( is_array( $allitems_merged_recursive ) ) {

                    if ( array_key_exists( 'PARENTITEM', $allitems_merged_recursive ) ) {

                        $attribute = array();

                        foreach ( $allitems_merged_recursive['PARENTITEM'] as $itemkey ) {

                            $attribute[$itemkey] = $allitems_passthrough[$itemkey];
                        }

                        $temp_return .= (string) $callback( (string) $func_domloop( (array) $allitems_merged_recursive, (array) $allitems_passthrough, $callback ), (array) $attribute );
                    }
                    else {

                        $temp_return .= (string) $func_domloop( (array) $allitems_merged_recursive, (array) $allitems_passthrough, $callback );
                    }
                }
            }

            return (string) $temp_return;
        };
    

        // foreach1: Durchlaufe das Array '$allitems'. Erzeuge ein neues Array '$allitems_merged' welches den DOM Baum Repräsentiert.
        $allitems_merged = array();
    
        foreach ( $allitems as $itemkey => $itemarr ) {

            // foreach2: Ermittele den KEY vom letzten Element innerhalb ['itemselector']. Welches 'SLUGTABLE_TEMPLATE_CLASS__' enthält, dann Abbruch und verwende $lastslugtablekey weiter!
            $lastslugtablekey = (int) 0;

            foreach ( array_reverse( $itemarr['itemselector'], true ) as $lastslugtablekey => $value ) {

                if ( strpos( (string) $value, 'SLUGTABLE_TEMPLATE_CLASS__' ) !== false ) { break 1; }
            }

            $allitems_merged = (array) array_merge_recursive( $allitems_merged, $func_itemrecursive( (array) $itemarr['itemselector'], (string) $itemkey, (int) $lastslugtablekey, (int) -1 ) );
        }


        return (string) $func_domloop( (array) $allitems_merged, (array) $allitems, $callback );
    }





    // Diese Funktion erstellt aus einem PHP Array einen aufbereiteten HTML Attribute String.
    // Beispiel: swapi_prepare_attribute( ['data-test' => 'test', 'name' => '123', 'class' => ['c1a', 'c1b', 'c1c'] ] );
    // Es ist möglich als attribute_value Strings, als auch Arrays anzugeben.
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_prepare_attribute( array $attribute_array, bool $output_as_extended_arr = false ): string|array {

        $boolean_attributes = (array) array('allowfullscreen', 'async', 'autofocus', 'autoplay', 'checked', 'controls', 'default', 'defer', 'disabled', 'formnovalidate', 'hidden', 'inert', 'ismap', 'itemscope', 'loop', 'multiple', 'muted', 'nomodule', 'novalidate', 'open', 'playsinline', 'readonly', 'required', 'reversed', 'selected' );

        $attribute_arr_nonbool = (array) array();
        $attribute_arr_boolean = (array) array();

        /* hook */ $boolean_attributes = (array) register_swapi_hook_point_passthrough_array( 'swapi_prepare_attribute_booleanattr', ['return' => $boolean_attributes, 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return'] )['return'];
        /* hook */ $attribute_array    = (array) register_swapi_hook_point_passthrough_array( 'swapi_prepare_attribute_array',       ['return' => $attribute_array,    'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return'] )['return'];

        foreach ( $attribute_array as $attribute_name => $attribute_value ) {

            if ( is_string( $attribute_name ) && !empty( $attribute_name ) ) {


                // Optional: Attribute value ist ein Array - Wandele es in einen String um!
                if ( is_array( $attribute_value ) ) {

                    $attribute_value = (string) implode( ' ', $attribute_value );
                }


                // Optional: Attribute value ist ein Int, Float, Bool - Wandele es in einen String um!
                else if ( is_int( $attribute_value ) || is_float( $attribute_value ) || is_bool( $attribute_value ) ) {

                    $attribute_value = (string) strval( $attribute_value );
                }


                // Gefordert: Attribute value muss ein String sein (entweder von foreach oder von is_* siehe weiter oben)
                if ( is_string( $attribute_value ) ) {                                      // Hier kein empty, values dürfen auch leer sein!

                    $attribute_name = (string) swapi_sanitize_azlower( $attribute_name );   // Setze alles kleingeschrieben sowie entferne alle zeichen außer a-z -

                    if ( !in_array( $attribute_name, $boolean_attributes ) ) {


                        if ( !swapi_validate_url( $attribute_value ) ) {

                            $attribute_value = (string) htmlspecialchars( $attribute_value, ENT_QUOTES );
                        }


                        // $attribute_value ist ab hier, entweder eine gültige URL oder ein durch htmlspecialchars unschädlich gemachter String!


                        $attribute_arr_nonbool[$attribute_name] = (string) $attribute_value;
                    }
                    else {

                        $attribute_arr_boolean[$attribute_name] = (bool) true;
                    }
                }
            }
        }


        // ----- Array to String _nonbool ----- Performence Optimierung 14.03.2023
        $attribute_str_nonbool = (string) '';

        foreach ( $attribute_arr_nonbool as $attribute_name => $attribute_value ) {

            $attribute_str_nonbool .= (string) ' ' . $attribute_name . "='" . $attribute_value . "'";
        }


        // ----- Array to String _boolean ----- Performence Optimierung 14.03.2023
        $attribute_str_boolean = (string) '';

        foreach ( $attribute_arr_boolean as $attribute_name => $attribute_value ) {

            $attribute_str_boolean .= (string) ' ' . $attribute_name;
        }


        if ( $output_as_extended_arr === true ) {

            return (array) array( 'string' => (string) ($attribute_str_nonbool . $attribute_str_boolean), 'array' => (array) array_merge($attribute_arr_nonbool, $attribute_arr_boolean) );
        } 
        else {

            return (string) ($attribute_str_nonbool . $attribute_str_boolean);
        }        
    }





    // Diese Funktion definiert das Grundgerüst einer Fehlermeldung speziell für die Programmierung - Falsche Typen in Array, String Empty usw.
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_ERRORMSG( string $errorstr ): string {

        return (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_module_error'], 'style' => ['color:var(--swapi-core-default-accent);']] ) . '>ERROR: ' . $errorstr . '</div>';
    }





    // Diese Funktion definiert das Grundgerüst eines Required Indikators
    // ----------------------------------------------------------------------------------------------------------------------
    function swapi_required( string $str = '*' ): string {

        return (string) '<span' . swapi_prepare_attribute( ['class' => ['swapi_required']] ) . '>' . $str . '</span>';
    }





    // Erzeugt aus einem Array mit 'keys'. - Beispiel: - $keys = ['key1', 'key2', 'key3'...]
    // Ein Rekursives Array, welches folgender Zusammenstellung enspricht. - Beispiel: - $array['key1']['key2']['key3']... = $value;
    // Die funktion wird folgendermaßen aufgerufen: - Beispiel: - $recursive_array = swapi_array_levels_recursive( ['key1', 'key2', 'key3'], 'innerValue' );
    // ----------------------------------------------------------------------------------------------------------------------
    /*function swapi_array_levels_recursive( array $keys, string $value = '' ): array {

        $return = array();

        $key = array_shift( $keys );

        if ( !empty( $keys ) ) {

            $return[$key] = (array) swapi_array_levels_recursive( (array) $keys, (string) $value );
        }
        else {

            $return[$key] = (string) $value;
        }

        return (array) $return;
    }*/


?>