

    /* 20.10.2022 - Uhrzeit im Element mit der class .paNel_now anzeigen! */
    /* ========================================================================================================================================================== */
    function paNel_now() {

        const now = new Date();
        const get_year    = now.getFullYear();                                    // liefert YYYY
        const get_month   = ('0'+(now.getMonth()+1)).slice(-2);                   // liefert 01 - 12
        const get_date    = ('0'+now.getDate()).slice(-2);                        // liefert 01 - 31
        const get_hours   = ('0'+now.getHours()).slice(-2);                       // liefert 00 - 23
        const get_minutes = ('0'+now.getMinutes()).slice(-2);                     // liefert 00 - 59
        const get_weekday = now.toLocaleString( 'de-de', { weekday: 'long' } );   // liefert Montag - Sonntag

        const sel = swapinode().querySelector(':scope .paNel_now');

        if ( sel !== null ) {

            sel.innerHTML = "<span class='paNel_weekday'>"+get_weekday+"</span><span class='paNel_time'>"+get_hours+":"+get_minutes+"</span><span class='paNel_date'>"+get_date+"."+get_month+"."+get_year+"</span>";
        }
    }

    setInterval(function() {

        paNel_now();

    }, 1000);

    paNel_now();

