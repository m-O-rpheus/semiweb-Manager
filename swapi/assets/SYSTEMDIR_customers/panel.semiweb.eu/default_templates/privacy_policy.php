<?php

if ( !isset($is_include) ) { exit; } // Bei Direktaufruf abbruch

/* ========================================================================================================================================================== */

?>


<?php if ( php_session_is_logged_in() ): ?>


    <?php require swapi_paths()['PATH_dir_customers_templates'] . $GLOBALS["GLOBAL_swapi_SYSTEM_pages"]["SYSTEM_dashboard"]; ?>


<?php else: ?>


    <?php echo paNel_loggedout_embed( MODULE_honeycomb_buzz( "swapiWysiwyg", "panel.semiweb.eu_privacy_policy" ) ); ?>


<?php endif; ?>