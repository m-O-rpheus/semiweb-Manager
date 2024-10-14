<?php



/* Erzeugt ein Default Inputfeld */

/*

    MODULE_field_default(array(

        [ 'type' => 'text', 'label' => 'Field 1', 'description' => 'Description for Field 1', '...' ],
        [ 'type' => 'file', 'label' => 'Field 2', 'description' => 'Description for Field 2', '...' ],

    ), none(default)|wrap|dropdown );


    Hinweis: Über einen Funktionsaufruf können somit mehrere Inputfelder ausgegeben werden!
    Hinweis: Über den zweiten Funktionsparameter kann ein Optionaler Wrapper bestimmt werden! Mögliche werte sind none, wrap, dropdown





    Erforderlich:
    -------------
    ATTRIBUTE: type                 text   email   number   range   tel   url   search   password   textarea   radio   checkbox   file


    Optional:
    ---------
    ATTRIBUTE: accept               -      -       -        -       -     -     -        -          -          -       -          file      (Durch Komma , getrennte MIME Liste Beispiel: 'image/jpeg,image/png' https://wiki.selfhtml.org/wiki/MIME-Type/%C3%9Cbersicht) 
    ATTRIBUTE: autocomplete         text   email   -        -       tel   url   search   password   -          -       -          -
    ATTRIBUTE: min                  -      -       number   range   -     -     -        -          -          -       -          -
    ATTRIBUTE: max                  -      -       number   range   -     -     -        -          -          -       -          -
    ATTRIBUTE: step                 -      -       number   range   -     -     -        -          -          -       -          -
    ATTRIBUTE: minlength            text   email   -        -       tel   url   search   password   textarea   -       -          -
    ATTRIBUTE: maxlength            text   email   -        -       tel   url   search   password   textarea   -       -          -
    ATTRIBUTE: pattern              text   email   -        -       tel   url   search   password   -          -       -          -
    ATTRIBUTE: placeholder          text   email   number   -       tel   url   search   password   textarea   -       -          -
    ATTRIBUTE: name                 text   email   number   range   tel   url   search   password   textarea   radio   checkbox   file      (Nur sinnvoll bei radio)
    ATTRIBUTE: description          text   email   number   range   tel   url   search   password   textarea   radio   checkbox   file      (It is set as an attribute, but treated separately and removed from the array)
    ATTRIBUTE: label                text   email   number   range   tel   url   search   password   textarea   radio   checkbox   file      (It is set as an attribute, but treated separately and removed from the array)
    ATTRIBUTE: data-*               text   email   number   range   tel   url   search   password   textarea   radio   checkbox   file

    ATTRIBUTE: value                text   email   number   range   tel   url   search   -          textarea   radio   checkbox   -         (It is set as an attribute, but treated separately and removed from the array)
                :.. Das Eingabefeld mit einem Wert vorbelegen.
                :.. type 'password':         value wird immer als leer gesetzt.
                :.. type 'file':             value ist nie gesetzt.
                :.. type 'radio' 'checkbox': value ist nie gesetzt, stattdessen wird bei 'checked' oder 'true' das Attribute 'checked' gesetzt.
                

    Boolean Attribute:
    ------------------
    ATTRIBUTE: multiple             -      -       -        -       -     -     -        -          -          -       -          file 
    ATTRIBUTE: readonly             text   email   number   -       tel   url   search   password   textarea   -       -          -
    ATTRIBUTE: required             text   email   number   range   tel   url   search   password   -          radio   checkbox   file
    ATTRIBUTE: hidden               text   email   number   range   tel   url   search   password   textarea   radio   checkbox   file

*/

