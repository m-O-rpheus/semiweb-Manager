

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_autogrowcallbackrange = function(selectionstart, selectionend, val) {

        let o = {};
        let j = 0;

        for (let i = selectionstart; i < selectionend; i++ ) {

            o[funcwysiwyg_indicator(i)] = val[j];
            j++;
        }

        return o;  
    };
    // --------------------------------------------------------------------------------------------------

