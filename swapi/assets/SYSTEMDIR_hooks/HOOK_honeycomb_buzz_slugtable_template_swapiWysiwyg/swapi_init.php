<?php


    /*
                1-9999   Hook Content beforebegin
            10001-19999  Hook Content afterbegin
            20001-29999  Hook Content beforeend
            30001-39999  Hook Content afterend


            <!-- beforebegin -->
            <section>
                <!-- afterbegin -->
                foo
                <!-- beforeend -->
            </section>
            <!-- afterend -->
    */

    



    // Define container open tag
    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiWysiwyg' ) {

            $passthrough['return'] .= (string) '<section' . swapi_prepare_attribute( ['class' => ['swapi_wysiwyg_base']] ) . '>';
        }

        return (array) $passthrough;

    }, 10000);


    
    // Define container close tag
    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiWysiwyg' ) {

            $passthrough['return'] .= (string) '</section>';
        }

        return (array) $passthrough;

    }, 30000);



    // Define container swapiWysiwyg Content
    hook_into_swapi_array('swapi_module_honeycomb_buzz_template', function( array $passthrough ) {

        if ( $passthrough['READONLY_slugtable'] === 'swapiWysiwyg' ) {

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            // 1. FILTER: Filtere aus dem gesammten array '$passthrough['READONLY_inputfields']' aus der Datenbank, nur die Relevanten Felder heraus. Erzeuge ein neues Array '$filtered_allitems'!
            // Das bringt vorallem Geschwindigkeitsvorteile, wegen der aufwändigen Funktion 'honeycomb_buzz_allitems_recursive'.
            $filtered_allitems = array();

            foreach ( $passthrough['READONLY_inputfields'] as $itemkey => $itemarr ) {

                if ( array_key_exists( 'swapiwysiwyg', $itemarr['itemdataset'] ) && is_string( $itemarr['itemdataset']['swapiwysiwyg'] ) ) {

                    // Field 'content_final' enthält Formatierungen h1-h6 usw. align left,center usw. sowie Text bereits als HTML Formatiert von JS!
                    if ( $itemarr['itemdataset']['swapiwysiwyg'] === 'content_final' ) {

                        $filtered_allitems[$itemkey] = $itemarr;
                    }

                    // Field 'assign_id' enthält eine zugewiesene ID, zu der jeweiligen structure!
                    else if ( $itemarr['itemdataset']['swapiwysiwyg'] === 'assign_id' ) {

                        $filtered_allitems[$itemkey] = $itemarr;
                    }

                    // Field 'structure_tag_*' enthält die gewählte structure!
                    else if ( str_starts_with( $itemarr['itemdataset']['swapiwysiwyg'], 'structure_tag_' ) ) {

                        if ( $itemarr['itemvalue'] === 'true' ) {

                            $filtered_allitems[$itemkey] = $itemarr;
                        }
                    }
                }
            }



            // 2. FINAL: Generiere den Fertigen HTML Output.
            // Das Fertige HTML innerhalb des '### content Block ($data['content_final'])', wird bereits von JavaScript generiert. Da so die gleiche Ausgabe speziell bei Multibyte Zeichen unterschiede zwischen JS und PHP gewährleistet ist!
            $passthrough['return'] .= (string) honeycomb_buzz_allitems_recursive( (array) $filtered_allitems, function( string $childnode, array $attribute ) : string {

                $data = array();

                foreach ( $attribute as $itemarr ) {

                    // Field 'content_final' enthält Formatierungen h1-h6 usw. align left,center usw. sowie Text bereits als HTML Formatiert von JS!
                    if ( $itemarr['itemdataset']['swapiwysiwyg'] === 'content_final' ) {

                        if ( array_key_exists( 'itemvalue', $itemarr ) && array_key_exists( 'wysiwygJsFormat', $itemarr['itemdataset'] ) && array_key_exists( 'wysiwygJsAlign', $itemarr['itemdataset'] ) ) {

                            $data['content_tag']   = (string) str_replace( 'content_tag_',   '', (string) $itemarr['itemdataset']['wysiwygJsFormat']);
                            $data['content_align'] = (string) str_replace( 'content_align_', '', (string) $itemarr['itemdataset']['wysiwygJsAlign'] );
                            $data['content_final'] = (string) $itemarr['itemvalue'];
                        }
                    }

                    // Field 'assign_id' enthält eine zugewiesene ID, zu der jeweiligen structure!
                    else if ( $itemarr['itemdataset']['swapiwysiwyg'] === 'assign_id' ) {

                        if ( array_key_exists( 'itemvalue', $itemarr ) ) {

                            $data['structure_assignid'] = (string) $itemarr['itemvalue'];
                        }
                    }

                    // Field 'structure_tag_*' enthält die gewählte structure!
                    else if ( str_starts_with( $itemarr['itemdataset']['swapiwysiwyg'], 'structure_tag_' ) ) {

                        $data['structure_tag'] = (string) str_replace( 'structure_tag_', '', (string) $itemarr['itemdataset']['swapiwysiwyg'] );
                    }
                }


                // ### content Block
                if ( array_key_exists( 'content_tag', $data ) && array_key_exists( 'content_align', $data ) && array_key_exists( 'content_final', $data ) ) {

                    return (string) MODULE_legacy_text( array( [ (string) $data['content_tag'], (string) $data['content_final'], (array) (( $data['content_align'] !== 'left' ) ? ['style' => ['text-align:'.$data['content_align'].';']] : []) ] ), 'none' );
                }

                // ### structure Block
                else if ( array_key_exists( 'structure_assignid', $data ) && array_key_exists( 'structure_tag', $data ) ) {

                    $attr = ['class' => ['swapi_wysiwyg_type_structure']];

                    if ( !empty( $data['structure_assignid'] ) ) {

                        $attr['data-swapi-structure-assignid'] = $data['structure_assignid'];
                    }

                    return (string) '<' . $data['structure_tag'] . swapi_prepare_attribute( $attr ) . '>' . $childnode . '</' . $data['structure_tag'] . '>';
                }

                return (string) swapi_ERRORMSG( '[honeycomb_buzz_template] Unknown error' );
            });

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        }

        return (array) $passthrough;

    }, 20000);


?>