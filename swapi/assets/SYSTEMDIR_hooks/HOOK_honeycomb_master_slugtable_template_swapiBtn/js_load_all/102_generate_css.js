

    /* generate CSS */
    swapinode().addEventListener('input', function(e) {

        let css_str_root = '.swapi_hcm_root[data-swapi-slugtable="swapiBtn"]';

        let p = e.target.closest( css_str_root );

        if( p !== null ) {

            let css_str_body = css_str_root + ' [data-template-hcm-naming="' + p.querySelector(':scope [data-template-hcm-naming]').getAttribute('data-template-hcm-naming') + '"] ~ .swapi_hcm_body';

            let gen = css_str_body + ' .slugtable_template_swapiBtn_example{' + p.querySelector(':scope [data-swapibtn-generalstyle]').value + '}';


            


            

            p.querySelector(':scope .slugtable_template_swapiBtn_style').innerHTML = '<style>' + gen + '</style>';
        }
    });







    /* generate CSS OLD */
    /*swapinode().addEventListener('input', function(e) {

        if ( e.target.hasAttribute('data-swapibutton-default') || e.target.hasAttribute('data-swapibutton-css') ) {

            let p   = e.target.closest('.swapi_hcm_root');
            let css = '[data-swapi-slugtable="' + p.getAttribute('data-swapi-slugtable') + '"] [data-template-hcm-naming="' + p.querySelector(':scope [data-template-hcm-naming]').getAttribute('data-template-hcm-naming') + '"] ~ .swapi_hcm_body';
            let gen = '';

            p.querySelectorAll(':scope [data-swapibutton-css]').forEach(function(a) {

                let elem = a.closest('.SLUGTABLE_TEMPLATE_CLASS__state');

                gen += css + ' .SLUGTABLE_TEMPLATE_CLASS__state:nth-of-type(' + (Array.from(elem.parentNode.children).indexOf(elem)+1).toString() + ') .slugtable_template_swapiBtn_example{' + a.value + '}';
            });

            gen = css + ' .slugtable_template_swapiBtn_example{' + p.querySelector(':scope [data-swapibutton-default]').value + '}' + gen;

            p.querySelector(':scope .slugtable_template_swapiBtn_style').innerHTML = '<style>' + gen + '</style>';
        }
    });*/