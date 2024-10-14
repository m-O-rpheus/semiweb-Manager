<?php


    function paNel_task( string $window_id, string $window_icon, string $unique ): string {

        $html = "<div class='paNel_task' style='--panel-ico:" . htmlentities(trim($window_icon), ENT_QUOTES) . ";' data-panel-unique='" . htmlentities(trim($unique), ENT_QUOTES) . "'>";
            $html .= "<div class='paNel_task_field paNel_ico'></div>";
            $html .= "<div class='paNel_task_title'>" . htmlentities(trim($window_id), ENT_QUOTES) . "</div>";
        $html .= "</div>";

        return $html;
    }

    
?>