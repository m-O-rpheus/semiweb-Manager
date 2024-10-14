<?php


/* Generiert das 'swapi' Javascript
   Wird folgendermaßen in eine beliebige Webseite eingebunden - Dieses Script ist Cross Domain fähig!

            <script src='https://semiweb.eu/swapi/swapi.js.php?page=markus-jaeger.de'></script>

    Funktioniert nur unter HTTPS.
    Am Server muss zusätzlich SSL Erzwingen, aktiviert werden!

    Der Parameter ?page, bestimmt für welchen 'Kunden' das JavaScript ausgeliefert wird.
    - Hier muss exakt die Domain der Webseite angegeben werden ( ohne http / https) bzw Subdomain 'subdomain.domain.de' von der Webseite welche das Script eingefügt hat.
    - Der Kunde muss in der config unter 'scriptpages' Registriert sein!
    - Diese Datei ist als aller erstes auszuführen

/* ========================================================================================================================================================== */


    // Includes
    $ROOTDIR = dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
    require_once $ROOTDIR . 'swapi_config.php';
    require_once $ROOTDIR . 'swapi_loadfiles.php';
    swapi_loadfiles( $ROOTDIR . GLOBAL_CORE_SYSTEMDIR_functions . DIRECTORY_SEPARATOR );



    

    // Header JavaScript Datei
    header('Content-Type: application/javascript; charset=utf-8');

    // Header für noCache
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: on, 01 Jan 1970 00:00:00 GMT');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');  // HSTS - Automatically redirect to HTTPS
    




    // Script Variable Definieren - Ziel ist es, das diese überschrieben wird.
    $script = 'alert("Error: unknown error");';


    // Nur weitermachen wenn das <script> mit einer $_GET['page'] Variable aufgerufen wird.
    if ( is_array( $_GET ) && array_key_exists( 'page', $_GET ) && is_string( $_GET['page'] ) && is_array( $GLOBALS['GLOBAL_customers'] ) && in_array( $_GET['page'], $GLOBALS['GLOBAL_customers'] ) ) {


        $GLOBALS['GLOBAL_customer'] = trim( trim( swapi_sanitize_url( $_GET['page'] ) ), '/' );  // Diese Seite wurde im <script> Tag unter dem Parameter ?page= angegeben. Stelle diese GLOBAL bereit!
        

        // Kein https -> Leite weiter auf https
        if( !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ) {

            header('Location: ' . $GLOBALS['GLOBAL_self_uri'] . '/' . basename(__FILE__), true, 301);
            exit();
        }


        // https -> Alles gut.
        else {


            // Starte eine neue Session sowie prüfen ob dies Erfolgreich war!
            // Hinweis: Es wird zu diesem zeitpunkt IMMER eine Neue Session gestartet und erst später per Javascript eine vorhandene aufgenommen!
            if ( swapi_start_or_continue_session() === true ) {

                $swapi_tag = 'swapi-' . swapi_generate_uniquehash();   // unique Tag Name

                $script = trim('

                    (function(){"use strict"; try {
                        
                        const mjv_swapi_script = document.currentScript;   '/* ----- Hole den aktuellen <script> Tag! ----- */.'
                        const mjv_swapi_tag    = "' . $swapi_tag . '";     '/* ----- Definiere den Tag Namen des ShadowElement ----- */.'

                        document.addEventListener("DOMContentLoaded", function() {

                            '/* ----- ShadowElement definieren ----- */.'
                            class SWAPI extends HTMLElement {
                                constructor() {
                                    super();
                                    this.attachShadow({ mode: "open" });
                                    this.shadowRoot.innerHTML = "<div class=\'swapi\' id=\'swapi\' data-swapi-tag=\'" + mjv_swapi_tag + "\'><div class=\'swapi_pseudo\' hidden><div><div><div><div></div></div></div></div></div></div>";
                                }
                            }

                            window.customElements.define(mjv_swapi_tag, SWAPI);

                            mjv_swapi_script.parentNode.insertBefore(new SWAPI(), mjv_swapi_script.nextSibling);   '/* <swapi-*> Element unterhalb des aktuellen <script> Tags einfügen! */.'


                            '/* ----- Mache was nachdem das Shadow Element fertig angelegt wurde! ----- */.'
                            window.customElements.whenDefined(mjv_swapi_tag).then(function() {

                                if (mjv_swapi_script.nextSibling.tagName.toLowerCase() === mjv_swapi_tag) {


                                    '/* ----- Globale Variablen Initialisieren ----- */.'
                                    const mjv_global_swapi_root              = "' . GLOBAL_CORE_SWAPIROOT . '";                                                               '/* ContentIdentifier: .swapi_root */.'

                                    const mjv_global_customerpage            = "' . $GLOBALS['GLOBAL_customer'] . '";                                                         '/* Seite die im ?page Parameter an das <script> angehängt ist! */.'
                                    const mjv_global_queryname               = "' . $GLOBALS['GLOBAL_queryname'] . '";                                                        '/* URL Query Parameter Name config */.'
                                    const mjv_global_selfuri                 = "' . $GLOBALS['GLOBAL_self_uri'] . '/";
                                    const mjv_global_urlquery                = "' . mj_generate_urlquery( $GLOBALS['GLOBAL_customer'], $GLOBALS['GLOBAL_queryname'] ) . '";   '/* Seitenwechsel URL mit Parameter */.'

                                    const mjv_global_classname_swapi_section = ".swapi_section";  

                                    const mjv_global_initial_session         = "' . $_SESSION['core_session_aeskey'] . '";                                                    '/* Da bei jeder Anfrage an die (swapi.js.php) eine neue Session erstellt wird speichere diese in eine Konstante! */.'
                                    let   mjv_actual_session                 = mjv_global_initial_session;                                                                    '/* mjv_actual_session wird von jeder 'CORS Antwort' oder inhalt des 'LocalStorage' überschrieben! */.'


                                    '/* ----- Script Include ----- */.'
                                    ' . file_get_contents( swapi_paths()['PATH_dir_css-js_once_root'] . 'root_script.js' ) . '
                                }
                            });
                        });

                    }catch(e){return\'\';}})();

                ');

            }
            else {

                // Fehler mit der Session
            }
        }
    }
    else {

        $script = 'alert("Error: Please append a valid ?page=MYPAGE variable to the <script> tag. Please also make sure that the site is registered in the semiweb system!");';
    }



    $script = mj_minify_swapi_system_javascript( mj_minify_javascript( $script ) );
    echo '/*'.number_format((strlen($script)), 0).' KB*/'.$script;


?>