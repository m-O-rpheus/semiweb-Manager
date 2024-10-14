<?php


    function paNel_stickygroup_embed( string $title, string $content ): string {

        $html = "<div class='paNel_stickygroup_embed'>";
            $html .= "<details class='paNel_stickygroup_subtitle' open>";
                $html .= "<summary>" . htmlentities(trim($title), ENT_QUOTES) . "</summary>";
            $html .= "</details>";
            $html .= "<div class='paNel_stickygroup_submenu'>";
                $html .= "<div class='paNel_stickygroup_container'>" . trim($content) . "</div>";
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    
?>