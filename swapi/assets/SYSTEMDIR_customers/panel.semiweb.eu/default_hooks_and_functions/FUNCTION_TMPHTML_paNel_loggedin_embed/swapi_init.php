<?php


    function paNel_loggedin_embed( string $tasks ): string {

        $html = "<div class='paNel_loggedin_embed'>";
            $html .= "<div class='paNel_wallpaper'>" . MODULE_image_simple( paNel_SETTINGS_wallpaper_loggedin(), 'paNel Wallpaper' ) . "</div>";
            $html .= "<div class='paNel_taskbar'>";
                $html .= "<div class='paNel_taskbar_l'>";
                    $html .= "<div class='paNel_now'>"./* Insert here by JavaScript */"</div>";
                $html .= "</div>";
                $html .= "<div class='paNel_taskbar_c'>";
                    $html .= "<div class='paNel_tasks'>" . trim($tasks) . "</div>";
                $html .= "</div>";
                $html .= "<div class='paNel_taskbar_r'>";
                    $html .= "<div class='paNel_menubtn swapi_active_layer_parent'>" . MODULE_activelayer() . "</div>";
                $html .= "</div>";            
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    
?>