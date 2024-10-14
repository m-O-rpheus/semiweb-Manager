<?php


/* Responsive Flex Container welcher ohne Media Query Automatisch umbricht!

        MODULE_responsive_flex( elemente, min_width, min_columns, max_columns, rowgap, columngap );

        - array   elemente         <div> Container die Innerhalb des 'responsive_flex' liegen
        - string  min_width        Mindestbreite eines 'responsive_flex' Elements in px
        - int     min_columns      Wieviele Spalten Minimal (in der Mobilen Ansicht)
        - int     max_columns      Wieviele Spalten Maximal (in der Desktop Ansicht)
        - string  rowgap           Abstand zwischen den Zeilen, in px, vw,vw usw
        - string  columngap        Abstand zwischen den Spalten, in px, vw,vw usw

/* ========================================================================================================================================================== */

    function MODULE_responsive_flex( array $elements, string $minwidth, int $mincolumns, int $maxcolumns, string $rowgap = '5vh', string $columngap = '2vw' ): string {

        $html = (string) '';

        if ( $mincolumns < $maxcolumns && $mincolumns > 0 && $maxcolumns > 1) {

            $html .= (string) '<nav' . swapi_prepare_attribute( ['class' => ['swapi_responsive_flex'], 'style' => ['--swapi-minwidth:'.$minwidth.';', '--swapi-mincolumns:'.$mincolumns.';', '--swapi-maxcolumns:'.$maxcolumns.';', '--swapi-rowgap:'.$rowgap.';', '--swapi-columngap:'.$columngap.';']] ) . '>';
            
                $html .= (string) '<ul' . swapi_prepare_attribute( ['class' => ['swapi_responsive_flex_ul']] ) . '>';

                    foreach( $elements as $element ) {

                        $html .= (string) '<li' . swapi_prepare_attribute( ['class' => ['swapi_responsive_flex_li']] ) . '>'.$element.'</li>';
                    }

                    for ($i = 1; $i < $maxcolumns; $i++) {

                        $html .= (string) '<li></li>';
                    }

                $html .= (string) '</ul>';

            $html .= (string) '</nav>';


            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_responsive_flex');
        }
        else {

            $html .= (string) swapi_ERRORMSG( '[MODULE_responsive_flex] Invalid min_columns and max_columns values!' );
        }

        return (string) $html;
    }

    
?>