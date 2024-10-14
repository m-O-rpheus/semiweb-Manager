<?php


    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */


    function MODULE_honeycomb_master( string $MySQLslugtable ): string {


        // ====== Module Identifier über die Session weitergeben ======
        if ( false === ( array_key_exists( 'MODULE_honeycomb_master_identifiercode', $_SESSION ) && is_string( $_SESSION['MODULE_honeycomb_master_identifiercode'] ) && !empty( $_SESSION['MODULE_honeycomb_master_identifiercode']) ) ) {

            $_SESSION['MODULE_honeycomb_master_identifiercode'] = (string) swapi_generate_uniquestr(100);
        }

        $identifierhash = (string) hash('sha3-224', $_SESSION['MODULE_honeycomb_master_identifiercode']);
        $MySQLslugtable = (string) swapi_sanitize_minimalist($MySQLslugtable);  // Remove invalid characters from the 'MySQLslugtable'



        $html = (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_honeycomb_master');   // Reihenfolge wichtig: 1. Load main css

        $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_root'], 'data-swapi-slugtable' => $MySQLslugtable, 'data-swapi-unique' => swapi_generate_uniquehash(), 'data-swapi-identifiercode' => $identifierhash] ) . '>';

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_msg']] ) . '></div>';

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_choose']] ) . '>';



                    // ------------------------------
                    $arr = array(
                        'Create new'   => (array) array( 'New...' ),
                        'My Templates' => (array) MySQL_TABLE_honeycomb_get_all_naming( $MySQLslugtable ),
                    );

                    $i = 0;

                    foreach ( $arr as $groupcaption => $namingarray ) {

                        if ( is_array( $namingarray ) && !empty( $namingarray ) ) {

                            $html .= (string) '<figure>';
                                $html .= (string) '<figcaption>' . $groupcaption . '</figcaption>';
                                $html .= (string) '<ul>';

                                foreach ( $namingarray as $namingstring ) {

                                    if ( is_string( $namingstring ) ) {

                                        $namingstring = swapi_sanitize_minimalist($namingstring);  // Remove invalid characters from the 'namingstring'

                                        $html .= (string) '<li' . swapi_prepare_attribute( ['data-swapi-naming' => $namingstring] ) . '>';

                                            if ( $i > 0 ) {

                                                $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_delete']] ) . '>' . MODULE_activelayer() . '&times;</a>';
                                            }

                                            $html .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_choosen']] ) . '>' . MODULE_activelayer() . $namingstring . '</a>';
                                        $html .= (string) '</li>';
                                    }
                                }

                                $html .= (string) '</ul>';
                            $html .= (string) '</figure>';
                        }

                        $i++;
                    }
                    // ------------------------------



            $html .= (string) '</div>';

            $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_paste']] ) . '></div>';  // Paste Content here with JS

            $html .= (string) '<template' . swapi_prepare_attribute( ['class' => ['swapi_hcm_template']] ) . '>';


                    
                    // ------------------------------ complete template for MySQLslugtable begin ------------------------------
                    $SLUGTABLE___TEMPLATE___MAIN = function( string $MySQLslugtable ): string {  // Hinweis 'actionbtns' und 'head' werden deswegen mit in das Template verpackt, damit es beim leeren des 'paste' Containers komplett mit entfernt wird! Muss so bleiben! Reihenfolge wichtig: 2. Load slugtable specific css and js from Hook

                        $template = (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_ctas']] ) . '>';
                            $template .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_savebtn']] ) . '>' . MODULE_activelayer() . 'Save</a>';
                            $template .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_resetbtn']] ) . '>' . MODULE_activelayer() . 'Reset</a>';
                            $template .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_embedbtn']] ) . '>' . MODULE_activelayer() . 'Embed</a>';
                            $template .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_exportbtn']] ) . '>' . MODULE_activelayer() . 'Export</a>';
                            $template .= (string) '<a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_importbtn']] ) . '>' . MODULE_activelayer() . 'Import</a>';
                            $template .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_importfile', 'swapi_hcm_hide']] ) . '>' . MODULE_field_default(array( ['type' => 'file', 'label' => 'Upload the file to Import data', 'accept' => '.txt'] )) . '</div>';
                        $template .= (string) '</div>';

                        $template .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_head']] ) . '>';
                            $template .= (string) MODULE_field_default(array( ['type' => 'text', 'label' => $MySQLslugtable . ' Naming [a-zA-Z0-9_-.]', 'data-hcm-naming' => ''] ));
                        $template .= (string) '</div>';

                        $template .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_body']] ) . '>';  // Class darf nur ein mal vorkommen!


                            // ------------------------------ include template for MySQLslugtable via hook begin ------------------------------
                            $SLUGTABLE___TEMPLATE___BEGIN = function( string $tmplname ): string {  // WICHTIG: Bitte an der DOM Struktur nichts ändern!

                                $tmplname = 'SLUGTABLE_TEMPLATE_CLASS__' . preg_replace( '/[^a-z]/', '', strtolower( $tmplname ) );
                            
                                return (string) '
                                    <template' . swapi_prepare_attribute( ['class' => [$tmplname]] ) . '>
                                        <div' . swapi_prepare_attribute( ['class' => [$tmplname]] ) . '>
                                            <div' . swapi_prepare_attribute( ['class' => ['SLUGTABLE_CONTAINER']] ) . '>
                                                <a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_unset']] ) . '>' . MODULE_activelayer() . '&times;</a>
                                                <div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_move']] ) . '>
                                                    <a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_up']] ) . '>' . MODULE_activelayer() . '&#8593;</a>
                                                    <a' . swapi_prepare_attribute( ['class' => ['swapi_btn', 'swapi_active_layer_parent', 'swapi_hcm_down']] ) . '>' . MODULE_activelayer() . '&#8595;</a>
                                                </div>
                                ';
                            };
                            
                            $SLUGTABLE___TEMPLATE___END = function(): string {                      // WICHTIG: Bitte an der DOM Struktur nichts ändern!
                            
                                return (string) '
                                            </div>
                                        </div>
                                    </template>
                                ';
                            };

                            $SLUGTABLE___TEMPLATE___INSERT = function(): string {                   // WICHTIG: Bitte an der DOM Struktur nichts ändern!
                            
                                return (string) '<div' . swapi_prepare_attribute( ['class' => ['SLUGTABLE_TEMPLATE_INSERT']] ) . '></div>';
                            };

                            /* hook */ $template .= (string) register_swapi_hook_point_passthrough_array( 'swapi_module_honeycomb_master_template', ['return' => '', 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'return', 'READONLY_slugtable' => $MySQLslugtable, 'READONLY_function_SLUGTABLE___TEMPLATE___BEGIN' => $SLUGTABLE___TEMPLATE___BEGIN, 'READONLY_function_SLUGTABLE___TEMPLATE___END' => $SLUGTABLE___TEMPLATE___END, 'READONLY_function_SLUGTABLE___TEMPLATE___INSERT' => $SLUGTABLE___TEMPLATE___INSERT ] )['return'];

                            // ------------------------------ include template for MySQLslugtable via hook end ------------------------------


                        $template .= (string) '</div>';

                        $template = (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_hcm_header'], 'data-template-hcm-time' => strval(time() * 1000), 'data-template-hcm-hash' => hash('sha3-224', $template)] ) . '></div>' . $template;

                        return (string) $template;
                    };

                    $html .= (string) $SLUGTABLE___TEMPLATE___MAIN( (string) $MySQLslugtable );
                    // ------------------------------ complete template for MySQLslugtable end ------------------------------

                    

            $html .= (string) '</template>';

        $html .= (string) '</div>';

        $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_honeycomb_master');  // Reihenfolge wichtig: 3. Load main js



        return (string) $html;
    }


    /* =========================================================================================================================== */
    /* ACHTUNG: Bitte in Dieser Datei nichts ändern! Änderungen werden alle mittels Hook via Template Datei gesteuert!             */
    /* =========================================================================================================================== */
    

?>