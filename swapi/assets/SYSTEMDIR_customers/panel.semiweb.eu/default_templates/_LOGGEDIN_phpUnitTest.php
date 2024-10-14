<?php

if ( !isset($is_include) ) { exit; } // Bei Direktaufruf abbruch

/* ========================================================================================================================================================== */

?>


<?php if ( php_session_is_logged_in() ): ?>


    <?php

        $unittest_stickygroup = function( string $title, closure $unittest_scenarios ): string {

            $unittest_html = (string) '<table class="paNel_unittest">';
                $unittest_html .= (string) '<thead>';
                    $unittest_html .= (string) '<tr>';
                        $unittest_html .= (string) '<th>Testszenario</th>';
                        $unittest_html .= (string) '<th>Test Erfolgreich?</th>';
                    $unittest_html .= (string) '</tr>';
                $unittest_html .= (string) '</thead>';
                $unittest_html .= (string) '<tbody>';
        
                    foreach ( $unittest_scenarios( array() ) as $name => $successful ) {

                        $unittest_html .= (string) '<tr>';
                            $unittest_html .= (string) '<td>' . $name . '</td>';
                            $unittest_html .= (string) '<td class="' . ($successful ? 'paNel_unittest_on_successful' : 'paNel_unittest_on_error') . '"></td>';
                        $unittest_html .= (string) '</tr>';
                    }

                $unittest_html .= (string) '</tbody>';
            $unittest_html .= (string) '</table>';

            return paNel_stickygroup_embed( $title, $unittest_html );
        };

    ?>





    <?php

        echo $unittest_stickygroup( 'UnitTest - function swapi_prepare_attribute()', function( array $unittest_arr ): array {

            $unittest_arr['String Int Float Bool Array test']   = (bool) ( swapi_prepare_attribute( array( 'src' => 'testimg.jpg', 'width' => 500, 'height' => 600.01, 'id' => true, 'style' => array('background-color:red;') ) ) === " src='testimg.jpg' width='500' height='600.01' id='1' style='background-color:red;'" );
            $unittest_arr['Override same String Attribute']     = (bool) ( swapi_prepare_attribute( array( 'data-test' => 'test1', 'data-test' => 'test2', 'name' => 'name1', 'name' => 'name2' ) ) === " data-test='test2' name='name2'" );
            $unittest_arr['Override same Class as String']      = (bool) ( swapi_prepare_attribute( array( 'class' => 'class1', 'class' => 'class2' ) ) === " class='class2'" );
            $unittest_arr['Override same Class as Array']       = (bool) ( swapi_prepare_attribute( array( 'class' => array('class1'), 'class' => array('class2') ) ) === " class='class2'" );
            $unittest_arr['Boolean Attribute at End']           = (bool) ( swapi_prepare_attribute( array( 'required' => 'required', 'data-hallo' => 'hallo', 'hidden' => '', 'class' => 'test1 test2 test3' ) ) === " data-hallo='hallo' class='test1 test2 test3' required hidden" );
            $unittest_arr['wrong HTTP URL with specialchars']   = (bool) ( swapi_prepare_attribute( array( 'href' => 'example.com/basin?birth=alarm&act=bridge' ) ) === " href='example.com/basin?birth=alarm&amp;act=bridge'" );
            $unittest_arr['correct HTTP URL with specialchars'] = (bool) ( swapi_prepare_attribute( array( 'href' => 'https://example.com/basin?birth=alarm&act=bridge' ) ) === " href='https://example.com/basin?birth=alarm&act=bridge'" );
            $unittest_arr['Parameter Empty']                    = (bool) ( swapi_prepare_attribute( array() ) === "" );
            $unittest_arr['Not Attribute Name']                 = (bool) ( swapi_prepare_attribute( array( 'required', 'checked', array() ) ) === "" );
            $unittest_arr['Rainbow test']                       = (bool) ( base64_encode( swapi_prepare_attribute( array( 'TEsT' => '<>&<>&', 'z&(I%/BN' => 'JKBHLUZIOP(/)', 'dAtA-KeINE-Ahnung!' => '3124hp89ÄÖÜäöüß' ) ) ) === "IHRlc3Q9JyZsdDsmZ3Q7JmFtcDsmbHQ7Jmd0OyZhbXA7JyB6aWJuPSdKS0JITFVaSU9QKC8pJyBkYXRhLWtlaW5lLWFobnVuZz0nMzEyNGhwODnDhMOWw5zDpMO2w7zDnyc=" );

            return $unittest_arr;
        });




        
    ?>
    

<?php else: require swapi_paths()['PATH_dir_customers_templates'] . $GLOBALS['GLOBAL_swapi_SYSTEM_pages']['SYSTEM_noauthorization']; endif; ?>