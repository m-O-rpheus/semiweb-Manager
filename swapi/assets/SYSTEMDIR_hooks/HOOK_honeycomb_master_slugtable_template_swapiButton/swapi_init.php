<?php


    /* =========================================================================================================================== */

        /* HINWEIS:
                In dieser Datei wird ein Template für den jeweiligen Generator festgelegt.

                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'xxx' );
                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();

                - Alle data- Attribute die innerhalb 'MODULE_field_default' definiert werden, werden zusätzlich mit zum Server übertragen!

    /* =========================================================================================================================== */

    
    
    hook_into_swapi_array('swapi_module_honeycomb_master_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiButton' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


            $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiButton_style']] ) . '></div>';

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiButton_state']] ) . '>';
                $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => '.css_class_name', 'data-swapibutton-classname' => ''] ));
                $html .= (string) MODULE_field_default(array( ['type' => 'textarea', 'placeholder' => 'CSS styling here', 'data-swapibutton-default' => ''] ));
                $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiButton_example']] ) . '><i></i><i></i><i></i></button>';
            $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___INSERT']();  // Insert <template> via Javascript here

            $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'slugtable_template_swapiButton_btnadd']] ) . '>' . MODULE_activelayer() . '</button>';



            // ========== TEMPLATE 'state' BEGIN ==========
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'state' );

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiButton_state']] ) . '>';
                $html .= (string) '<form' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiButton_dropdown']] ) . '>';
                    $html .= (string) MODULE_field_default(array(
                        ['type' => 'radio', 'label' => ':visited', 'name' => 'state', 'data-swapibutton-state' => 'visited'],
                        ['type' => 'radio', 'label' => ':active',  'name' => 'state', 'data-swapibutton-state' => 'active' ],
                        ['type' => 'radio', 'label' => ':hover',  'name' => 'state', 'data-swapibutton-state' => 'hover' ],
                        ['type' => 'radio', 'label' => ':focus',  'name' => 'state', 'data-swapibutton-state' => 'focus' ],
                        ['type' => 'radio', 'label' => ':disabled',  'name' => 'state', 'data-swapibutton-state' => 'disabled' ],
                        ['type' => 'radio', 'label' => ':custom',  'name' => 'state', 'data-swapibutton-state' => 'custom' ],
                    ), 'dropdown' );
                $html .= (string) '</form>';
                $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => '.additional_class_name', 'data-swapibutton-customcn' => ''] ));
                $html .= (string) MODULE_field_default(array( ['type' => 'textarea', 'placeholder' => 'CSS styling here', 'data-swapibutton-css' => ''] ));
                $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiButton_example']] ) . '><i></i><i></i><i></i></button>';
            $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();
            // ========== TEMPLATE 'state' END ==========



            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);
            $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);

            $passthrough['return'] = $html;

            
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 100);
    

?>