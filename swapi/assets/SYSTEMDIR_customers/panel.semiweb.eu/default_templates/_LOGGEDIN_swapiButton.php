<?php

if ( !isset($is_include) ) { exit; } // Bei Direktaufruf abbruch

/* ========================================================================================================================================================== */

?>


<?php if ( php_session_is_logged_in() ): ?>



    <?php echo MODULE_honeycomb_master( "swapiButton" ); ?>



<?php else: require swapi_paths()['PATH_dir_customers_templates'] . $GLOBALS["GLOBAL_swapi_SYSTEM_pages"]["SYSTEM_noauthorization"]; endif; ?>