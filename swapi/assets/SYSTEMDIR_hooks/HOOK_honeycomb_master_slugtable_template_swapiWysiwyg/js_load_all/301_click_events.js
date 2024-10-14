

    // CLICK EVENT - add structure or content button was clicked
    // --------------------------------------------------------------------------------------------------
    funcwysiwyg_registerclickevent('.swapi_wysiwyg_structure_btn', function(sel, t) {

        if ( sel.getAttribute('data-swapiwysiwyg') === 'structure' ) {

            sel.previousSibling.appendChild( document.importNode( sel.closest('.swapi_hcm_body').querySelector(':scope > .SLUGTABLE_TEMPLATE_CLASS__structure').content, true ) );
        }
        else if ( sel.getAttribute('data-swapiwysiwyg') === 'content' ) {

            sel.previousSibling.previousSibling.appendChild( document.importNode( sel.closest('.swapi_hcm_body').querySelector(':scope > .SLUGTABLE_TEMPLATE_CLASS__content').content, true ) );
        }
    });



    // CLICK EVENT - add attribute button was clicked
    // --------------------------------------------------------------------------------------------------
    funcwysiwyg_registerclickevent('.swapi_wysiwyg_flow_attr_add', function(sel, t) {

        sel.previousSibling.appendChild( document.importNode( sel.closest('.swapi_hcm_body').querySelector(':scope > .swapi_wysiwyg_attr_tmpl').content, true ) );
    });



    // CLICK EVENT - remove attribute button was clicked
    // --------------------------------------------------------------------------------------------------
    funcwysiwyg_registerclickevent('.swapi_wysiwyg_attr_remove_btn', function(sel, t) {

        sel.parentNode.remove();
    });



    // CLICK EVENT - reset all button was clicked
    // --------------------------------------------------------------------------------------------------
    funcwysiwyg_registerclickevent('.swapi_wysiwyg_bar_reset_btn', function(sel, t) {

        const content = sel.closest('.SLUGTABLE_TEMPLATE_CLASS__content');

        content.querySelector(':scope [data-swapiwysiwyg="content_format"]').value = '{}';
        content.querySelector(':scope [data-swapiwysiwyg="content_area"]').dispatchEvent(new CustomEvent('input', { bubbles: true }));

        funcwysiwyg_removecontentflow(content);
        funcwysiwyg_allresettrigger(content);
    });



    // CLICK EVENT - focus out was clicked
    // --------------------------------------------------------------------------------------------------
    funcwysiwyg_registerclickevent('.swapi_wysiwyg_structure_root', function(sel, t) {

        if ( t.closest('.SLUGTABLE_TEMPLATE_CLASS__content') === null ) {

            funcwysiwyg_hideopenedfocusbars(sel);
            funcwysiwyg_hideflowselections(sel);
        }
    });



    // CLICK EVENT - flow menu button was clicked
    // --------------------------------------------------------------------------------------------------
    funcwysiwyg_registerclickevent('.swapi_wysiwyg_flow_btn', function(sel, t) {

        const content = sel.closest('.SLUGTABLE_TEMPLATE_CLASS__content');

        if ( content.classList.contains('wysiwyg_focus') && content.classList.contains('wysiwyg_selected') ) {

            let attributes = {};

            content.querySelectorAll(':scope .swapi_wysiwyg_attr_main').forEach(function(e) {

                attributes[e.querySelector(':scope [data-swapiwysiwyg="attr_name"]').value] = e.querySelector(':scope [data-swapiwysiwyg="attr_value"]').value;
            });

            const btnname = sel.getAttribute('data-swapiwysiwyg');

            funcwysiwyg_resetbtnactive(content, [btnname], 1);

            content.querySelector(':scope [data-swapiwysiwyg="content_area"]').dispatchEvent(new CustomEvent('input', { bubbles: true, detail: { 'event': 'EVbutton', 'flowbtnthis': btnname, 'flowbtnattr': attributes } }));
        }
    });

