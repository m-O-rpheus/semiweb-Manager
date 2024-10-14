

    swapinode().addEventListener('change', function(e) {

        if( e.target && e.target.classList.contains('swapi_field_file') && e.target.parentNode.parentNode.classList.contains('swapi_generate_font2swapifile') ) {

            const file     = e.target.files[0];
            const filename = file.name.substr(0, file.name.lastIndexOf('.')).replace(/[^a-zA-Z0-9]/g, "");
            const reader   = new FileReader();

            reader.readAsDataURL(file); 
            reader.onload = function(e) {

                const b64 = e.target.result; 


                // Lösung A: constructed stylesheet - Besser da nicht im DOM sichtbar aber Browser Support schlecht
                // (function(){let css = new CSSStyleSheet();css.replaceSync('.hallo { background-color: blue; }');document.adoptedStyleSheets=[css];})();

                
                // Lösung B: Alternative - Aber style tag im DOM
                // (function(){document.head.insertAdjacentHTML("beforeend",'<style class="swapi_font">.hallo { background-color: green; }</style>');})();


                // Lösung C: sheet.insertRule
                const str = '(function(){const font=document.createElement("style");font.setAttribute("class","swapi_font");font.setAttribute("data-swapi-font-family","swapi-'+filename+'");document.head.appendChild(font);font.sheet.insertRule(\'@font-face{font-family:"swapi-'+filename+'";src:url("'+b64+'") format("woff2");font-weight:normal;font-style:normal;font-display:swap;}\',0);})();';

                
                var elem = document.createElement('a');
                elem.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(str));
                elem.setAttribute('download', '000_font_swapi-'+filename+'.js');
                elem.style.display = 'none';

                document.body.appendChild(elem);
                
                elem.click();
                
                document.body.removeChild(elem);
            };
        }
    });

