

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_autogrowcallbackfrontend = function(content, str) {

        str = str.replace(/\r\n|\r|\n/g, '<br>');

        str = str.replace(/<i&([^>]*?)&>(.*?)<\/i>/g, function(x, p1, p2) {

            let this_decode = JSON.parse(atob(p1));
            let this_tag    = this_decode[0].replace('flow_tag_', '');
            let this_attr   = '';

            Object.keys(this_decode[1]).forEach(function(i) {

                this_attr += ' ' + funcwysiwyg_htmlspecialchars(i) + '="' + funcwysiwyg_htmlspecialchars(this_decode[1][i]) + '"';
            });

            return '<' + this_tag + this_attr + '>' + p2 + '</' + this_tag + '>';
        });

        str = str.replace(/<i>(.*?)<\/i>/g, function(x, p1) {

            return p1;
        });

        content.querySelector(':scope [data-swapiwysiwyg="content_final"]').value = str;
    };
    // --------------------------------------------------------------------------------------------------

