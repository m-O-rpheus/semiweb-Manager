<?php


    /* Lade Template Dateien vom Ordner 'templates' */
    /* ========================================================================================================================================================== */
    hook_into_swapi_array('swapi_customer_template_html_inside_swapi_section_container', function( array $passthrough ) {


        // === function: no_existing_file - for GLOBAL_swapi_SYSTEM_pages
        $no_existing_file = function( string $contentidentifier, string $dir ): string {


            // ====== function: swapi_BLANK_template - for GLOBAL_swapi_SYSTEM_pages templates
            $swapi_BLANK_template = function( string $contentidentifier ): string {


                $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_BLANK']] ) . '>';
        
                    $html .= (string) MODULE_legacy_text([ ['heading1', 'Semiweb BLANK'], ['paragraph', '<span' . swapi_prepare_attribute( ['class' => ['swapi_BLANK_example'], 'style' => ['color:var(--swapi-core-default-accent);']] ) . '>This is an Example BLANK page for the Semiweb Ecosystem (' . $contentidentifier . ').<br>To override this, please create a template file in the directory &quot' . swapi_paths_2_path_for_user_explanation('PATH_dir_customers_templates') . '&quot with the filename &quot' . $contentidentifier . '&quot.</span>'] ]);
                    $html .= (string) MODULE_legacy_text([ ['heading2', 'All existing Semiweb BLANK pages (sitemap)'] ]);
                    $html .= (string) MODULE_sitemap();

        
                    // ========= swapi_BLANK for - SYSTEM_index || SYSTEM_dashboard - LOGGEDOUT
                    if (
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index']            && php_session_is_logged_in() === false
                        ||
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_dashboard']        && php_session_is_logged_in() === false
                    ) {
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'Login into Semiweb Ecosystem'] ]);
                        $html .= (string) MODULE_honeycomb_buzz( 'swapiForms', '_SYSTEM_login' );
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'Lost Password?'] ]);
                        $html .= (string) MODULE_honeycomb_buzz( 'swapiForms', '_SYSTEM_lostpwd' );
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'or Register to Semiweb Ecosystem'] ]);
                        $html .= (string) MODULE_honeycomb_buzz( 'swapiForms', '_SYSTEM_register' );
                    }
        
        
                    // ========= swapi_BLANK for - SYSTEM_index || SYSTEM_dashboard - if the above does not apply!
                    if (
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index']            && php_session_is_logged_in() === true
                        ||
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_dashboard']        && php_session_is_logged_in() === true
                    ) {
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'Welcome'] ]);
                        $html .= (string) MODULE_loggedin_welcome_message();
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'Set Cookie'] ]);
                        $html .= (string) MODULE_browserstorage_form();
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'Passwort change?'] ]);
                        $html .= (string) MODULE_honeycomb_buzz( 'swapiForms', '_SYSTEM_pwdchange' );
                    }


                    // ========= swapi_BLANK for SYSTEM_logout - LOGGEDIN
                    else if (
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_logout']           && php_session_is_logged_in() === true
                    ) {
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'LOGOUT'], ['paragraph', 'Are you sure you want to log out?'] ]);
                        $html .= (string) MODULE_honeycomb_buzz( 'swapiForms', '_SYSTEM_logout' );
                        $html .= (string) MODULE_hyperlink( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], 'Back to Startpage' );
                    }
        
        
                    // ========= swapi_BLANK for SYSTEM_registersuccess - LOGGEDOUT
                    else if (
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_registersuccess']  && php_session_is_logged_in() === false
                    ) {
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'REGISTER SUCCESS'], ['paragraph', 'You have registered. If the submitted e-mail address is not yet in our system, you will receive a notification that still needs to be confirmed. If you are already registered, you will receive an email asking you to log in. If you haven\'t received an email, we recommend that you contact support or try again later. The e-mail address entered may not be permitted for this action.'] ]);
                        $html .= (string) MODULE_hyperlink( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], 'Back to Startpage' );
                    }
        
        
                    // ========= swapi_BLANK for SYSTEM_lostpwdsuccess - LOGGEDOUT
                    else if (
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_lostpwdsuccess']   && php_session_is_logged_in() === false 
                    ) {
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'LOSTPWD SUCCESS'], ['paragraph', 'You have requested a password reset. If the information submitted is valid, an email will be sent to reset your password. If you haven\'t received an email, we recommend that you contact support or try again later. The e-mail address entered may not be permitted for this action.'] ]);
                        $html .= (string) MODULE_hyperlink( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], 'Back to Startpage' );
                    }
        
        
                    // ========= swapi_BLANK for SYSTEM_noauthorization - if the above does not apply!
                    else if (
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_noauthorization']
                        ||
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_logout']           && php_session_is_logged_in() === false
                        ||
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_registersuccess']  && php_session_is_logged_in() === true
                        ||
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_lostpwdsuccess']   && php_session_is_logged_in() === true
                    ) {
                        $html .= (string) MODULE_legacy_text([ ['heading3', 'NO AUTHORIZATION'], ['paragraph', 'No authorization to view the current page content. Please ensure you are logged in or out of the required page as appropriate.'] ]);
                        $html .= (string) MODULE_hyperlink( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], 'Back to Startpage' );
                    }
        
        
                    // ========= swapi_BLANK for SYSTEM_404error
                    else if (
                        $contentidentifier === $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_404error']
                    ) {
                        $html .= (string) MODULE_legacy_text([ ['heading3', '404 Page not found'] ]);
                        $html .= (string) MODULE_hyperlink( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_index'], 'Back to Startpage' );
                    }

        
                $html .= (string) '</div>';
        
                return (string) $html;
            };



            // ====== KEINE physische Datei vorhanden aber Array enthält 'contentidentifier' als value. Gebe swapi_BLANK aus!
            if ( in_array( $contentidentifier, $GLOBALS['GLOBAL_swapi_SYSTEM_pages'] ) ) {

                return (string) $swapi_BLANK_template( $contentidentifier );                                         // 'swapi_BLANK_template' for ALL 'GLOBAL_swapi_SYSTEM_pages' except 'SYSTEM_404error'
            }

            // ====== KEINE physische Datei sowie 'contentidentifier' NICHT im array vorhanden. Gebe 404 Error aus!
            if ( file_exists( $dir . $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_404error'] ) ) {

                ob_start();
                $is_include = (bool) true;
                require ( $dir . $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_404error'] );
                $content = ob_get_clean();

                return (string) $content;
            }
            else {

                return (string) $swapi_BLANK_template( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_404error'] );   // 'swapi_BLANK_template' ONLY for 'SYSTEM_404error' from 'GLOBAL_swapi_SYSTEM_pages'
            }
        };



        $content_array = (array)  array();
        $dir           = (string) swapi_paths()['PATH_dir_customers_templates'];

        if ( swapi_validate_minimalist( $passthrough['READONLY_contentidentifier'] ) && file_exists( $dir . $passthrough['READONLY_contentidentifier'] ) ) {    // Trifft nur zu wenn die aufzurufende Datei als physische Datei vorhanden ist!

            // ---------- Include Single oder Multipage
            ob_start();
            $is_include = (bool) true;
            require ( $dir . $passthrough['READONLY_contentidentifier'] );
            $content = (string) ob_get_clean();


            // ---------- Check for Multipage
            if ( isset( $multiple_pages_in_section_import ) && is_array( $multiple_pages_in_section_import ) && !empty( $multiple_pages_in_section_import ) ) {

                foreach( $multiple_pages_in_section_import as $contentidentifier ) {

                    if ( swapi_validate_minimalist( $contentidentifier ) && file_exists( $dir . $contentidentifier ) ) {                                        // Trifft nur zu wenn die aufzurufende Datei als physische Datei vorhanden ist!

                        ob_start();
                        $is_include = (bool) true;
                        require ( $dir . $contentidentifier );
                        $content = (string) ob_get_clean();
    
                        $content_array[$contentidentifier] = (string) $content;
                    }
                    else {

                        $content_array[$contentidentifier] = (string) $no_existing_file( $contentidentifier, $dir );                                            // KEINE physische Datei vorhanden liefere alternativen Content
                    }
                }
            }
            else {

                $content_array[$passthrough['READONLY_contentidentifier']] = (string) $content; 
            }
        }
        else {

            $content_array[$passthrough['READONLY_contentidentifier']] = (string) $no_existing_file( $passthrough['READONLY_contentidentifier'], $dir );        // KEINE physische Datei vorhanden liefere alternativen Content
        }

        $passthrough['html'] += $content_array;

        return (array) $passthrough;

    }, 1);



    

    /* CSS sowie JavaScript Dateien, aus den Ordner 'css' sowie 'js' einbinden */
    /* ========================================================================================================================================================== */
    hook_into_swapi_array('swapi_customer_template_html_inside_swapi_root_container', function( array $passthrough ) {

        $passthrough['html'] .= swapi_enqueue_style([ swapi_paths()['PATH_dir_customers_css'] ], 'define_by_customer' );
        $passthrough['html'] .= swapi_enqueue_script([ swapi_paths()['PATH_dir_customers_js'] ], 'define_by_customer' );

        return (array) $passthrough;

    }, 5000);


?>