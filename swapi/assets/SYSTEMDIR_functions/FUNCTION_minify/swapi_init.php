<?php



/* Javascript Minifizieren */
/* ========================================================================================================================================================== */

    function mj_minify_javascript( string $str ): string {

        require_once swapi_paths()['PATH_dir_frameworks_root'] . 'JShrink' . DIRECTORY_SEPARATOR . 'Minifier.php';

        $str = trim( \JShrink\Minifier::minify( $str ) );

        return (string) $str;
    }



    function mj_minify_swapi_system_javascript( string $str ): string {

        // Globale Konstanten
        $str = str_replace( 'mjv_global_swapi_root'                                     , 'Ga', $str );
        $str = str_replace( 'mjv_global_customerpage'                                   , 'Gb', $str );
        $str = str_replace( 'mjv_global_queryname'                                      , 'Gc', $str );
        $str = str_replace( 'mjv_global_selfuri'                                        , 'Gd', $str );
        $str = str_replace( 'mjv_global_urlquery'                                       , 'Ge', $str );
        $str = str_replace( 'mjv_global_classname_swapi_section'                        , 'Gf', $str );
        $str = str_replace( 'mjv_global_initial_session'                                , 'Gg', $str );
       

        // Annonyme Funktionsnamen - diese beginnen immer mit 'func_' oder mit 'mj_' ( Hinweis e,i,x darf nicht benutzt werden + immer kleingeschrieben )
        $str = str_replace( 'mj_aesGCM_encrypt_v5'                                      , 'a', $str );
        $str = str_replace( 'mj_aesGCM_decrypt_v5'                                      , 'b', $str );
        $str = str_replace( 'func_set_or_remove_browserstorage'                         , 'c', $str );
        $str = str_replace( 'func_get_browserstorage'                                   , 'd', $str );
        $str = str_replace( 'func_helper_stringSelectorAll_removeAll'                   , 'f', $str );
        $str = str_replace( 'func_set_swapi_complete'                                   , 'g', $str );
        $str = str_replace( 'func_script_start'                                         , 'h', $str );
        $str = str_replace( 'func_async_input_files_loop'                               , 'j', $str );
        $str = str_replace( 'func_swapiCORS_send_mj_encrypt'                            , 'k', $str );
        $str = str_replace( 'func_swapiCORScbLazyLoad_1_waitloopAndOpen'                , 'l', $str );
        $str = str_replace( 'func_swapiCORScbLazyLoad_2_executeAndOpen'                 , 'm', $str );
        $str = str_replace( 'func_swapiCORScbLazyLoad_3_event'                          , 'n', $str );
        $str = str_replace( 'func_swapiCORScb'                                          , 'o', $str );  // Muss per Minify nach 'func_swapiCORScb...' folgen!
        $str = str_replace( 'func_override_browserstorage_and_actual_session_if_exists' , 'p', $str );
        $str = str_replace( 'func_error_handling'                                       , 'q', $str );
        $str = str_replace( 'func_helper_selectorIsSelfOrChildren'                      , 'r', $str );
        $str = str_replace( 'func_swapi_timeout'                                        , 's', $str );
        $str = str_replace( 'func_obj_hasKey_and_valueHasType'                          , 't', $str );
      

        // Variablen Namen und Parameter - diese beginnen immer mit 'mjv_' ( Hinweis E, darf nicht benutzt werden + immer großgeschrieben )  
        $str = str_replace( 'mjv_swapi_tag'                                             , 'A', $str );
        $str = str_replace( 'mjv_aesGCM_compression_stream_support'                     , 'B', $str );
        $str = str_replace( 'mjv_LLcbPage'                                              , 'C', $str );
        $str = str_replace( 'mjv_callTypePassthroughBoolean'                            , 'D', $str );
        $str = str_replace( 'mjv_elem'                                                  , 'F', $str );
        $str = str_replace( 'mjv_arr'                                                   , 'G', $str );
        $str = str_replace( 'mjv_response'                                              , 'H', $str );   
        $str = str_replace( 'mjv_str'                                                   , 'I', $str );
        $str = str_replace( 'mjv_all_files'                                             , 'J', $str );
        $str = str_replace( 'mjv_obj'                                                   , 'K', $str );
        $str = str_replace( 'mjv_callback_without_error'                                , 'L', $str );
        $str = str_replace( 'mjv_sel'                                                   , 'M', $str );
        $str = str_replace( 'mjv_actual_session'                                        , 'N', $str );
        $str = str_replace( 'swapinode'                                                 , 'O', $str );  


        // mj_aesGCM_..._v5
        $str = str_replace( 'mjv_password'                                              , 'P', $str );
        $str = str_replace( 'mjv_callback'                                              , 'Q', $str );
        $str = str_replace( 'mjv_cipherblob'                                            , 'R', $str );


        // Erweitert
        $str = str_replace( 'mjv_CORScbLazyLoadSpeicher'                                , 'S', $str );
        $str = str_replace( 'mjv_CORScbLoadCounter'                                     , 'T', $str );
        $str = str_replace( 'mjv_swapi_script'                                          , 'U', $str );

        
        // Events und Event IF
        $str = str_replace( 'mjv_ev_swapi_ready_if_full_request_arrived'                , 'V', $str );  
        $str = str_replace( 'mjv_ev'                                                    , 'W', $str );  
        $str = str_replace( 'mjv_if_before'                                             , 'X', $str );
        $str = str_replace( 'mjv_if_after'                                              , 'Y', $str );



        // function() in arrow function umstellen!
        // $str = preg_replace_callback('/function\((.*?)\)\{/s', function ($func) { return '('.$func[1].')=>{'; }, $str );


        return (string) $str;
    }





