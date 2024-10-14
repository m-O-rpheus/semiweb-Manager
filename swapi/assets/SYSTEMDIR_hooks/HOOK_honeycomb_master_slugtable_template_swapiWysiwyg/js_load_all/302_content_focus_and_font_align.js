

    // set format and align from .swapi_wysiwyg_bar_main Radios to .SLUGTABLE_TEMPLATE_CLASS__content (+content_final)
    // --------------------------------------------------------------------------------------------------
    swapinode().addEventListener('change', function(e) {

        if ( e.target.hasAttribute('data-swapiwysiwyg') ) {

            const content  = e.target.closest('.SLUGTABLE_TEMPLATE_CLASS__content');
            const additive = content.querySelector(':scope [data-swapiwysiwyg="content_final"]');

            if ( e.target.closest('.swapi_wysiwyg_bar_format') !== null ) {

                content.setAttribute('data-wysiwyg-js-format', e.target.getAttribute('data-swapiwysiwyg'));
                additive.setAttribute('data-wysiwyg-js-format', e.target.getAttribute('data-swapiwysiwyg'));  // Nur für PHP notwendig
            }
            else if ( e.target.closest('.swapi_wysiwyg_bar_align') !== null ) {
                
                content.setAttribute('data-wysiwyg-js-align', e.target.getAttribute('data-swapiwysiwyg'));
                additive.setAttribute('data-wysiwyg-js-align', e.target.getAttribute('data-swapiwysiwyg'));   // Nur für PHP notwendig
            }
        }
    });



    // read format and align if present, else set default. In .SLUGTABLE_TEMPLATE_CLASS__content (+content_final) and .swapi_wysiwyg_bar_main Radios
    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_defaultformatalign = function(content) {

        const additive = content.querySelector(':scope [data-swapiwysiwyg="content_final"]');

        if ( !content.hasAttribute('data-wysiwyg-js-format') ) {     // gibt es .SLUGTABLE_TEMPLATE_CLASS__content[data-wysiwyg-js-format] noch nicht. Lege es mit default Wert an an!

            content.setAttribute('data-wysiwyg-js-format', 'content_tag_p');
            additive.setAttribute('data-wysiwyg-js-format', 'content_tag_p');      // Nur für PHP notwendig
        }

        if ( !content.hasAttribute('data-wysiwyg-js-align') ) {      // gibt es .SLUGTABLE_TEMPLATE_CLASS__content[data-wysiwyg-js-align] noch nicht. Lege es mit default Wert an an!

            content.setAttribute('data-wysiwyg-js-align', 'content_align_left');
            additive.setAttribute('data-wysiwyg-js-align', 'content_align_left');  // Nur für PHP notwendig
        }

        // Setze Checked Status der Radio Buttons in der Bar auf den Wert aus dem Attribute.
        content.querySelector(':scope .swapi_wysiwyg_bar_format [data-swapiwysiwyg="' + content.getAttribute('data-wysiwyg-js-format') + '"]').checked = true;
        content.querySelector(':scope .swapi_wysiwyg_bar_align [data-swapiwysiwyg="' + content.getAttribute('data-wysiwyg-js-align') + '"]').checked = true;
    };



    // focus handling
    // --------------------------------------------------------------------------------------------------
    swapinode().addEventListener('focusin', function(e) {   // focus in

        if ( e.target && e.target.hasAttribute('data-swapiwysiwyg') && e.target.getAttribute('data-swapiwysiwyg') === 'content_area' ) {

            const parent  = e.target.closest('.swapi_hcm_body');
            const content = e.target.closest('.SLUGTABLE_TEMPLATE_CLASS__content');

            // [Remove all] ...
            funcwysiwyg_hideopenedfocusbars(parent);
            
            // [Add] class .wysiwyg_focus to this content
            content.classList.add('wysiwyg_focus');

            // [Add] template to .swapi_wysiwyg_content_bar in this content
            if ( content.querySelector(':scope .swapi_wysiwyg_content_bar').firstChild === null ) {

                content.querySelector(':scope .swapi_wysiwyg_content_bar').appendChild( document.importNode( parent.querySelector(':scope > .swapi_wysiwyg_bar_tmpl').content, true ) );
            }

            // [DEFAULT] Specify settings for .swapi_wysiwyg_bar_format and .swapi_wysiwyg_bar_align radio fields by JavaScript.
            funcwysiwyg_defaultformatalign(content);

            funcwysiwyg_allresettrigger(content);
        }
    });

