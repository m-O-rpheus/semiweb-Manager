

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_hideflowselections = function(parent) {

        // Remove everything related to the selectionchange event

        parent.querySelectorAll(':scope .wysiwyg_selected').forEach(function(e) {           // [Remove all] .wysiwyg_selected classes

            e.classList.remove('wysiwyg_selected');
        });

        funcwysiwyg_removecontentflow(parent);                                              // [Clean all] .swapi_wysiwyg_content_flow
        
        parent.querySelectorAll(':scope .swapi_wysiwyg_flow_span').forEach(function(e) {    // [Reset all by Trigger Event] on autogrow

            e.closest('.swapi_area_autogrow').querySelector(':scope [data-swapiwysiwyg="content_area"]').dispatchEvent(new CustomEvent('input', { bubbles: true }));
        });
    };
    // --------------------------------------------------------------------------------------------------