/* HTML Minifizieren - Update 14.10.2021 - Verwende hier nur einen kleinen aber effizienten Minifizierer. Wichtig: Lieber weniger effizient, dafür die Komptibilität besser */
/* ========================================================================================================================================================== */

    function mj_minify_html( string $str ): string {


        // Mehrere Leerzeichen sowie Zeilenumbrüche im HTML Element <code> berücksichtigen - 14.10.2021
        $tag = 'code';

        $str = (string) preg_replace_callback('/\<'.$tag.'([^\>]*?)\>((?!\<\/'.$tag.'\>).*?)\<\/'.$tag.'\>/s', function ($value) use (&$tag) {

            return '<'.$tag.$value[1].'>'.nl2br(str_replace(' ', '&#32;', $value[2]), false).'</'.$tag.'>';     // Leerzeichen durch HTML Code &#32; ersetzen, Zeilenumbruch durch <br>

        }, $str);

        

        // Mehrere Leerzeichen sowie Zeilenumbrüche im HTML Element <pre> berücksichtigen - 14.10.2021
        $tag = 'pre';

        $str = (string) preg_replace_callback('/\<'.$tag.'([^\>]*?)\>((?!\<\/'.$tag.'\>).*?)\<\/'.$tag.'\>/s', function ($value) use (&$tag) {

            return '<'.$tag.$value[1].'>'.nl2br(str_replace(' ', '&#32;', $value[2]), false).'</'.$tag.'>';     // Leerzeichen durch HTML Code &#32; ersetzen, Zeilenumbruch durch <br>

        }, $str);



        $str = (string) preg_replace('/\<\!\-\-((?!\-\-\>).)*\-\-\>/s', '', $str);  // 14.10.2021 - Entferne alle HTML Kommentare <!-- --> aus dem string mit Negative lookahead. Der Modifikator /s aktiviert ein mehrzeiliges Ersetzen.
        $str = (string) preg_replace('/\s+/', ' ', $str);                           // 13.10.2021 - Dies bedeutet, dass Leerzeichen, Tabulatoren oder Zeilenumbrüche (ein oder mehrere) durch ein einzelnes Leerzeichen ersetzt werden.
        $str = (string) preg_replace('/\s*([<>])\s*/', '$1', $str);                 // 14.10.2021 - Entferne Leerzeichen vor und nach folgenden Zeichen < >.
        
        $str = (string) str_replace('"', "'", $str);                                // 14.10.2021 - Da JSON das doppelte Hochkomma bereits nutzt und es somit Escaped werden muss, ersetzte es durch ein einfaches Hochkomma
        $str = (string) str_replace('<br />', '<br>', $str);                        // 14.10.2021 - Line Breaks nach HTML5 Standard ohne Slash am Ende


        
        $str = str_replace(['ä','ö','ü','Ä','Ö','Ü','ß','€'], ['&auml;','&ouml;','&uuml;','&Auml;','&Ouml;','&Uuml;','&szlig;','&euro;'], $str);    // ALT



        return (string) trim( $str );
    }





/* CSS Minifizieren - Update 14.10.2021 - Verwende hier nur einen kleinen aber effizienten Minifizierer. Wichtig: Lieber weniger effizient, dafür die Komptibilität besser */
/* ========================================================================================================================================================== */

    function mj_minify_css( string $str ): string {

        $str = (string) preg_replace('/\/\*((?!\*\/).)*\*\//s', '', $str);  // 14.10.2021 - Entferne alle CSS Kommentare /* */ aus dem string mit Negative lookahead. Der Modifikator /s aktiviert ein mehrzeiliges Ersetzen.
        $str = (string) preg_replace('/\s+/', ' ', $str);                   // 13.10.2021 - Dies bedeutet, dass Leerzeichen, Tabulatoren oder Zeilenumbrüche (ein oder mehrere) durch ein einzelnes Leerzeichen ersetzt werden.
        $str = (string) preg_replace('/\s*([{};>~])\s*/', '$1', $str);      // 14.10.2021 - Entferne Leerzeichen vor und nach folgenden Zeichen { } ; > ~ Die Wahrscheinlichkeit das diese auch innerhalb einer CSS string Eingeschaft verwendet werden, ist relativ gering.

        $str = (string) str_replace('"', "'", $str);                        // 14.10.2021 - Da JSON das doppelte Hochkomma bereits nutzt und es somit Escaped werden muss, ersetzte es durch ein einfaches Hochkomma

        return (string) trim( $str );
    }



?>