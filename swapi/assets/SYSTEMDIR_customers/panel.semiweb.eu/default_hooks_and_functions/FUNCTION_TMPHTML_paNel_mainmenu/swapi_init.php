<?php


    function paNel_mainmenu(): string {



        $windows = array(
            '_LOGGEDIN_usersaccount.php',
            '_LOGGEDIN_swapiButton.php',
            '_LOGGEDIN_swapiForms.php',
            '_LOGGEDIN_swapiWysiwyg.php',
            '_LOGGEDIN_swapi_manufacture.php',
            '_LOGGEDIN_phpUnitTest.php',
            '_LOGGEDIN_neuesProgramm1.php',
            '_LOGGEDIN_neuesProgramm2.php',
            '_LOGGEDIN_neuesProgramm3.php',
            '_LOGGEDIN_neuesProgramm4.php',
        );



        $html = "<div class='paNel_mainmenu'>";
            $html .= "<div class='paNel_mainmenuwrap'>";
                $html .= "<div class='paNel_mainmenugrid'>";

                    foreach ( $windows as $window_id ) {

                        $html .= paNel_mainapp( $window_id, paNel_icon_from_name( $window_id ) );
                    }
                    
                $html .= "</div>";
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    
?>