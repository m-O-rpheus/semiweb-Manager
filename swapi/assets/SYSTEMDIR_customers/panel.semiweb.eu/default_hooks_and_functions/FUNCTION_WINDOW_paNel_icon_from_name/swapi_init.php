<?php


    function paNel_icon_from_name( string $window_id ): string {

        $window_icon = 'var(--swapi-svg-unknownwindow)';

        if ( $window_id === '_LOGGEDIN_usersaccount.php' ) {

            $window_icon = 'var(--swapi-svg-usersaccount)';
        }
        else if ( $window_id === '_LOGGEDIN_swapiButton.php' ) {

            $window_icon = 'var(--swapi-svg-swapiButton)';
        }
        else if ( $window_id === '_LOGGEDIN_swapiForms.php' ) {

            $window_icon = 'var(--swapi-svg-swapiForms)';
        }
        else if ( $window_id === '_LOGGEDIN_swapiWysiwyg.php' ) {

            $window_icon = 'var(--swapi-svg-swapiWysiwyg)';
        }
        else if ( $window_id === '_LOGGEDIN_swapi_manufacture.php' ) {

            $window_icon = 'var(--swapi-svg-manufacture)';
        }
        else if ( $window_id === '_LOGGEDIN_phpUnitTest.php' ) {

            $window_icon = 'var(--swapi-svg-unittest)';
        }





        else if ( $window_id === '_LOGGEDIN_window1.php' ) {

            $window_icon = 'var(--swapi-svg-settings)';
        }

        else if ( $window_id === '_LOGGEDIN_window2.php' ) {

            $window_icon = 'var(--swapi-svg-user)';
        }

        else if ( $window_id === '_LOGGEDIN_window3.php' ) {

            $window_icon = 'var(--swapi-svg-cat)';
        }

        else if ( $window_id === '_LOGGEDIN_window4.php' ) {

            $window_icon = 'var(--swapi-svg-developer)';
        }

        return $window_icon;
    }

    
?>