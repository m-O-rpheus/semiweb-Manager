<?php


    // Prepare swapi mail: header + footer
    hook_into_swapi_array('swapi_mail_content', function( array $passthrough ) {

        $passthrough['return']['thead'] .= 'SWAPI HEADER';
        $passthrough['return']['tfoot'] .= 'SWAPI FOOTER';

        return (array) $passthrough;

    }, 1 );





    // Prepare swapi mail: basic
    hook_into_swapi_array('swapi_prepare_attribute_array', function( array $passthrough ) {


        // <body|article>
        if ( array_key_exists( 'class', $passthrough['return'] ) && is_array( $passthrough['return']['class'] ) && in_array( 'swapi_mail_main', $passthrough['return']['class'] ) ) {



            // --------------------------- general adjustments ---------------------------
            $body_bgcolor  = '#598217';     // #HEXHEX
            // --------------------------- general adjustments ---------------------------



            // Attribute width hier nicht erlaubt
            $passthrough['return']['bgcolor'] = $body_bgcolor;
            $passthrough['return']['style']   = ['background-color:'.$body_bgcolor.';', 'width:100%;', 'margin:0px;', 'border:none;', 'padding:0px;'];
        }


        // <table>
        else if ( array_key_exists( 'data-swapi-tabularasa-unique', $passthrough['return'] ) && str_starts_with( $passthrough['return']['data-swapi-tabularasa-unique'], '_SYSTEM_mail-') ) {



            // --------------------------- general adjustments ---------------------------
            if ( str_starts_with( $passthrough['return']['data-swapi-tabularasa-unique'], '_SYSTEM_mail-inner') ) {

                $table_bgcolor  = '#7bd1b1';     // #HEXHEX
                $table_color    = '#d17b88';     // #HEXHEX
                $table_width    = '640px';       // % or px
                $table_halign   = 'left';        // left|center|right|justify
                $table_valign   = 'top';         // top|middle|bottom|baseline
            }
            else {

                $table_bgcolor  = '#d5a286';     // #HEXHEX
                $table_color    = '#7b8fd1';     // #HEXHEX
                $table_width    = '100%';        // % or px
                $table_halign   = 'center';      // left|center|right|justify
                $table_valign   = 'middle';      // top|middle|bottom|baseline
            }
            // --------------------------- general adjustments ---------------------------



            $passthrough['return']['style'] = array();

            if ( in_array( 'swapi_tabularasa_th', $passthrough['return']['class'] ) || in_array( 'swapi_tabularasa_td', $passthrough['return']['class'] ) ) {

                $passthrough['return']['align']       = $table_halign;
                $passthrough['return']['valign']      = $table_valign;
                $passthrough['return']['color']       = $table_color;
                $passthrough['return']['style']       = array_merge( $passthrough['return']['style'], ['padding:0px;', 'text-align:'.$table_halign.';', 'vertical-align:'.$table_valign.';', 'color:'.$table_color.';'] );
            }

            if ( in_array( 'swapi_tabularasa_table', $passthrough['return']['class'] ) ) {

                $passthrough['return']['width']       = trim( $table_width, 'px' );
                $passthrough['return']['border']      = '0';
                $passthrough['return']['cellpadding'] = '0';
                $passthrough['return']['cellspacing'] = '0';
                $passthrough['return']['bgcolor']     = $table_bgcolor;
                $passthrough['return']['style']       = array_merge( $passthrough['return']['style'], ['width:'.$table_width.';', 'padding:0px;', 'border-spacing:0px;', 'table-layout:auto;', 'border-collapse:collapse;', 'background-color:'.$table_bgcolor.';', 'margin:auto;', 'border:none;'] );
            }
            else {

                $passthrough['return']['style']       = array_merge( $passthrough['return']['style'], ['margin:0px;', 'border:none;'] );
            }
        }

        return (array) $passthrough;

    }, 1 );





    // Clean swapi mail
    hook_into_swapi_array('swapi_prepare_attribute_array', function( array $passthrough ) {

        if ( array_key_exists( 'data-swapi-tabularasa-unique', $passthrough['return'] ) && str_starts_with( $passthrough['return']['data-swapi-tabularasa-unique'], '_SYSTEM_mail-') ) {

            if ( array_key_exists( 'data-swapi-tabularasa-parent', $passthrough['return'] ) ) {

                unset( $passthrough['return']['data-swapi-tabularasa-parent'] );
            }

            unset( $passthrough['return']['data-swapi-tabularasa-unique'] );

            ksort( $passthrough['return'] );  // Attribute aufsteigender Reihenfolge sortieren. Bugfix für Outlook und andere Mail Clients.


            // remove all Attribute from thead tbody tfoot - To remove the elements with str_replace - Because with html4 or E-Mails the tfoot has to come after the thead, but not with html5
            if ( array_key_exists( 'class', $passthrough['return'] ) ) {

                if (
                    in_array( 'swapi_tabularasa_thead', $passthrough['return']['class'] )
                    ||
                    in_array( 'swapi_tabularasa_tbody', $passthrough['return']['class'] )
                    ||
                    in_array( 'swapi_tabularasa_tfoot', $passthrough['return']['class'] )
                ) {
    
                    $passthrough['return'] = array();
                }
            }
        }

        return (array) $passthrough;

    }, 10000 );


?>