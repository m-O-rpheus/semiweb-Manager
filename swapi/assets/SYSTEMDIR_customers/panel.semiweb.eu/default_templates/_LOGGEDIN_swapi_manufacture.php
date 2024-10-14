<?php

if ( !isset($is_include) ) { exit; } // Bei Direktaufruf abbruch

/* ========================================================================================================================================================== */

?>


<?php if ( php_session_is_logged_in() ): ?>


       
    <?php echo paNel_stickygroup_embed( 'font2swapifile',

        '<div class="paNel_manufacture_swapifile">'
            .
            MODULE_honeycomb_buzz( 'swapiWysiwyg', 'panel.semiweb.eu_description_font2swapifile' )
            .
            MODULE_generate_font2swapifile()
            .
        '</div>'
    
    ); ?>

    <?php echo paNel_stickygroup_embed( 'svg2swapifile',
    
        '<div class="paNel_manufacture_swapifile">'
            .
            MODULE_generate_svg2swapifile()
            .
        '</div>'

    ); ?>



<?php else: require swapi_paths()['PATH_dir_customers_templates'] . $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_noauthorization']; endif; ?>