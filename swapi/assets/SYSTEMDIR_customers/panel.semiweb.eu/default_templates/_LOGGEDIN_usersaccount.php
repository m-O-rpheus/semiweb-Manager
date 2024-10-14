<?php

if ( !isset($is_include) ) { exit; } // Bei Direktaufruf abbruch

/* ========================================================================================================================================================== */

?>


<?php if ( php_session_is_logged_in() ): ?>


    <?php echo MODULE_honeycomb_buzz( 'swapiForms', '_SYSTEM_logout' ); ?>            

    <?php echo MODULE_browserstorage_form(); ?>

    <?php echo '<div class="paNel_form_a_part_of">' . MODULE_honeycomb_buzz( 'swapiForms', '_SYSTEM_pwdchange' ) . '</div>'; ?>


<?php else: require swapi_paths()['PATH_dir_customers_templates'] . $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_noauthorization']; endif; ?>