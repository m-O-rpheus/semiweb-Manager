

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_allresettrigger = function(content) {

        if ( content.querySelector(':scope [data-swapiwysiwyg="content_format"]').value === '{}' ) {

            content.querySelector(':scope .swapi_wysiwyg_bar_reset_btn').classList.add('wysiwyg_allisreset');
        }
        else {
        
            content.querySelector(':scope .swapi_wysiwyg_bar_reset_btn').classList.remove('wysiwyg_allisreset');
        }
    };
    // --------------------------------------------------------------------------------------------------

    