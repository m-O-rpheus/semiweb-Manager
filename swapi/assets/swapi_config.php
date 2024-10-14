<?php

/* Globale Festlegungen */
/* ========================================================================================================================================================== */

    // $GLOBALS['GLOBAL_customer']   -- Diese Variable steht auch GLOBAL überall zur Verfügung. Wird jedoch Dynamisch erzeugt.
    // $GLOBALS['GLOBAL_basename']   -- Diese Variable wird innerhalb der customers templates festgelegt.



    // Das JavaScript wird folgendermaßen ein die Seite eingebunden: <script src='https://semiweb.eu/swapi/swapi.js.php?page={GLOBAL_customers}'></script>
    // Gebe mittels ?page die Webseite des Kunden an, auf dieser das Script verwendet werden soll. Es steht dann Individuelles '_FormAction und _LazyLoad zur Verfügung'

    // Lege hier die Kunden fest, auf welchen Webseiten das <script> eingesetzt werden darf.
    $GLOBALS['GLOBAL_customers'] = [
        'markus-jaeger.de',
        'semiwebblank.markus-jaeger.de',
        'admin.semiweb.eu',
        'panel.semiweb.eu',
        'smarthome.markus-jaeger.de',
    ];

    $GLOBALS['GLOBAL_allow_origins'] = [
        'https://markus-jaeger.de',
        'https://semiwebblank.markus-jaeger.de',
        'https://admin.semiweb.eu',
        'https://panel.semiweb.eu',
        'https://smarthome.markus-jaeger.de',
    ];


    
    // URL Query Parameter Name - Hier Bitte nur a-z eingeben!
    // wird in <a href=''> und im JavaScript für window.history und URLSearchParams verwendet. Dieser query Paramter wird in zwei fällen, in eine URL einkodiert.
    $GLOBALS['GLOBAL_queryname']   = 'swapi';



    // SYSTEM Seitenfestlegung - ( $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_xXx'] )
    $GLOBALS['GLOBAL_swapi_SYSTEM_pages'] = array(
        'SYSTEM_index'           => 'start.php',                    // Startseite: Logged Out
        'SYSTEM_dashboard'       => '_LOGGEDIN_dashboard.php',      // Startseite: Logged In
        'SYSTEM_logout'          => '_LOGGEDIN_logout.php',         // Logout Page
        'SYSTEM_registersuccess' => 'register_success.php',         // Registrierung nächster Schritt
        'SYSTEM_lostpwdsuccess'  => 'lostpwd_success.php',          // Passwort Vergessen nächster Schritt
        'SYSTEM_noauthorization' => '_ERROR_no_authorization.php',  // ERROR: no authorization
        'SYSTEM_404error'        => '_ERROR_404.php',               // ERROR: 404
    );
    

    // Ermittelt die eigene Server Domain mit https - WICHTIG: Das trim() muss immer stehen bleiben!
    $GLOBALS['GLOBAL_self_uri'] = trim(     'https://' . trim( $_SERVER['SERVER_NAME'] ) . '/swapi'     , '/');


    


    // ContentIdentifier: ...
    define('GLOBAL_CORE_SWAPIROOT', '_SWAPI_root'); // Nicht ändern!


    // Verbindung zur Datenbank: ...
    define('GLOBAL_CORE_MYSQLHOST', 'localhost');
    define('GLOBAL_CORE_MYSQLDB',   'd036e327');
    define('GLOBAL_CORE_MYSQLUSER', 'd036e327');
    define('GLOBAL_CORE_MYSQLPASS', 'gtZhgctYGymu6Jw9');


    // Passwörter sowie Salt: ... Änderungen zuerstören möglicherweise die komplette Funktionalität
    define('GLOBAL_CORE_MASTERKEY_MAILLINK',       'V}eyB&A§X7T&-%vm5%Zu(6=7uUnc#$HQpxS>yrz2u$+Wy{hefMCRaN§m!xeFY8MH');
    define('GLOBAL_CORE_MASTERKEY_HONEYCOMBMYSQL', '&X47@ANhd!wXxBSm)gwjDGz!N4G2!2AS0vQh!Txps2EChW6GzP?fKpD&kpCZ*H!w');
    define('GLOBAL_CORE_MASTERKEY_SESSION',        'Wz2kH8A(eHS$P(nek:qERY%CCbXqt(§x,FarbE6sQsarNmSZRg)qJD<Pwvyu§HZ&');
        

    // Ordner Namen
    define('GLOBAL_CORE_SYSTEMDIR_customers', 'SYSTEMDIR_customers');   // Ordner Name
    define('GLOBAL_CORE_SYSTEMDIR_functions', 'SYSTEMDIR_functions');   // Ordner Name
    define('GLOBAL_CORE_SYSTEMDIR_hooks',     'SYSTEMDIR_hooks');       // Ordner Name
    define('GLOBAL_CORE_SYSTEMDIR_modules',   'SYSTEMDIR_modules');     // Ordner Name



?>