

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_hideopenedfocusbars = function(parent) {

        parent.querySelectorAll(':scope .wysiwyg_focus').forEach(function(e) {              // [Remove all] .wysiwyg_focus classes

            e.classList.remove('wysiwyg_focus');
        });
        
        parent.querySelectorAll(':scope .swapi_wysiwyg_content_bar').forEach(function(e) {  // [Clean all] .swapi_wysiwyg_content_bar

            e.innerHTML = '';
        });
    };
    // --------------------------------------------------------------------------------------------------

