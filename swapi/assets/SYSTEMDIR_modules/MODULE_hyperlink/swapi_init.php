<?php


/* Definiert einen Link 

        MODULE_hyperlink( link, content, unload );

        Das Modul 'MODULE_hyperlink' dient dazu, alle Links zu erstellen und zu definieren. Diese Funktion hat drei Parameter, die verwendet werden, um den Link zu konfigurieren.

        Der erste Parameter 'link' bestimmt, ob der Link auf eine externe oder interne Seite verweist. Wenn der Parameter mit 'http://' oder 'https://' beginnt, wird auf eine externe Seite verlinkt, andernfalls auf eine interne Seite
        wenn der Link nur aus diesen erlaubten Zeichen sind a-z, A-Z, 0-9, -, . und _ besteht. Diese Einschränkungen sind notwendig, um sicherzustellen, dass die verlinkte Seite korrekt geladen werden kann und um mögliche
        Sicherheitsprobleme zu vermeiden.

        Der zweite Parameter 'content' definiert den Inhalt, der innerhalb des <a>-Tags angezeigt wird.

        Der dritte und letzte Parameter 'unload' gibt an, ob der verlinkte Inhalt im Voraus geladen werden soll oder erst nach dem Klicken auf den Link.

        Wenn 'unload' auf 'false' gesetzt ist, wird der verlinkte Inhalt (nur interne Seiten) im Voraus gescannt und vorab geladen. Dies entspricht der Standardfunktionalität und wird als die beste Wahl für statischen Inhalt betrachtet,
        der schnell dargestellt werden soll. In diesem Fall wird der verlinkte Inhalt im Voraus geladen. Dies ermöglicht eine schnellere Darstellung des Inhalts, sobald der Link angeklickt wird.

        Wenn 'unload' auf 'true' gesetzt ist, wird der verlinkte Inhalt (nur interne Seiten) erst geladen, nachdem der Link angeklickt wurde, hier wird nicht gescannt. Es sollte immer dann verwendet werden, wenn der verlinkte Inhalt
        dynamisch erzeugt wird und erst zur Laufzeit aktualisiert werden kann. In diesem Fall wird der verlinkte Inhalt erst geladen, nachdem der Link angeklickt wurde. Dies kann sinnvoll sein, wenn der Inhalt z.B. durch Benutzereingaben
        beeinflusst wird oder die Datenbankzugriffe reduziert werden sollen. Außerdem erhält man zudem die Möglichkeit, die verlinkte Seite nachträglich mit JavaScript zu ändern oder zu ergänzen. Hierfür muss das
        HTML Attribut 'data-swapi-unload' im HTML DOM geändert werden. Auch hierbei sind die gleichen Einschränkungen zu beachten, das nur die oben genannten erlaubten Zeichen verwendet werden dürfen.

        Es ist zu beachten, dass die Wahl zwischen 'false' oder 'true' für die unload-Option abhängig von der Art des verlinkten Inhalts und den Anforderungen an die Leistung und die Benutzerfreundlichkeit der Anwendung ist.

        Die Überarbeitung des Textes sowie des Module erfolgte am 27. Januar 2023.

/* ========================================================================================================================================================== */

    function MODULE_hyperlink( string $link, string $content, bool $unload = false ): string {

        $link = (string) swapi_sanitize_url( $link );

        
        // Externer Link...
        if ( parse_url( $link, PHP_URL_SCHEME ) === 'http' || parse_url( $link, PHP_URL_SCHEME ) === 'https' ) {

            $attribute = (string) swapi_prepare_attribute( ['class' => ['swapi_link', 'swapi_active_layer_parent', 'swapi_external_link'], 'href' => $link, 'target' => '_blank', 'rel' => 'noopener'] );
        }


        // Interner Link...
        else if ( swapi_validate_minimalist( $link ) ) {  // a-zA-Z0-9-._
            
            $href = mj_generate_urlquery( $GLOBALS['GLOBAL_customer'], $GLOBALS['GLOBAL_queryname'], $link );       // Erstelle den SEO Korrekt finalen Link href='' Attribute!
            
            if ( isset( $GLOBALS['GLOBAL_basename'] ) && $link === $GLOBALS['GLOBAL_basename'] ) {                  // Dieser Link entspricht der aktuell geöffneten Seite. Setze zusätzliche Klasse .swapi_active!

                $attribute = (string) swapi_prepare_attribute( ['class' => ['swapi_link', 'swapi_active_layer_parent', 'swapi_local_link', 'swapi_active'], 'href' => $href, (( $unload === true ) ? 'data-swapi-unload' : 'data-swapi-preload') => $link] );
            }
            else {                                                                                                  // Dieser Link entspricht NICHT der aktuell geöffneten Seite. 

                $attribute = (string) swapi_prepare_attribute( ['class' => ['swapi_link', 'swapi_active_layer_parent', 'swapi_local_link'], 'href' => $href, (( $unload === true ) ? 'data-swapi-unload' : 'data-swapi-preload') => $link] );
            }
        }


        // Sonstiger Link...
        else {

            $attribute = (string) swapi_prepare_attribute( ['class' => ['swapi_link', 'swapi_active_layer_parent'], 'href' => $link] );
        }


        return (string) '<a' . $attribute . '>' . MODULE_activelayer() . trim( $content ) . '</a>';
    }

    
?>