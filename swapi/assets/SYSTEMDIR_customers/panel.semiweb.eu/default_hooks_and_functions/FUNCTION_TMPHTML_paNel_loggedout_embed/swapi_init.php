<?php


    function paNel_loggedout_embed( string $content ): string {

        $html = "<div class='paNel_loggedout_embed'>";
            $html .= "<div class='paNel_wallpaper'>" . MODULE_image_simple( paNel_SETTINGS_wallpaper_loggedout(), 'paNel Wallpaper' ) . "</div>";
            $html .= "<div class='paNel_loggedout_screen'>";
                $html .= "<div class='paNel_loggedout_menu'>";


                    $html .= MODULE_hyperlink( 'start.php', 'login' );
                    $html .= "<span class='paNel_loggedout_spacer'></span>";
                    $html .= MODULE_hyperlink( 'register.php', 'register' );
                    $html .= "<span class='paNel_loggedout_spacer'></span>";
                    $html .= MODULE_hyperlink( 'lostpwd.php', 'lost password' );
                    $html .= "<span class='paNel_loggedout_spacer'></span>";
                    $html .= MODULE_hyperlink( 'privacy_policy.php', 'privacy policy' );


                $html .= "</div>";
                $html .= "<div class='paNel_loggedout_content'>";
                    $html .= trim($content);
                $html .= "</div>";
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    
?>