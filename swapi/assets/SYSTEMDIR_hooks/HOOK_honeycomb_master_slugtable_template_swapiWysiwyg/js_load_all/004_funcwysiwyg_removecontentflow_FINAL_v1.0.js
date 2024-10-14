

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_removecontentflow = function(content_or_parent) {

        content_or_parent.querySelectorAll(':scope .swapi_wysiwyg_content_flow').forEach(function(e) {

            e.innerHTML = '';
        });
    };
    // --------------------------------------------------------------------------------------------------

