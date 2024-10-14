<?php


    /* =========================================================================================================================== */

        /* HINWEIS:
                In dieser Datei wird ein Template für den jeweiligen Generator festgelegt.

                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'xxx' );
                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();

                - Alle data- Attribute die innerhalb 'MODULE_field_default' definiert werden, werden zusätzlich mit zum Server übertragen!

    /* =========================================================================================================================== */

    
    
    hook_into_swapi_array('swapi_module_honeycomb_master_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiBtn' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


            $nav    = function( string $content = '' ) : string { return (string) '<nav' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_nav', 'slugtable_template_swapiBtn_adjust']] ) . '><div>' . $content . '</div></nav>'; };
            $row    = function( string $content = '' ) : string { return (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_row', 'slugtable_template_swapiBtn_adjust']] ) . '><nav></nav>' . $content . '</div>'; };  // <nav> darf keine attribute haben wegen JS!
            $column = function( string $content = '' ) : string { return (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_column']] ) . '>' . $content . '</div>'; };



            $nav_firstrow = (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_state']] ) . '>';
                $nav_firstrow .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => '.css_class_extend', 'data-swapibtn-extendname' => ''] ));
                $nav_firstrow .= (string) MODULE_field_default(array( ['type' => 'textarea', 'placeholder' => 'CSS styling here', 'data-swapibtn-extendstyle' => ''] ));
            $nav_firstrow .= (string) '</div>';



            $nav_otherrow = (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_state']] ) . '>';
                $nav_otherrow .= (string) '<form' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_dropdown']] ) . '>';
                    $nav_otherrow .= (string) MODULE_field_default(array(
                        ['type' => 'radio', 'label' => ':visited',  'name' => 'state', 'data-swapibtn-pseusoname' => 'visited'],
                        ['type' => 'radio', 'label' => ':active',   'name' => 'state', 'data-swapibtn-pseusoname' => 'active' ],
                        ['type' => 'radio', 'label' => ':hover',    'name' => 'state', 'data-swapibtn-pseusoname' => 'hover' ],
                        ['type' => 'radio', 'label' => ':focus',    'name' => 'state', 'data-swapibtn-pseusoname' => 'focus' ],
                        ['type' => 'radio', 'label' => ':disabled', 'name' => 'state', 'data-swapibtn-pseusoname' => 'disabled' ],
                    ), 'dropdown' );
                $nav_otherrow .= (string) '</form>';
                $nav_otherrow .= (string) MODULE_field_default(array( ['type' => 'textarea', 'placeholder' => 'CSS styling here', 'data-swapibtn-pseusostyle' => ''] ));
            $nav_otherrow .= (string) '</div>';


            
            $delcolumn = (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'slugtable_template_swapiBtn_btn', 'slugtable_template_swapiBtn_delcolumn']] ) . '>' . MODULE_activelayer() . '-</button>';
            $example   = (string) '<a' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_example']] ) . '></a>';





            $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_state']] ) . '>';
                $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => '.css_class', 'data-swapibtn-generalname' => ''] ));
                $html .= (string) MODULE_field_default(array( ['type' => 'textarea', 'placeholder' => 'CSS styling here', 'data-swapibtn-generalstyle' => ''] ));
            $html .= (string) '</div>';

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_container'], 'data-count-buttons' => '1'] ) . '>';
                $html .= (string) $row( $column( $delcolumn.$example ) ).$nav( $nav_firstrow );
                $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___INSERT']();  // Insert <template> via Javascript here
            $html .= (string) '</div>';

            $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'slugtable_template_swapiBtn_btn', 'slugtable_template_swapiBtn_newcolumn']] ) . '>' . MODULE_activelayer() . '+</button>';
            $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'slugtable_template_swapiBtn_btn', 'slugtable_template_swapiBtn_newrow']] ) . '>' . MODULE_activelayer() . '+</button>';

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_style']] ) . '></div>';



            // ========== TEMPLATE 'row' BEGIN ==========
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'row' );
                $html .= (string) $row( $column( $example ) ).$nav( $nav_otherrow );
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();
            // ========== TEMPLATE 'row' END ==========



            // ========== FOR JS TEMPLATE 'first row' BEGIN ==========
            $html .= (string) '<template' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_templatefirst']] ) . '>';
                $html .= (string) $column( $delcolumn.$example );
            $html .= (string) '</template>';
            // ========== FOR JS TEMPLATE 'first row' END ==========



            // ========== FOR JS TEMPLATE 'other row' BEGIN ==========
            $html .= (string) '<template' . swapi_prepare_attribute( ['class' => ['slugtable_template_swapiBtn_templateother']] ) . '>';
                $html .= (string) $column( $example );
            $html .= (string) '</template>';
            // ========== FOR JS TEMPLATE 'other row' END ==========



            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);
            $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);

            $passthrough['return'] = $html;

            
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 100);
    

?>