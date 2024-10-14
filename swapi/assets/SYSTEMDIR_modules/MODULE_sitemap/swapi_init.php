<?php


/* Erstellt ein Sitemap

        MODULE_sitemap();

/* ========================================================================================================================================================== */

    function MODULE_sitemap(): string {

        $read_all_kunden_template_files = function(): array {

            static $customer_templates = array();       // Verwendete Module Zwischenspeichern dank static - Bei jedem weiteren Aufruf bleibt die Dateiliste erhalten!
    
            if ( empty( $customer_templates ) ) {
    
                $dir = (string) swapi_paths()['PATH_dir_customers_templates'];
        
                if ( file_exists( $dir ) ) {
            
                    foreach ( scandir( $dir ) as $contentidentifier ) {
            
                        if ( pathinfo( $contentidentifier, PATHINFO_EXTENSION ) === 'php' ) {
                
                            if ( file_exists( $dir . $contentidentifier ) ) {
    
                                $customer_templates[$contentidentifier] = true;
                            }
                        }
                    }
                }

                foreach ( $GLOBALS['GLOBAL_swapi_SYSTEM_pages'] as $key => $contentidentifier ) {

                    if ( !array_key_exists( $contentidentifier, $customer_templates ) ) {

                        $customer_templates[$contentidentifier] = true;
                    }
                }

                $customer_templates = array_keys( $customer_templates );
            }
    
            return (array) $customer_templates;
        };

        $html = '<ul' . swapi_prepare_attribute( ['class' => ['swapi_sitemap']] ) . '>';

            foreach ( $read_all_kunden_template_files() as $contentidentifier ) {

                $html .= '<li>' . MODULE_hyperlink( $contentidentifier, $contentidentifier ) . '</li>';
            }

        $html .= '</ul>';

        return (string) $html;
    }

    
?>