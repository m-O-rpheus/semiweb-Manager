<?php


    /* =========================================================================================================================== */

        /* HINWEIS:
                In dieser Datei wird ein Template für den jeweiligen Generator festgelegt.

                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'xxx' );
                - Muss min. 1x vorkommen und dürfen nicht ineinander verschachtelt werden:   $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();

                - Alle data- Attribute die innerhalb 'MODULE_field_default' definiert werden, werden zusätzlich mit zum Server übertragen!

    /* =========================================================================================================================== */

    

    hook_into_swapi_array('swapi_module_honeycomb_master_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiWysiwyg' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


            /* HINWEIS:

                Es gibt 3 verschiedene Hierarchie Ebenenen:
                
                - Elemente die andere Elemente Enthalten dürfen, und auch ineinander verschachtelt werden können - Ebene 1 ( div ul ol li )
                - Elemente die Abschluss Elemente sind ( p, h1, h2, h3, h4, h5, h6, li, code, adress, blockquote )
                - Elemente die zur Textauszeichnung dienen ( strong, em, u, sub, sup, q, s, small, a, span ) greift nur wenn text Markiert wird.
            */

            $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_structure_main', 'swapi_wysiwyg_structure_root']] ) . '>';
                $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___INSERT']();  // Insert <template> via Javascript here
                $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_wysiwyg_structure_btn'], 'data-swapiwysiwyg' => 'structure'] ) . '>' . MODULE_activelayer() . 'Add Structure</button>';
            $html .= (string) '</div>';





            // ========== TEMPLATE 'structure' BEGIN ==========
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'structure' );

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_structure_main']] ) . '>';
                    $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___INSERT']();  // Insert <template> via Javascript here
                    $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_wysiwyg_structure_btn'], 'data-swapiwysiwyg' => 'structure'] ) . '>' . MODULE_activelayer() . 'Add Structure</button>';
                    $html .= (string) '<button' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_wysiwyg_structure_btn'], 'data-swapiwysiwyg' => 'content'] ) . '>' . MODULE_activelayer() . 'Add Content</button>';
                $html .= (string) '</div>';

                $html .= (string) '<form' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_structure_format']] ) . '>';
                    $html .= (string) MODULE_field_default(array(
                        ['type' => 'radio', 'label' => '<div>', 'name' => 'structure', 'data-swapiwysiwyg' => 'structure_tag_div'],
                        ['type' => 'radio', 'label' => '<ul>',  'name' => 'structure', 'data-swapiwysiwyg' => 'structure_tag_ul' ],
                        ['type' => 'radio', 'label' => '<ol>',  'name' => 'structure', 'data-swapiwysiwyg' => 'structure_tag_ol' ],
                        ['type' => 'radio', 'label' => '<li>',  'name' => 'structure', 'data-swapiwysiwyg' => 'structure_tag_li' ],
                    ), 'dropdown' );
                $html .= (string) '</form>';

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_assign_id']] ) . '>';
                    $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => 'Assign ID', 'data-swapiwysiwyg' => 'assign_id'] ));
                $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();
            // ========== TEMPLATE 'structure' END ==========





            // ========== TEMPLATE 'content' BEGIN ==========
            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___BEGIN']( 'content' );

                $html .= (string) MODULE_field_default(array( ['type' => 'text', 'data-swapiwysiwyg' => 'content_format', 'hidden' => 'hidden', 'value' => '{}'] ));  // Wird nur für JS benötigt
                $html .= (string) MODULE_field_default(array( ['type' => 'text', 'data-swapiwysiwyg' => 'content_final', 'hidden' => 'hidden', 'value' => ''] ));     // Wird nur für PHP benötigt
                $html .= (string) MODULE_field_default(array( ['type' => 'textarea', 'data-swapi-override-style' => 'area_autogrow', 'data-swapi-js-val-innerhtml-passthrough-func' => 'swapiwysiwyg_autogrow_callback', 'data-swapiwysiwyg' => 'content_area', 'placeholder' => 'your content...'] ));

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_content_bar']] ) . '>';
                    // insert here by JavaScript
                $html .= (string) '</div>';

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_content_flow']] ) . '>';
                    // insert here by JavaScript
                $html .= (string) '</div>';

            $html .= (string) $passthrough['READONLY_function_SLUGTABLE___TEMPLATE___END']();
            // ========== TEMPLATE 'content' END ==========
            




            // ========== FOR JS TEMPLATE 'bar' BEGIN ==========
            $html .= (string) '<template' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_bar_tmpl']] ) . '>';

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_bar_wrap']] ) . '>';
                    $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_bar_main']] ) . '>';
                        $html .= (string) '<form' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_bar_format']] ) . '>';
                            $html .= (string) MODULE_field_default(array(
                                ['type' => 'radio', 'label' => '<p>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_p'],
                                ['type' => 'radio', 'label' => '<h1>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_h1'],
                                ['type' => 'radio', 'label' => '<h2>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_h2'],
                                ['type' => 'radio', 'label' => '<h3>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_h3'],
                                ['type' => 'radio', 'label' => '<h4>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_h4'],
                                ['type' => 'radio', 'label' => '<h5>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_h5'],
                                ['type' => 'radio', 'label' => '<h6>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_h6'],
                                ['type' => 'radio', 'label' => '<li>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_li'],
                                ['type' => 'radio', 'label' => '<code>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_code'],
                                ['type' => 'radio', 'label' => '<adress>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_adress'],
                                ['type' => 'radio', 'label' => '<blockquote>', 'name' => 'format', 'data-swapiwysiwyg' => 'content_tag_blockquote'],
                            ), 'dropdown' );
                        $html .= (string) '</form>';

                        $html .= (string) '<form' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_bar_align']] ) . '>';
                            $html .= (string) MODULE_field_default(array(
                                ['type' => 'radio', 'name' => 'align', 'data-swapiwysiwyg' => 'content_align_left'],
                                ['type' => 'radio', 'name' => 'align', 'data-swapiwysiwyg' => 'content_align_center'],
                                ['type' => 'radio', 'name' => 'align', 'data-swapiwysiwyg' => 'content_align_right'],
                                ['type' => 'radio', 'name' => 'align', 'data-swapiwysiwyg' => 'content_align_justify'],
                            ), 'none' );
                        $html .= (string) '</form>';

                        $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_bar_reset_btn', 'swapi_active_layer_parent'], 'title' => 'reset all'] ) . '>' . MODULE_activelayer() . '</a>';                                                          // all reset

                    $html .= (string) '</div>';
                $html .= (string) '</div>';

            $html .= (string) '</template>';
            // ========== FOR JS TEMPLATE 'bar' END ==========





            // ========== FOR JS TEMPLATE 'flow' BEGIN ==========
            $html .= (string) '<template' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_tmpl']] ) . '>';

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_wrap']] ) . '>';
                    $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_main']] ) . '>';

                        $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_grouping']] ) . '>';
                            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_group']] ) . '>';
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_strong', 'title' => 'text with strong importance'] ) . '>' . MODULE_activelayer() . '</a>'; // fett
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_em', 'title' => 'emphasized text'] ) . '>' . MODULE_activelayer() . '</a>';                 // kursive
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_u', 'title' => 'unarticulated annotation'] ) . '>' . MODULE_activelayer() . '</a>';         // unterstrichen
                            $html .= (string) '</div>';
                            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_group']] ) . '>';
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_sub', 'title' => 'subscript text'] ) . '>' . MODULE_activelayer() . '</a>';                 // tiefgestellter text
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_sup', 'title' => 'superscript text'] ) . '>' . MODULE_activelayer() . '</a>';               // hochgestellter text
                            $html .= (string) '</div>';
                            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_group']] ) . '>';
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_q', 'title' => 'short quotation'] ) . '>' . MODULE_activelayer() . '</a>';                  // zitat
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_s', 'title' => 'text that is no longer correct'] ) . '>' . MODULE_activelayer() . '</a>';   // durchgestrichen
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_small', 'title' => 'defines smaller text'] ) . '>' . MODULE_activelayer() . '</a>';         // kleiner text
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_a', 'title' => 'defines a hyperlink'] ) . '>' . MODULE_activelayer() . '</a>';              // hyperlink
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_tag_span', 'title' => 'custom element'] ) . '>' . MODULE_activelayer() . '</a>';                // custom element with custom class
                            $html .= (string) '</div>';
                            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_group']] ) . '>';
                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_btn', 'swapi_active_layer_parent'], 'data-swapiwysiwyg' => 'flow_reset', 'title' => 'reset'] ) . '>' . MODULE_activelayer() . '</a>';                            // reset
                            $html .= (string) '</div>';
                        $html .= (string) '</div>';
                        $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_attr']] ) . '>';
                            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_attr_append']] ) . '></div>';
                            $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_flow_attr_add', 'swapi_active_layer_parent']] ) . '>' . MODULE_activelayer() . 'Add Attribute</a>';
                        $html .= (string) '</div>';

                    $html .= (string) '</div>';
                $html .= (string) '</div>';

            $html .= (string) '</template>';
            // ========== FOR JS TEMPLATE 'flow' END ==========





            // ========== FOR JS TEMPLATE 'attr' BEGIN ==========
            $html .= (string) '<template' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_attr_tmpl']] ) . '>';

                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_attr_main']] ) . '>';
                    $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_attr_remove_btn', 'swapi_active_layer_parent']] ) . '>' . MODULE_activelayer() . '&times;</a>';
                    $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => 'Attribute Name', 'data-swapiwysiwyg' => 'attr_name'] ));
                    $html .= (string) MODULE_field_default(array( ['type' => 'text', 'placeholder' => 'Attribute Value', 'data-swapiwysiwyg' => 'attr_value'] ));
                $html .= (string) '</div>';

            $html .= (string) '</template>';
            // ========== FOR JS TEMPLATE 'attr' END ==========





            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);
            $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_honeycomb_master_'.$passthrough['READONLY_slugtable']);

            $passthrough['return'] = $html;

            
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 100);


?>