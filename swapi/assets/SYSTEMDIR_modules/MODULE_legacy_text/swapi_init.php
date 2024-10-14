<?php



/* Modul zur Textstrukturierung */

/*

    MODULE_legacy_text(array(

        [ 'p',          'represents a paragraph',                                                               {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],

        [ 'h1',         'heading1 are used to define HTML headings',                                            {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],
        [ 'h2',         'heading2 are used to define HTML headings',                                            {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],
        [ 'h3',         'heading3 are used to define HTML headings',                                            {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],
        [ 'h4',         'heading4 are used to define HTML headings',                                            {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],
        [ 'h5',         'heading5 are used to define HTML headings',                                            {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],
        [ 'h6',         'heading6 are used to define HTML headings',                                            {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],

        [ 'li',         'defines a list item'],
        [ 'code',       'tag is used to define a piece of computer code',                                       {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],
        [ 'adress',     'tag defines the contact information for the author/owner of a document or an article', {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],
        [ 'blockquote', 'indicates that the enclosed text is an extended quotation',                            {OPTIONAL}array('class' => 'test', 'data-custom' => 'custom attribute') ],

    ), wrap(default)|unorderedlist|orderedlist|none );

*/

/* ========================================================================================================================================================== */

    function MODULE_legacy_text( array $alltextsarr, string $wrapper = 'wrap' ): string {

        $html = (string) '';

        if ( is_array( $alltextsarr ) && !empty( $alltextsarr ) ) {

            foreach ( $alltextsarr as $textarr ) {

                if ( is_array( $textarr ) && count( $textarr ) > 1 && is_string( $textarr[0] ) && is_string( $textarr[1] ) ) {  // count length. min 2

                    $custom_attribute = (array) (( count( $textarr ) > 2 && is_array( $textarr[2] ) ) ? $textarr[2] : []);      // count length. min 3


                    // Paragraph
                    // ---------
                    if ( $textarr[0] === 'p' || $textarr[0] === 'paragraph' ) {

                        $html .= (string) '<p' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_p']], $custom_attribute ) ) . '>' . $textarr[1] . '</p>';
                    }


                    // Heading 1-6
                    // -----------
                    else if ( $textarr[0] === 'h1' || $textarr[0] === 'heading1' ) {

                        $html .= (string) '<h1' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_h', 'swapi_h1']], $custom_attribute ) ) . '>' . $textarr[1] . '</h1>';
                    }
                    else if ( $textarr[0] === 'h2' || $textarr[0] === 'heading2' ) {

                        $html .= (string) '<h2' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_h', 'swapi_h2']], $custom_attribute ) ) . '>' . $textarr[1] . '</h2>';
                    }
                    else if ( $textarr[0] === 'h3' || $textarr[0] === 'heading3' ) {

                        $html .= (string) '<h3' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_h', 'swapi_h3']], $custom_attribute ) ) . '>' . $textarr[1] . '</h3>';
                    }
                    else if ( $textarr[0] === 'h4' || $textarr[0] === 'heading4' ) {

                        $html .= (string) '<h4' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_h', 'swapi_h4']], $custom_attribute ) ) . '>' . $textarr[1] . '</h4>';
                    }
                    else if ( $textarr[0] === 'h5' || $textarr[0] === 'heading5' ) {

                        $html .= (string) '<h5' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_h', 'swapi_h5']], $custom_attribute ) ) . '>' . $textarr[1] . '</h5>';
                    }
                    else if ( $textarr[0] === 'h6' || $textarr[0] === 'heading6' ) {

                        $html .= (string) '<h6' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_h', 'swapi_h6']], $custom_attribute ) ) . '>' . $textarr[1] . '</h6>';
                    }


                    // Diverse
                    // -------
                    else if ( $textarr[0] === 'li' ) {

                        $html .= (string) '<li' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_li']], $custom_attribute ) ) . '>' . $textarr[1] . '</li>';
                    }
                    else if ( $textarr[0] === 'code' ) {

                        $html .= (string) '<code' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_code']], $custom_attribute ) ) . '>' . $textarr[1] . '</code>';
                    }
                    else if ( $textarr[0] === 'adress' ) {

                        $html .= (string) '<adress' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_adress']], $custom_attribute ) ) . '>' . $textarr[1] . '</adress>';
                    }
                    else if ( $textarr[0] === 'blockquote' ) {

                        $html .= (string) '<blockquote' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_text', 'swapi_blockquote']], $custom_attribute ) ) . '>' . $textarr[1] . '</blockquote>';
                    }


                }
                else {

                    $html .= (string) swapi_ERRORMSG( '[MODULE_legacy_text] Array Error!' );
                }
            }
        }


        if ( !empty( $html ) ) {


            // Wrapper
            // -------
            if ( $wrapper === 'wrap' ) {

                $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_text_wrap']] ) . '>' . $html . '</div>';
            }
            else if ( $wrapper === 'unorderedlist' ) {

                $html = (string) '<ul' . swapi_prepare_attribute( ['class' => ['swapi_text_unorderedlist']] ) . '>' . $html . '</ul>';
            }
            else if ( $wrapper === 'orderedlist' ) {

                $html = (string) '<ol' . swapi_prepare_attribute( ['class' => ['swapi_text_orderedlist']] ) . '>' . $html . '</ol>';
            }

            // none = No Wrapper


            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_legacy_text');
        }
        else {

            $html .= (string) swapi_ERRORMSG( '[MODULE_legacy_text] General Error!' );
        }
        

        return (string) $html;
    }

    
?>