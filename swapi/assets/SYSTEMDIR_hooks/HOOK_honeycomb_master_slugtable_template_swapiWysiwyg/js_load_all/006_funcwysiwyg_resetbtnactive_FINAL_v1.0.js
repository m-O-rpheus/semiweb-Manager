

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_resetbtnactive = function(content, btnnamesarr, count) {

        // Remove all and Add .wysiwyg_btnactive data-btncount logik

        content.querySelectorAll(':scope .wysiwyg_btnactive').forEach(function(e) {

            e.classList.remove('wysiwyg_btnactive');
        });

        content.querySelector(':scope .swapi_wysiwyg_flow_wrap').setAttribute('data-btncount', count);

        btnnamesarr.forEach(function(i) {
    
            content.querySelector(':scope .swapi_wysiwyg_flow_btn[data-swapiwysiwyg="'+i+'"]').classList.add('wysiwyg_btnactive');
        }); 
    };
    // --------------------------------------------------------------------------------------------------

