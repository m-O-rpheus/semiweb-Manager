

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_autogrowcallbackmark = function(flowstart, flowbetween, flowend, selectionstart, selectionend) {

        return flowstart + '<mark class="swapi_wysiwyg_flow_mark" data-wysiwyg-selectionstart="' + selectionstart + '" data-wysiwyg-selectionend="' + selectionend + '">' + flowbetween + '</mark><span class="swapi_wysiwyg_flow_span"></span>' + flowend;
    };
    // --------------------------------------------------------------------------------------------------

