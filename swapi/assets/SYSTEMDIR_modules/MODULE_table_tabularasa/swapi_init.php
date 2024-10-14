<?php

    /*

        MODULE_table_tabularasa( [
            ['thead_row1_column1', 'thead_row1_column2', 'thead_row1_column3'],
            ['thead_row2_column1', 'thead_row2_column2', 'thead_row2_column3'],
            ['thead_row3_column1', 'thead_row3_column2', 'thead_row3_column3'],
        ], [
            ['tbody_row1_column1', 'tbody_row1_column2', 'tbody_row1_column3'],
            ['tbody_row2_column1', 'tbody_row2_column2', 'tbody_row2_column3'],
            ['tbody_row3_column1', 'tbody_row3_column2', 'tbody_row3_column3'],
        ], [
            ['tfoot_row1_column1', 'tfoot_row1_column2', 'tfoot_row1_column3'],
            ['tfoot_row2_column1', 'tfoot_row2_column2', 'tfoot_row2_column3'],
            ['tfoot_row3_column1', 'tfoot_row3_column2', 'tfoot_row3_column3'],
        ] );

    */
    function MODULE_table_tabularasa( array $thead = array(), array $tbody = array(), array $tfoot = array(), string $unique = '' ): string {

        $thtml = function( string $tag, string $content, array $attr ): string {

            return (string) '<' . $tag . swapi_prepare_attribute( array_merge( ['class' => ['swapi_tabularasa_' . $tag]], $attr ) ) . '>' . $content . '</' . $tag . '>';
        };
        
        $tlevel = function( string $tag, array $tarray, closure $thtml, string $unique ): string {

            $html = (string) '';

            if ( !empty( $tarray ) ) {

                $htmlrow = (string) '';

                foreach( $tarray as $row ) {

                    if ( !empty( $row ) && is_array( $row ) ) {

                        $htmlcolumn = (string) '';

                        foreach ( $row as $column ) {

                            if ( is_string( $column ) ) {

                                $htmlcolumn .= (string) $thtml( ( ( $tag === 'thead' ) ? 'th' : 'td' ), $column, ['data-swapi-tabularasa-parent' => $tag, 'data-swapi-tabularasa-unique' => $unique] );
                            }
                        }

                        $htmlrow .= (string) $thtml( 'tr', $htmlcolumn, ['data-swapi-tabularasa-parent' => $tag, 'data-swapi-tabularasa-unique' => $unique] );
                    }
                }

                $html .= (string) $thtml( $tag, $htmlrow, ['data-swapi-tabularasa-unique' => $unique] );
            }

            return (string) $html;
        };

        $unique = (string) $unique . '---' . swapi_generate_uniquestr(20);

        $html = (string) '';

        if ( !empty( $thead ) || !empty( $tbody ) || !empty( $tfoot ) ) {

            $html .= (string) $thtml( 'table', ( $tlevel( 'thead', $thead, $thtml, $unique ) . $tlevel( 'tbody', $tbody, $thtml, $unique ) . $tlevel( 'tfoot', $tfoot, $thtml, $unique ) ), ['data-swapi-tabularasa-unique' => $unique] );
        }

        return (string) $html;
    }

    
?>