/* ========================================================================================================================================================== */

    function MODULE_field_default( array $allinputsarr, string $wrapper = 'none' ): string {

        $module_dir = (string) dirname(__FILE__, 1) . DIRECTORY_SEPARATOR;

        $texts = array(
            'input_dropdown_text_initial' => 'choose',
            'input_dropdown_text_before'  => 'currently ',
            'input_dropdown_text_after'   => ' chosen',
            'input_file_text_initial'     => 'choose a file',
            'input_file_text_before'      => 'currently ',
            'input_file_text_after'       => ' files are selected',
        );

        /* hook */ $allinputsarr = (array) register_swapi_hook_point_passthrough_array( 'swapi_module_swapi_field_default_fields', ['return' => $allinputsarr, 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return', 'READONLY_wrapper' => $wrapper] )['return'];
        /* hook */ $texts        = (array) register_swapi_hook_point_passthrough_array( 'swapi_module_swapi_field_default_texts',  ['return' => $texts,        'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return', 'READONLY_wrapper' => $wrapper, 'READONLY_attribute' => $allinputsarr] )['return'];



        $str_html     = (string) '';
        $str_enqueue  = (string) '';
        $assign_types = (array)  array();  // generate a list of all input types

        if ( is_array( $allinputsarr ) && !empty( $allinputsarr ) ) {

            $is_enqueue_range = (bool) false;
            $is_enqueue_file  = (bool) false;

            foreach ( $allinputsarr as $assign_attr ) {

                if ( is_array( $assign_attr ) && !empty( $assign_attr ) ) {



                    // # Prepare - Add type if absent or invalid to prepared attribute array!
                    // ----------------------------------------------------------------------
                    if ( array_key_exists( 'type', $assign_attr ) && is_string( $assign_attr['type'] ) && in_array( $assign_attr['type'], array( 'text', 'email', 'number', 'range', 'tel', 'url', 'search', 'password', 'textarea', 'radio', 'checkbox', 'file' ) ) ) {

                        $assign_attr['type'] = (string) $assign_attr['type'];
                    }
                    else {

                        $assign_attr['type'] = (string) 'text';
                    }

                    $assign_types[$assign_attr['type']] = (string) 'swapi_has_type_' . $assign_attr['type'];



                    // # Special if attribute 'label' exists! - Write it into a new variable and delete it from the array + append aria-label and required!
                    // ------------------------------------------------------------------------------------------------------------------------------------
                    $labels = (array) array( '', '' );

                    if ( array_key_exists( 'label', $assign_attr ) ) {

                        if ( is_string( $assign_attr['label'] ) ) {

                            $labels[0] .= (string) htmlspecialchars( nl2br( $assign_attr['label'], false ), ENT_QUOTES );
                            $labels[1] .= (string) $labels[0];
                        }

                        unset( $assign_attr['label'] );
                    }

                    if ( array_key_exists( 'required', $assign_attr ) && in_array( $assign_attr['type'], array( 'text', 'email', 'number', 'range', 'tel', 'url', 'search', 'password', 'radio', 'checkbox', 'file' ) ) ) {
    
                        $labels[0] .= (string) swapi_required();
                        $labels[1] .= (string) strip_tags( swapi_required() );
                    }

                    $assign_label = (string) '';

                    if ( !empty( $labels[0] ) && !empty( $labels[1] ) ) {

                        $assign_label              = (string) '<span' . swapi_prepare_attribute( ['class' => ['swapi_label']] ) . '>' . $labels[0] . '</span>';
                        $assign_attr['aria-label'] = (string) $labels[1];
                    }



                    // # Special if attribute 'value' exists! - Write it into a new variable and delete it from the array!
                    // ---------------------------------------------------------------------------------------------------
                    $assign_value = (string) '';

                    if ( array_key_exists( 'value', $assign_attr ) ) {

                        if ( is_string( $assign_attr['value'] ) ) {

                            if ( in_array( $assign_attr['type'], array( 'text', 'email', 'number', 'range', 'tel', 'url', 'search' ) ) ) {

                                // 1. Line Breaks zu <br>, 2. HTML unschädlich machen - Line Breaks sind bei diesen Elementen nicht erlaubt!
                                $assign_value = (string) htmlspecialchars( nl2br( $assign_attr['value'], false ), ENT_QUOTES );
                            }
                            else if ( in_array( $assign_attr['type'], array( 'textarea' ) ) ) {
    
                                // 1. Line Breaks zu <br>, 2. HTML unschädlich machen, 3. <br> (auch durch User eingegebene) zu HTML backspace carriage return - Line Breaks sind hier erlaubt!
                                $assign_value = (string) str_replace( ['&lt;br&gt;','&lt;br/&gt;','&lt;br /&gt;'], '&#013;&#010;', htmlspecialchars( nl2br( $assign_attr['value'], false ), ENT_QUOTES ) );
                            }
                            else if ( in_array( $assign_attr['type'], array( 'radio', 'checkbox' ) ) ) {
    
                                if ( strtolower( trim( $assign_attr['value'] ) ) === 'checked' || strtolower( trim( $assign_attr['value'] ) ) === 'true' ) {
        
                                    $assign_value = (string) ' checked';
                                }
                            }
                        }

                        unset( $assign_attr['value'] );
                    }



                    // # Special if attribute 'description' exists! - Write it into a new variable and delete it from the array!
                    // ---------------------------------------------------------------------------------------------------------
                    $assign_description = (array) array();

                    if ( array_key_exists( 'description', $assign_attr ) ) {

                        $assign_description['data-swapi-description'] = (string) $assign_attr['description'];

                        unset( $assign_attr['description'] );
                    }



                    // # Special if attribute 'hidden' exists! - Write it into a new variable and delete it from the array!
                    // ----------------------------------------------------------------------------------------------------
                    $assign_hidden = (array) array();

                    if ( array_key_exists( 'hidden', $assign_attr ) ) {

                        $assign_hidden['hidden'] = (string) $assign_attr['hidden'];

                        unset( $assign_attr['hidden'] );
                    }





                    if ( in_array( $assign_attr['type'], array('text', 'email', 'number', 'tel', 'url', 'search', 'password') ) ) {

                        $str_input = (string) '<label' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_label_for_input', 'swapi_label_for_input_textline', 'swapi_label_for_input_'.$assign_attr['type']]], $assign_description, $assign_hidden ) ) . '>' . $assign_label . '<input' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_field', 'swapi_field_textline', 'swapi_field_' . $assign_attr['type']]], $assign_attr ) ) . ' value="' . $assign_value . '"></label>';
                    }
                    else if ( in_array( $assign_attr['type'], array('textarea') ) ) {

                        $str_input = (string) '<label' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_label_for_input', 'swapi_label_for_input_textline', 'swapi_label_for_input_'.$assign_attr['type']]], $assign_description, $assign_hidden ) ) . '>' . $assign_label . '<textarea' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_field', 'swapi_field_textline', 'swapi_field_' . $assign_attr['type']]], $assign_attr ) ) . '>' . $assign_value . '</textarea></label>';
                    }
                    else if ( in_array( $assign_attr['type'], array('range') ) ) {

                        $str_input = (string) '<label' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_label_for_input', 'swapi_label_for_input_slide', 'swapi_label_for_input_'.$assign_attr['type']]], $assign_description, $assign_hidden ) ) . '>' . $assign_label . '<input' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_field', 'swapi_field_slide', 'swapi_field_' . $assign_attr['type']]], $assign_attr ) ) . ' value="' . $assign_value . '"></label>';
                    }
                    else if ( in_array( $assign_attr['type'], array('file') ) ) {

                        $str_input = (string) '<label' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_label_for_input', 'swapi_label_for_input_files', 'swapi_label_for_input_'.$assign_attr['type']]], $assign_description, $assign_hidden ) ) . '>' . $assign_label . '<input' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_field', 'swapi_field_files', 'swapi_field_' . $assign_attr['type']]], $assign_attr ) ) . '><span' . swapi_prepare_attribute( ['class' => ['swapi_field', 'swapi_field_files', 'swapi_field_'.$assign_attr['type']], 'data-swapi-before' => $texts['input_file_text_before'], 'data-swapi-after' => $texts['input_file_text_after']] ) . '>' . $texts['input_file_text_initial'] . '</span></label>';
                    }
                    else if ( in_array( $assign_attr['type'], array('radio', 'checkbox') ) ) {

                        $str_input = (string) '<label' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_label_for_input', 'swapi_label_for_input_selection', 'swapi_label_for_input_'.$assign_attr['type']]], $assign_description, $assign_hidden ) ) . '><input' . swapi_prepare_attribute( array_merge_recursive( ['class' => ['swapi_field', 'swapi_field_selection', 'swapi_field_' . $assign_attr['type']]], $assign_attr ) ) . $assign_value . '>' . $assign_label . '</label>';
                    }
                    else {

                        return (string) swapi_ERRORMSG( '[MODULE_field_default] Invalid form field defined!' );
                    }
       
                    /* hook */ $str_html .= (string) register_swapi_hook_point_passthrough_array( 'swapi_module_swapi_field_default_inputloop', ['return' => $str_input, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'return', 'READONLY_attribute' => $assign_attr, 'READONLY_wrapper' => $wrapper] )['return'];



                    // Load at 2: special_adjustment <style> AND <script>
                    if ( in_array( $assign_attr['type'], array('range') ) && $is_enqueue_range === false ) { $is_enqueue_range = (bool) true;

                        $str_enqueue .= (string) swapi_enqueue_style([ $module_dir . 'css_load_all_special_adjustment_range' ], 'MODULE_field_default_special_adjustment_range');

                    }
                    else if ( in_array( $assign_attr['type'], array('file') ) && $is_enqueue_file === false ) { $is_enqueue_file = (bool) true;

                        $str_enqueue .= (string) swapi_enqueue_style([ $module_dir . 'css_load_all_special_adjustment_file' ], 'MODULE_field_default_special_adjustment_file');
                        $str_enqueue .= (string) swapi_enqueue_script([ $module_dir . 'js_load_all_special_adjustment_file' ], 'MODULE_field_default_special_adjustment_file');

                    }



                }
            }
        }



        if ( !empty( $str_html ) ) {



            // Load at 1: default <style> (prepend to final output)
            $str_enqueue = (string) swapi_enqueue_style([ $module_dir . 'css_load_all_default' ], 'MODULE_field_default') . $str_enqueue;



            // Wrapper (wrap|dropdown) for input fields
            if ( $wrapper === 'wrap' ) {

                $str_html = (string) '<div' . swapi_prepare_attribute( ['class' => array_merge(['swapi_input_wrapper_wrap', 'swapi_count_types_'.count($assign_types)], array_values($assign_types))] ) . '>' . $str_html . '</div>';
            }
            else if ( $wrapper === 'dropdown' ) {

                // data-swapi-dropdown-direction   down|up
                $str_html = (string) '<details' . swapi_prepare_attribute( ['class' => array_merge(['swapi_input_wrapper_dropdown', 'swapi_count_types_'.count($assign_types)], array_values($assign_types)), 'data-swapi-dropdown-direction' => 'down'] ) . '><summary' . swapi_prepare_attribute( ['data-swapi-before' => $texts['input_dropdown_text_before'], 'data-swapi-after' => $texts['input_dropdown_text_after']] ) . '>' . $texts['input_dropdown_text_initial'] . '</summary><div>' . $str_html . '</div></details>';


                // Load at 3: special_adjustment <style> AND <script> (append to final output)
                $str_enqueue .= (string) swapi_enqueue_style([ $module_dir . 'css_load_all_special_adjustment_dropdown' ], 'MODULE_field_default_special_adjustment_dropdown');
                $str_enqueue .= (string) swapi_enqueue_script([ $module_dir . 'js_load_all_special_adjustment_dropdown' ], 'MODULE_field_default_special_adjustment_dropdown');
            }

            

            /* hook */ $str_html = (string) register_swapi_hook_point_passthrough_array( 'swapi_module_swapi_field_default_container', ['return' => $str_html, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'return', 'READONLY_attribute' => $allinputsarr, 'READONLY_wrapper' => $wrapper] )['return'];

            return (string) $str_html . $str_enqueue;
        }
        else {

            return (string) swapi_ERRORMSG( '[MODULE_field_default] General Error!' );
        }
    }

    
?>