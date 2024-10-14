<?php


    function paNel_win_embed( string $window_id, string $window_icon, string $unique, string $content ): string {

        $html = "<div class='paNel_win_js_active_click paNel_win_embed' style='--panel-ico:" . htmlentities(trim($window_icon), ENT_QUOTES) . ";' data-panel-unique='" . htmlentities(trim($unique), ENT_QUOTES) . "'>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_topleft' data-panel-mousedown='resizetopleft'></div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_top' data-panel-mousedown='resizetop'>";


                $html .= "<div class='paNel_win_topbar'>";
                    $html .= "<div class='paNel_win_topbar_elem paNel_win_topbar_ico paNel_win_topbar_move paNel_ico' data-panel-mousedown='move'></div>";
                    $html .= "<div class='paNel_win_topbar_elem paNel_win_topbar_title paNel_win_topbar_move' data-panel-mousedown='move'>";
                        $html .= "<h2 class='paNel_win_topbar_title_text'>" . htmlentities(trim($window_id), ENT_QUOTES) . "</h2>";
                    $html .= "</div>";
                    $html .= "<div class='paNel_win_topbar_elem paNel_win_topbar_minimize paNel_win_topbar_btn'>";
                        $html .= "<a class='swapi_active_layer_parent'>" . MODULE_activelayer() . "</a>";  // Click auf <a> erfolgt per JS
                    $html .= "</div>";
                    $html .= "<div class='paNel_win_topbar_elem paNel_win_topbar_maximize paNel_win_topbar_btn'>";
                        $html .= "<a class='swapi_active_layer_parent'>" . MODULE_activelayer() . "</a>";  // Click auf <a> erfolgt per JS
                    $html .= "</div>";
                    $html .= "<div class='paNel_win_topbar_elem paNel_win_topbar_close paNel_win_topbar_btn'>";
                        $html .= MODULE_hyperlink( htmlentities(trim($window_id), ENT_QUOTES), '', true );
                    $html .= "</div>";
                $html .= "</div>";


            $html .= "</div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_topright' data-panel-mousedown='resizetopright'></div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_left' data-panel-mousedown='resizeleft'></div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_middle'></div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_right' data-panel-mousedown='resizeright'></div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_bottomleft' data-panel-mousedown='resizebottomleft'></div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_bottom' data-panel-mousedown='resizebottom'></div>";
            $html .= "<div class='paNel_win_resize paNel_win_resize_bottomright' data-panel-mousedown='resizebottomright'></div>";
        $html .= "</div>";

        $html .= "<div class='paNel_win_js_active_click paNel_content'>" . trim($content) . "</div>";

        return $html;
    }

    
?>