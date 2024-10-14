

    swapinode().addEventListener('change', function(e) {

        if( e.target && e.target.classList.contains('swapi_field_file') ) {

            const parentsel = e.target.parentNode.parentNode;

            if ( parentsel.classList.contains('swapi_generate_svg2swapifile') ) {

                const file     = e.target.files[0];
                const filename = file.name.substr(0, file.name.lastIndexOf('.')).replace(/[^a-zA-Z0-9]/g, "");
                const reader   = new FileReader();

                reader.readAsDataURL(file); 
                reader.onload = function(e) {

                    const b64 = e.target.result; 

                    const selector = parentsel.querySelector(':scope [data-field="selector"]').value;
                    const naming   = parentsel.querySelector(':scope [data-field="naming"]').value;

                    const str = selector+"{--"+naming+"-svg-"+filename+":url('"+b64+"')}";

                    var elem = document.createElement('a');
                    elem.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(str));
                    elem.setAttribute('download', '000_svg_'+naming+'-'+filename+'.css');
                    elem.style.display = 'none';

                    document.body.appendChild(elem);

                    elem.click();

                    document.body.removeChild(elem);
                };
            }
        }
    });

