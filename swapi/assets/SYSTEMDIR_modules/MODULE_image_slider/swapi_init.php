<?php


/* Erstellt einen Slider aus HTML Bildern Vorlagen. Die im Kunden Ordner /image' Liegen müssen.

        MODULE_image_slider(
            [
                ['src' => 'bild1.jpg', 'alt' => 'Bild1', 'text' => 'Text auf dem Bild1'],
                ['src' => 'bild2.jpg', 'alt' => 'Bild2', 'text' => 'Text auf dem Bild2'],
            ],
            true|false
        );

/* ========================================================================================================================================================== */

    function MODULE_image_slider( array $images, bool $base64_encode = false ): string {

        if ( is_array( $images ) && !empty( $images ) ) {


            $i      = (int) 0;
            $count  = (int) count( $images );
            $slide  = (string) '';
            $unique = (string) swapi_generate_uniquehash();
    
            

            foreach ( $images as $image ) {
    
                $i++;
    
                $slide_src  = (string) ( ( array_key_exists( 'src',  $image ) && is_string( $image['src']  ) ) ? $image['src']  : '' );
                $slide_alt  = (string) ( ( array_key_exists( 'alt',  $image ) && is_string( $image['alt']  ) ) ? $image['alt']  : '' );
                $slide_text = (string) ( ( array_key_exists( 'text', $image ) && is_string( $image['text'] ) ) ? $image['text'] : '' );

                $slide_text = (string) ( ( !empty( $slide_text ) ) ? '<div' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_text_container']] ) . '>' . $slide_text . '</div>' : '' );


                if ( $count > 1 ) {     // The slider has more than one element

                    $bull = (string) '';

                    for( $j=1; $j <= $count; $j++ ) {
    
                        $bull .= (string) '<span' . swapi_prepare_attribute( ['class' => array_merge( ['swapi_img_slider_bull'], (( $i === $j ) ? ['swapi_img_slider_bull_active'] : []) )] ) . '>&bull;</span>';
                    }

                    $slide .= (string) '<input' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_radio'], 'name' => $unique, 'form' => ($unique.'0'), 'id' => ($unique.$i), 'type' => 'radio', 'data-swapi-radio' => ($i - 1), (($i === 1 ) ? 'checked' : '') => 'checked'] ) . '>';
                    $slide .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_slide']] ) . '>';
                        $slide .= (string) MODULE_image_simple( $slide_src, $slide_alt, $base64_encode );
                        $slide .= (string) $slide_text;
                        $slide .= (string) '<label' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_bullet'], 'for' => ($unique.$i)] ) . '>' . $bull. '</label>';
                        $slide .= (string) '<label' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_chevron', 'swapi_img_slider_chevron_prev'], 'for' => ($unique.((($i - 1) < 1) ? $count : ($i - 1)))] ) . '><span>&#10094;</span></label>';
                        $slide .= (string) '<label' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_chevron', 'swapi_img_slider_chevron_next'], 'for' => ($unique.((($i + 1) > $count) ? 1 : ($i + 1)))] ) . '><span>&#10095;</span></label>';
                    $slide .= (string) '</div>';
                }
                else {                  // The slider has only one element

                    $slide .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_slide']] ) . '>';
                        $slide .= (string) MODULE_image_simple( $slide_src, $slide_alt, $base64_encode );
                        $slide .= (string) $slide_text;
                    $slide .= (string) '</div>';
                }
            }
    


            $html = (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_img_slider']] ) . '>';
    
                if ( $count > 1 ) {     // The slider has more than one element

                    $html .= (string) '<form' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_form'], 'autocomplete' => 'off', 'id' => ($unique.'0') ] ) . '></form>';
                }
    
                $html .= (string) '<div' . swapi_prepare_attribute( ['class' => ['swapi_img_slider_slide_wrapper'], 'data-swapi-slides' => $count, 'style' => ['grid-template-columns:repeat('.$count.',100%);'] ] ) . '>' . $slide . '</div>';

            $html .= (string) '</div>';


            
            $html .= (string) swapi_enqueue_style([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'css_load_all' ], 'MODULE_image_slider');
            $html .= (string) swapi_enqueue_script([ dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'js_load_all' ], 'MODULE_image_slider');
    
            return (string) $html;
        }
        else {

            return (string) '';
        }
    }

    
?>