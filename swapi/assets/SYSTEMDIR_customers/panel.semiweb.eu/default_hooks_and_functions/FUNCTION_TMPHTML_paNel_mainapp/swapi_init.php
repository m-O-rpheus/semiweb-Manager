<?php


    function paNel_mainapp( string $window_id, string $window_icon ): string {

        return "<div class='paNel_mainapp' style='--panel-ico:" . htmlentities(trim($window_icon), ENT_QUOTES) . ";'>" . MODULE_hyperlink( htmlentities(trim($window_id), ENT_QUOTES), htmlentities(trim($window_id), ENT_QUOTES), true ) . "</div>";
    }

    
?>