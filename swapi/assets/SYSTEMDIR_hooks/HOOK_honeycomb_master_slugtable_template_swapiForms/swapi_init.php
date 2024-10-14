<?php


    /* =========================================================================================================================== */

        /* HINWEIS:
                In dieser Datei wird ein Template für den jeweiligen Generator festgelegt.

                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'xxx' );
                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();

                - Alle data- Attribute die innerhalb 'MODULE_field_default' definiert werden, werden zusätzlich mit zum Server übertragen!

    /* =========================================================================================================================== */

    
    
    hook_into_swapi_array('swapi_module_honeycomb_master_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiForms' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


            $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_submit_labeling']] ) . '>';
                $html .= (string) MODULE_field_default(array( ['type' => 'text', 'label' => 'Submit Labeling', 'data-swapiforms-submit-labeling' => ''] ));
            $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___INSERT']();  // Insert <template> via Javascript here

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_types']] ) . '>';
            
                $html .= (string) '<ul' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_add_field']] ) . '>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'text'] ) . '>' . MODULE_activelayer() . 'Einzeiliger Text</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'email'] ) . '>' . MODULE_activelayer() . 'E-Mail</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'number'] ) . '>' . MODULE_activelayer() . 'Zahlen</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'range'] ) . '>' . MODULE_activelayer() . 'Schieberegler</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'tel'] ) . '>' . MODULE_activelayer() . 'Telefon</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'url'] ) . '>' . MODULE_activelayer() . 'Webseite/URL</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'search'] ) . '>' . MODULE_activelayer() . 'Suche</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'password'] ) . '>' . MODULE_activelayer() . 'Passwort</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'textarea'] ) . '>' . MODULE_activelayer() . 'Textabsatz</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'radio'] ) . '>' . MODULE_activelayer() . 'Multiple Choice</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'checkbox'] ) . '>' . MODULE_activelayer() . 'Checkboxen</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'file'] ) . '>' . MODULE_activelayer() . 'Dateiupload</li>';
                    $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent'], 'data-swapiforms-field-type' => 'json'] ) . '>' . MODULE_activelayer() . 'JSON</li>';
                $html .= (string) '</ul>';

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_fieldset_labeling']] ) . '>';
                    $html .= (string) MODULE_field_default(array( ['type' => 'text', 'label' => '<fieldset>', 'data-swapiforms-fieldset-labeling' => ''] ));
                $html .= (string) '</div>';

            $html .= (string) '</div>';





            // ========== TEMPLATE 'fieldset' BEGIN ==========
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'fieldset' );

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_fieldset']] ) . '>';

                $html .= (string) MODULE_field_default(array( ['type' => 'text', 'label' => '<fieldset>', 'data-swapiforms-fieldset' => ''] ));

                $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___INSERT']();  // Insert <template> via Javascript here

            $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();
            // ========== TEMPLATE 'fieldset' END ==========





            // ========== TEMPLATE 'row' BEGIN ==========
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'row' );

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_row'], 'data-swapiforms-field-type' => ''] ) . '>';  // data-swapiforms-field-type for icon

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_id']] ) . '>';
                    $html .= (string) MODULE_field_default(array( ['type' => 'text', 'label' => 'ID (data-swapi-id) [a-zA-Z0-9_-.]', 'data-swapiforms-field-type' => ''] ));
                $html .= (string) '</div>';

                $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___INSERT']();  // Insert <template> via Javascript here

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_add_attribute']] ) . '>';  // Das <a> Tag muss verschachtelt sein. Da JS die class des <div> anspricht!
                    $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent']] ) . '>' . MODULE_activelayer() . 'Add Attribute</a>';
                $html .= (string) '</div>';

            $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();
            // ========== TEMPLATE 'row' END ==========





            // ========== TEMPLATE 'attribute' BEGIN ==========
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'attribute' );

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiForms_attribute']] ) . '>';

                $html .= (string) '<div>';
                    $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => 'Attribute Name', 'data-swapiforms-attribute-name' => ''] ));
                $html .= (string) '</div>';

                $html .= (string) '<div>';
                    $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => 'Attribute Value', 'data-swapiforms-attribute-value' => ''] ));
                $html .= (string) '</div>';

            $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();
            // ========== TEMPLATE 'attribute' END ==========





            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);
            $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);

            $passthrough['return'] = $html;

            
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 100);


?>