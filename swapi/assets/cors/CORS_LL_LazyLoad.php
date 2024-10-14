<?php

/* CORS_LazyLoad */
/* ========================================================================================================================================================== */

function CORS_LL_LazyLoad( array $array_object ): array {


    /* Nachdem in der Datei 'CORS.php' alles Valide ist, Springe HIER her! Führe weitere Validierungen durch. Speziell LazyLoad */
    /* ========================================================================================================================================================== */


    if ( is_string( $array_object['LLsPage'] ) && !empty( $array_object['LLsPage'] ) && swapi_validate_minimalist( $array_object['LLsPage'] ) ) {
    

        // ----------------------------- Hilfsvariable bereitstellen! -----------------------------
        $GLOBALS['GLOBAL_basename'] = (string) $array_object['LLsPage'];
        $swapi_template_html        = (string) ( ( $array_object['LLsPage'] === GLOBAL_CORE_SWAPIROOT ) ? 'swapi_ROOT_shadow' : 'swapi_SECTION_shadow' ); // NICHT ÄNDERN!!!
        $swapi_core_shadow_style    = (string) 'swapi_CORE_shadow_STYLE'; // NICHT ÄNDERN!!!



        // ----------------------------- ContentIdentifier: '_SWAPI_root' -----------------------------
        if ( $array_object['LLsPage'] === GLOBAL_CORE_SWAPIROOT ) {

            $root_content_string = (string) register_swapi_hook_point_passthrough_array( 'swapi_customer_template_html_inside_swapi_root_container', ['html' => '', 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'html', 'READONLY_customer' => $GLOBALS['GLOBAL_customer'], 'READONLY_contentidentifier' => $array_object['LLsPage']] )['html'];

            if ( is_string( $root_content_string ) ) {

                $core_style = swapi_enqueue_style([ swapi_paths()['PATH_dir_css-js_once_root'] . 'root_style.css' ], $swapi_core_shadow_style);

                $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_root']] ) . '>' . $core_style . $root_content_string . '</div>';   // Zuerst $core_style, dann der rest. Verhindert das HTML Schreibfehler bei <style></style> das root_style.css zerschießen!
            }
            else {

                $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_root']] ) . '>' . swapi_ERRORMSG( '[CORS_LL_LazyLoad] &apos;swapi_customer_template_html_inside_swapi_root_container&apos; return is not an string!' ) . '</div>';
            }
        }

        

        // ----------------------------- ContentIdentifier: irgendwas anderes als '_SWAPI_root' -----------------------------
        else {

            $section_content_array = (array) register_swapi_hook_point_passthrough_array( 'swapi_customer_template_html_inside_swapi_section_container', ['html' => array(), 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'html', 'READONLY_customer' => $GLOBALS['GLOBAL_customer'], 'READONLY_contentidentifier' => $array_object['LLsPage']] )['html'];

            if ( is_array( $section_content_array ) ) {

                if ( !empty( $section_content_array ) ) {

                    $loop_html  = '';
    
                    foreach( $section_content_array as $pagename => $content ) {
    
                        $loop_html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_scroll'], 'data-swapi-scroll-page' => $pagename] ) . '><div' . swapi_prepare_attribute( ['class' => ['swapi_flow']] ) . '>' . $content . '</div></div>';
                    }
                    
                    $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_section', (php_session_is_logged_in() ? 'swapi_is_loggedin' : 'swapi_is_loggedout')], 'style' => ['--swapi-count-initial:'.count( $section_content_array ).';'], 'data-swapi-page' => $array_object['LLsPage'] ] ) . '>' . $loop_html . '</div>';
                }
                else {

                    $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_section']] ) . '>' . swapi_ERRORMSG( '[CORS_LL_LazyLoad] &apos;swapi_customer_template_html_inside_swapi_section_container&apos; return is an empty array!' ) . '</div>';
                }
            }
            else {

                $html = '<div' . swapi_prepare_attribute( ['class' => ['swapi_section']] ) . '>' . swapi_ERRORMSG( '[CORS_LL_LazyLoad] &apos;swapi_customer_template_html_inside_swapi_section_container&apos; return is not an array!' ) . '</div>';
            }
        }



        // ----------------------------- 1. Extrahiere <script*>*</script> vom HTML -----------------------------
        if ( !array_key_exists( 'js_once', $_SESSION['core_session_datastorage_between_reloads'] ) ) {          // Notwendig um doppelte <script> über Aufrufe hinweg zu vermeiden!

            $_SESSION['core_session_datastorage_between_reloads']['js_once'] = array();
        }

        $scripts = array();

        if( strpos( $html, '</script>') !== false ) {

            $html = preg_replace_callback('/<script([^>]*?)>(.*?)<\/script>/s', function ($content) use (&$scripts, &$swapi_template_html) {
        
                $tagattr = trim( strval( $content[1] ) );
                $content = mj_minify_javascript( $content[2] );
            
                if ( !empty( $content ) ) {                                                                         // Leere <script> ignorieren
                    
                    $key = $swapi_template_html . '_SCRIPT_' . md5($content);
    
                    if ( !in_array( $key, $_SESSION['core_session_datastorage_between_reloads']['js_once'] ) ) {    // Notwendig um doppelte <script> über Aufrufe hinweg zu vermeiden!
    
                        $_SESSION['core_session_datastorage_between_reloads']['js_once'][] = $key;
    
                        $content = (string) register_swapi_hook_point_passthrough_array( 'swapi_customer_script_after_minify', ['javascript' => $content, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'javascript', 'READONLY_customer' => $GLOBALS['GLOBAL_customer'], 'READONLY_tagid' => $key, 'READONLY_tagattribute' => $tagattr ] )['javascript'];
    
                        if ( !empty( $content ) ) {                                                                 // Falls im Hook als Leer definiert, gebe nichts aus!
    
                            $content = '(function(){"use strict"; function swapinode(){return document.querySelector(\''.$_SESSION['core_session_tagname'].'\').shadowRoot.querySelector(\'.swapi\');}function swapiready(){if(swapinode().classList.contains(\'swapi_ready\')){try {' . $content . '}catch(e){return\'\';}}else{setTimeout(function(){swapiready();},2);}}swapiready();})();';
    
                            if ( !in_array( $content, $scripts ) ) {                                                // Falls durch den Hook oder 'swapi_ROOT_shadow' doppelter 'Content' vorhanden sein sollte ignorieren!
    
                                // RÜCKGABE
                                $scripts[$key] = $content;
                            }
                        }
                    }
                }
                
            return ''; }, $html );
        }



        // ----------------------------- 2. Extrahiere <style*>*</style> vom HTML -----------------------------
        if ( !array_key_exists( 'css_once', $_SESSION['core_session_datastorage_between_reloads'] ) ) {         // Notwendig um doppelte <style> über Aufrufe hinweg zu vermeiden!

            $_SESSION['core_session_datastorage_between_reloads']['css_once'] = array();
        }

        $styles = array();

        if( strpos( $html, '</style>') !== false ) {

            $html = preg_replace_callback('/<style([^>]*?)>(.*?)<\/style>/s', function ($content) use (&$styles, &$swapi_template_html, &$swapi_core_shadow_style) {
            
                $tagattr = trim( strval( $content[1] ) );
                $content = mj_minify_css( $content[2] );
            
                if ( !empty( $content ) ) {                                                                         // Leere <style> ignorieren

                    $key = ( ( $tagattr !== $swapi_core_shadow_style ) ? $swapi_template_html . '_STYLE_' . md5($content) : $swapi_core_shadow_style );

                    if ( !in_array( $key, $_SESSION['core_session_datastorage_between_reloads']['css_once'] ) ) {   // Notwendig um doppelte <style> über Aufrufe hinweg zu vermeiden!

                        $_SESSION['core_session_datastorage_between_reloads']['css_once'][] = $key;

                        if ( $tagattr !== $swapi_core_shadow_style ) {  // Hook bei '$swapi_core_shadow_style' deaktivieren

                            $content = (string) register_swapi_hook_point_passthrough_array( 'swapi_customer_style_after_minify', ['css' => $content, 'READONLY_return_type' => 'string', 'READONLY_return_array_key' => 'css', 'READONLY_customer' => $GLOBALS['GLOBAL_customer'], 'READONLY_tagid' => $key, 'READONLY_tagattribute' => $tagattr ] )['css'];
                        }

                        if ( !empty( $content ) ) {                                                                 // Falls im Hook als Leer definiert, gebe nichts aus!

                            $content = '@charset "UTF-8";' . $content;

                            if ( !in_array( $content, $styles ) ) {                                                 // Falls durch den Hook oder 'swapi_ROOT_shadow' doppelter 'Content' vorhanden sein sollte ignorieren!

                                // RÜCKGABE
                                $styles[$key] = $content;
                            }
                        }
                    }
                }
                
            return ''; }, $html );
        }



        // ----------------------------- Rückgabe der CORS Anfrage an JavaScript -----------------------------
        $result_array = (array) array();

        $result_array['LLcbPage'] = (string) $array_object['LLsPage'];
        $result_array['LLcbHtml'] = (string) mj_minify_html( $html );


        // Optional: LLsAndOpen, wird per JavaScript 'LLsAndOpen: true' an PHP übertragen, so sende dieses auch wieder zurück dann wird diese Datei direkt nach dem Laden aufgerufen!
        // Beispiel: func_swapiCORS_send_mj_encrypt( 'cbLazyLoad', {LLsPage: 'dashboard.php', LLsAndOpen: true} )
        if ( array_key_exists( 'LLsAndOpen', $array_object ) && is_bool( $array_object['LLsAndOpen'] ) && $array_object['LLsAndOpen'] === true ) {

            $result_array['LLcbAndOpen'] = (bool) true;
        }

        // Optional: Wenn Links im 'MODULE_hyperlink' als 'Unload' gekennzeichnet werden, werden sie erst zur Laufzeit nachgeladen. Dies macht es unmöglich auf Clientseite zu überprüfen,
        // ob sich auf der aufrufenden Seite Änderungen ergeben haben. Sende somit die Seite immer. Egal ob diese Identisch ist oder nicht
        if ( array_key_exists( 'LLsUnload', $array_object ) && is_bool( $array_object['LLsUnload'] ) && $array_object['LLsUnload'] === true ) {

            $result_array['LLcbUnload'] = (bool) true;
        }

        // Optional: Bei dem erstmaligem besuchen der Webseite, sowie bei jedem weiteren Reload Vorgang, wird der einmalige Array Key 'LLsInit' per JS übermittelt. Somit ist die Initiale Übertragung  erfolgreich
        // gewährleistet. Sende dann den Wert '100' zum Client zurück. Dieser Prüft 100 + 100 + 100 = 300! Das entspricht drei Seiten die wieder an den Client zurückgesendet werden, nach folgendem Schema.
        // - a) SWAPI_root, + IF PAGE NOT 404 -> b) PAGE,           -> c) 404 Error Page
        // - a) SWAPI_root, + IF PAGE 404     -> b) 404 Error Page, -> c) STARTSEITE
        // Nur wenn alles Valide und vorhanden ist, so wird das 'swapi_ready' Event getriggert!
        if ( array_key_exists ( 'LLsInit', $array_object ) ) {

            $result_array['LLcbInit'] = (integer) 100;
        }

        // Optional: LLcbScript
        if ( !empty( $scripts ) ) {

            $result_array['LLcbScript'] = (array) $scripts;
        }

        // Optional: LLcbStyle
        if ( !empty( $styles ) ) {

            $result_array['LLcbStyle'] = (array) $styles;
        }


        return (array) $result_array;    
    }

    return (array) array();  // Leeres Array == Manipulationsversuch! Erzeugt eine FakeCallback Antwort und gibt diese an JS zurück daraufhin lädt dann die Webseite neu)
}


?>