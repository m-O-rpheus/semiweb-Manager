<?php


    // Define container open tag
    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiButton' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            $filtered_allitems = array();

            $btn_classname  = (string) '';
            $btn_cssdefault = (string) '';



            // 1. FILTER: Filtere aus dem gesammten array nur die relevanten Informationen heraus
            foreach ( $passthrough['READONLY_inputfields'] as $itemkey => $itemarr ) {

                if ( array_key_exists( 'swapibuttonState', $itemarr['itemdataset'] ) && is_string( $itemarr['itemdataset']['swapibuttonState'] ) && $itemarr['itemvalue'] === 'true' ) {

                    $filtered_allitems[$itemkey] = $itemarr;
                }

                else if ( array_key_exists( 'swapibuttonCss', $itemarr['itemdataset'] ) && is_string( $itemarr['itemvalue'] ) ) {

                    $filtered_allitems[$itemkey] = $itemarr;
                }

                else if ( array_key_exists( 'swapibuttonCustomcn', $itemarr['itemdataset'] ) && is_string( $itemarr['itemvalue'] ) ) {

                    $filtered_allitems[$itemkey] = $itemarr;
                }

                else if ( array_key_exists( 'swapibuttonClassname', $itemarr['itemdataset'] ) && is_string( $itemarr['itemvalue'] ) ) {

                    $btn_classname = (string) $itemarr['itemvalue'];
                }

                else if ( array_key_exists( 'swapibuttonDefault', $itemarr['itemdataset'] ) && is_string( $itemarr['itemvalue'] ) ) {

                    $btn_cssdefault = (string) $itemarr['itemvalue']; 
                }
            }



            // 2. FINAL: Generiere den Fertigen Output.
            $genstyle = (string) '';

            honeycomb_buzz_allitems_recursive( (array) $filtered_allitems, function( string $childnode, array $attribute ) use ( $btn_classname, &$genstyle ) : string {

                $data = array();

                foreach ( $attribute as $itemarr ) {

                    if ( array_key_exists( 'swapibuttonState', $itemarr['itemdataset'] ) ) {                // Dropdown Field

                        $data['button_state'] = (string) $itemarr['itemdataset']['swapibuttonState'];
                    }

                    else if ( array_key_exists( 'swapibuttonCss', $itemarr['itemdataset'] ) ) {             // Textarea Field

                        $data['button_css']  = (string) $itemarr['itemvalue'];
                        $data['button_item'] = (string) $itemarr['itemdataset']['hcmItem'];
                    }

                    else if ( array_key_exists( 'swapibuttonCustomcn', $itemarr['itemdataset'] ) ) {        // CustomClass Field

                        $data['button_customclass'] = (string) $itemarr['itemvalue'];
                    }
                }



                $genstyle .= (string) ( ( $data['button_state'] === 'custom' ) ? $btn_classname.$data['button_customclass'] : $btn_classname.':'.$data['button_state'] ) . '{'.$data['button_css'].'}';

                return (string) '';
            });



            $genstyle = (string) $btn_classname.'{'.$btn_cssdefault.'}'.$genstyle;


            $passthrough['return'] = '<style BUTTON_TEST_FRONTEND>' . $genstyle . '</style>';

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 20000);


?>