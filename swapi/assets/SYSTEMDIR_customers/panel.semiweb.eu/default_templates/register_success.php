<?php

if ( !isset($is_include) ) { exit; } // Bei Direktaufruf abbruch

/* ========================================================================================================================================================== */

?>


<?php if ( php_session_is_logged_in() ): ?>


    <?php require swapi_paths()['PATH_dir_customers_templates'] . $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_dashboard']; ?>


<?php else: ?>

    
    <?php echo paNel_loggedout_embed( '<div class="paNel_form_a_part_of">Sie haben sich registriert. Sollte die übermittelte E-Mail-Adresse noch nicht in unserem System vorhanden sein, erhalten Sie eine Benachrichtigung, die noch bestätigt werden muss. Wenn Sie bereits registriert sind, erhalten Sie eine E-Mail, in der Sie aufgefordert werden, sich anzumelden. Wenn Sie keine E-Mail erhalten haben, empfehlen wir Ihnen, sich an den Support zu wenden oder es später erneut zu versuchen. Die eingegebene E-Mail-Adresse ist möglicherweise für diese Aktion nicht zugelassen.</div>' ); ?>


<?php endif; ?>