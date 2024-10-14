

const swapinode = mjv_swapi_script.nextSibling.shadowRoot.firstChild;

if ( mjv_swapi_script.src.startsWith( mjv_global_selfuri ) && swapinode && window.location.hostname == mjv_global_customerpage ) {



    /* Window History Window History scrollRestoration Deaktivieren */
    /* ========================================================================================================================================================== */

        if ( history.scrollRestoration ) {
            history.scrollRestoration = 'manual';
        }





    /* FUNKTION: Hilfsfunktionen */
    /* ========================================================================================================================================================== */

        // SetTimeout welche die Parameter Zuverlässig durchreicht!
        const func_swapi_timeout = function( ms, pa, fa ) {
            setTimeout(function(pb, fb) {
                fb(pb);                                                                             
            }, ms, pa, fa);
        };
    

        // Prüft ob der Selector selbst das Element ist, oder ein Kind davon! Liefert "selectiertesElement" oder "null"
        const func_helper_selectorIsSelfOrChildren = function( mjv_str, mjv_sel ) {

            return ( mjv_sel.matches(mjv_str) ? mjv_sel : mjv_sel.closest(mjv_str) );  // sel or null
        };


        // Entfernt alle Elemente welche auf einen CSS Selektor zutreffen.
        const func_helper_stringSelectorAll_removeAll = function( mjv_str ) {

            swapinode.querySelectorAll(mjv_str).forEach(function(e) { 

                e.parentNode.removeChild(e);
            });
        };


        // Prüft ob ein JavaScript Object einen Bestimmten Key besitzt und dieser einen bestimmten Type hat!
        const func_obj_hasKey_and_valueHasType = function( mjv_obj, p1, t1 ) {

            return ( mjv_obj.hasOwnProperty(p1) && typeof mjv_obj[p1] === t1 ); // true|false
        };


        // Setzt für 3 Sekunden eine Klasse .swapi_complete auf das Selektierte Element
        const func_set_swapi_complete = function( mjv_sel ) {

            mjv_sel.classList.add('swapi_complete');

            func_swapi_timeout( 3000, [mjv_sel], function( pa ) {
                pa[0].classList.remove('swapi_complete');      
            });
        };





    /* SESSION: BrowserStorage setzen - Aufgrund der CORS CrossDomain Übertragung ist hier eine JavaScript Lösung notwendig um Daten zu Speichern!. Alte Lösung
                    basierend auf Cookies funktioniert nicht zuverlässig. Neue Lösung basierend auf LocalStorage in folgenden Browsern schon. Getestete Browser:

        - Android Chrome Mobile
        - Android Samsung Internet
        - Android Firefox Mobile
        - Android Edge Canary
        - Android Dolphin Browser (Funktioniert jetzt auch. Alte Lösung basierend auf Cookies ging nicht!)
        - Android Opera
        - Android Opera Touch
        - Android Opera Mini
        - Windows Chrome
        - Windows Firefox
        - Windows Edge (auf Chromium basis)
        - Windows Opera
    /* ========================================================================================================================================================== */
    
        const func_set_or_remove_browserstorage = function( mjv_str ) {

            if ( mjv_str !== '' ) {

                swapinode.classList.add("swapi_cookie");
                localStorage.setItem("swapiSession", mjv_str );
            }
            else {

                swapinode.classList.remove("swapi_cookie");
                localStorage.removeItem("swapiSession");
            }
        };

        const func_get_browserstorage = function() {

            return localStorage.getItem("swapiSession");    // string or null
        };





    /* Übernehme eine vorhandene Session, sofern eine im LocalStorage vorhanden ist..! */
    /* ========================================================================================================================================================== */

        // Wird beim Laden der (swapi.js.php) einmalig ausgeführt!
        mjv_actual_session = func_get_browserstorage() || mjv_actual_session;   // Gibt seinen rechten Operanden zurück, wenn sein linker Operand null oder undefined ist, ansonsten seinen linken Operanden.


        // Wird durch die "CORS.php" getriggert oder durch "error_handling"!
        // "CORS.php"       - Vom Server kommt mit jeder Anfrage immer die aktuelle Session für den browserstorage zurück - Überschreibe!
        // "error_handling" - Bei Fehler setze den browserstorage zurück, auf die Initiale Session "mjv_global_initial_session" - Überschreibe!
        const func_override_browserstorage_and_actual_session_if_exists = function( mjv_str ) {

            mjv_actual_session = mjv_str;                                           // Überschreibe die Globale Variable in (swapi.js.php)

            if ( func_get_browserstorage() !== null ) {                             // Mache nur weiter wenn "swapiSession" im LocalStorage vorhanden ist!

                func_set_or_remove_browserstorage(mjv_actual_session);
            }
        };





    /* Error Handling das Aufgerufen wird wenn beim "Verschlüsseln, Entschlüsseln, CORS Senden, CORS Empfangen" ein Fehler aufgetreten ist! */
    /* ========================================================================================================================================================== */

        const func_error_handling = function() {

            // Bei irgendeinem Fehler, setze "mjv_actual_session" sowie LocalStorage (sofern vorhanden) wieder auf die initiale Session der swapi.js.php!
            func_override_browserstorage_and_actual_session_if_exists( mjv_global_initial_session );

            // HARD RESET - Eventuell hier noch eine "schnellere lösung" finden...!
            location.reload();
            


            /* Angedachte NEUE Lösung anstatt location.reload - Funzt Nicht!!!
            alert("Session abgelaufen! - Starte Neu");

            func_script_start();*/



        };





    /* MJ AES GCM Encrypt und Decrypt eines Strings ( URL safe ) Benötigt eine HTTPS Verbindung! ( https://gist.github.com/chrisveness/43bcda93af9f646d083fad678071b90a ) */
    /* ========================================================================================================================================================== */

        const mjv_aesGCM_compression_stream_support = ( "CompressionStream" in window );

        if ( mjv_aesGCM_compression_stream_support ) {

            swapinode.classList.add("swapi_compressed");
        }



        /* String Verschlüsseln */

        /*mj_aesGCM_encrypt_v5( mjv_obj, "PASSWORT", function( mjv_cipherblob ) {
            console.log( mjv_cipherblob );  // HELPER DEVELOPER AUSGABE
        });*/

        // Neu: mj_aesGCM_encrypt_v5 - Erzeugt aus einem Objekt einen Verschlüsselten Blob, der zusätzlich auch mit GZIP Komprimiert wird, sofern der Browser CompressionStream Unterstützt.
        const mj_aesGCM_encrypt_v5 = function( mjv_obj, mjv_password, mjv_callback ) {

            if ( typeof mjv_obj === 'object' && typeof mjv_password === 'string' && typeof mjv_callback === 'function' ) {          // 1.  Prüfe auf korrekte Parameter!

                const buf   = new TextEncoder('utf-8').encode( JSON.stringify( mjv_obj ) );                                         // 2.  Object zu String zu ArrayBuffer umwandeln
                const ivBin = window.crypto.getRandomValues(new Uint8Array(12));                                                    // 3.  Erzeuge iv (Binary) zufällig!
                const ivHex = Array.from(ivBin).map(function(b) { return b.toString(16).padStart(2,'0'); }).join('');               // 4.  Konvertiere iv (Binary) in iv (Hex String)!
                const alg   = { name: "AES-GCM", iv: ivBin, tagLength: 128 };                                                       // 5.  Festlegen des verwendeten Algorithmus!

                window.crypto.subtle.digest("SHA-256", new TextEncoder('utf-8').encode(mjv_password)).then(function(pw) {           // 6.  Hashe das Passwort erhalte (pw) ArrayBuffer!                   
            
                    window.crypto.subtle.importKey("raw", pw, alg, false, ["encrypt"]).then(function(key) {                         // 7.  Schlüssel aus dem Passwort generieren (encrypt)!          

                        const cb = function(buf) {

                            window.crypto.subtle.encrypt(alg, key, buf).then(function(buf) {                                        // 8.  Plaintext String zu Plaintext ArrayBuffer sowie Verschlüsseln mit dem iv und Passwort (buffer) ArrayBuffer!
       
                                mjv_callback(new Blob([ivHex,new Uint8Array(buf)], { type: 'application/octet-stream' }));          // 10. Den ArrayBuffer mit den Verschlüsselten Daten in einen Blob Umwandeln sowie als 'mjv_cipherblob' Zurückgeben!
    
                            }, mjv_callback);  // error handling
                        };

                        if ( mjv_aesGCM_compression_stream_support ) {                                                              // 9. Gzip CompressionStream Support?

                            new Response( new Response(buf).body.pipeThrough(new CompressionStream('gzip')) ).arrayBuffer().then(function(buf) {

                                cb(buf);

                            }, mjv_callback);  // error handling
                        }
                        else {

                            cb(buf);
                        }

                    }, mjv_callback);  // error handling  

                }, mjv_callback);  // error handling                                                    
            }
            else {
                mjv_callback('');  // error handling
            }
        };


        /* String Entschlüsseln */

        /*mj_aesGCM_decrypt_v5( mjv_cipherblob, "PASSWORT", function( mjv_obj ) {
            console.log( mjv_obj );  // HELPER DEVELOPER AUSGABE
        });*/

        // Neu: mj_aesGCM_decrypt_v5 - Erzeugt aus einem Verschlüsselten Blob ein Objekt. Wenn der Browser CompressionStream unterstützt, wird automatisch und Immer ein GZIP String erwartet. Dies muss entsprechend Serverseitig festgelegt werden!
        const mj_aesGCM_decrypt_v5 = function( mjv_cipherblob, mjv_password, mjv_callback ) {

            if ( mjv_cipherblob instanceof Blob && typeof mjv_password === 'string' && typeof mjv_callback === 'function' ) {       // 1.  Prüfe auf korrekte Parameter!

                mjv_cipherblob.slice(0,24).text().then(function(ivHex) {                                                            // 2.  Aus dem Blob iv (Hex String) herausziehen!

                    const ivBin = new Uint8Array(ivHex.match(/.{1,2}/g).map(function(b) { return parseInt(b, 16); }));              // 3.  Konvertiere iv (Hex String) in iv (Binary)!
                    const alg   = { name: "AES-GCM", iv: ivBin, tagLength: 128 };                                                   // 4.  Festlegen des verwendeten Algorithmus!

                    window.crypto.subtle.digest("SHA-256", new TextEncoder('utf-8').encode(mjv_password)).then(function(pw) {       // 5.  Hashe das Passwort erhalte (pw) ArrayBuffer!     

                        window.crypto.subtle.importKey("raw", pw, alg, false, ["decrypt"]).then(function(key) {                     // 6.  Schlüssel aus dem Passwort generieren (decrypt)!
                            
                            const fr = new FileReader();                                                                            // 7.  Initialisiere FileReader!
                            fr.readAsArrayBuffer(mjv_cipherblob.slice(24));                                                         // 8.  Aus dem Blob einen ArrayBuffer erzeugen!    
                            fr.onload = function(e) {
                            
                                window.crypto.subtle.decrypt(alg, key, e.target.result).then(function(buf) {                        // 9.  Entschlüsseln des ArrayBuffer mit dem iv und Passwort zu Plaintext ArrayBuffer!

                                    const cb = function(buf) {

                                        try {
                                            mjv_callback( JSON.parse( new TextDecoder('utf-8').decode(buf) ) );                     // 11. Plaintext ArrayBuffer zu String zu Object Zurückgeben!
                                        }
                                        catch (e) {
                                            mjv_callback(e);  // error handling
                                        }
                                    };

                                    if ( mjv_aesGCM_compression_stream_support ) {                                                  // 10. Gzip CompressionStream Support?

                                        new Response( new Response(buf).body.pipeThrough(new DecompressionStream('gzip')) ).arrayBuffer().then(function(buf) {

                                            cb(buf);
    
                                        }, mjv_callback);  // error handling
                                    }
                                    else {

                                        cb(buf);
                                    }

                                }, mjv_callback);  // error handling
                            };
                            fr.onerror = function(e) {
                                mjv_callback(e);  // error handling
                            };

                        }, mjv_callback);  // error handling

                    }, mjv_callback);  // error handling

                }, mjv_callback);  // error handling                  
            }
            else {
                mjv_callback('');  // error handling
            }
        };





    /* Globale variablen */
    /* ========================================================================================================================================================== */

    let mjv_CORScbLazyLoadSpeicher = {};                    // JavaScript Speicher, Speichere Alle swapiCORScbLazyLoad hier zwischen!
    let mjv_CORScbLoadCounter = 0;                          // Anfragen Zähler (--load)
    let mjv_ev_swapi_ready_if_full_request_arrived = 0;     // LLcbInit zählen





// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /* #3 - PAGEEVENT: Event aufrufen! */
    /* ========================================================================================================================================================== */

        const func_swapiCORScbLazyLoad_3_event = function( mjv_ev ) {

            if ( mjv_ev_swapi_ready_if_full_request_arrived == 300 ) {  // Addierter Wert kommt von CORS_LL_LazyLoad

                if ( mjv_ev === 'mjv_if_after' ) {

                    // console.log('EVENT swapi_ready');

                    mjv_ev_swapi_ready_if_full_request_arrived = 450;   // Erzeugter Wert von JavaScript
                    swapinode.classList.add('swapi_ready');
                }
            }
            else if ( mjv_ev_swapi_ready_if_full_request_arrived == 450 ) {

                if ( mjv_ev === 'mjv_if_before' ) {

                    // console.log('EVENT swapi_before_pagechange');

                    swapinode.dispatchEvent(new CustomEvent('swapi_before_pagechange'));     // Dieses Event wird vor  jedem Seitenwechsel getriggert, jedoch nich nach dem Neu Laden (ready)!
            }
            else if ( mjv_ev === 'mjv_if_after' ) {

                    // console.log('EVENT swapi_after_pagechange');

                    swapinode.dispatchEvent(new CustomEvent('swapi_after_pagechange'));      // Dieses Event wird nach jedem Seitenwechsel getriggert, jedoch nich nach dem Neu Laden (ready)!
                }
            }
            else {

                func_swapi_timeout( 2, [mjv_ev], function( pa ) {
                    func_swapiCORScbLazyLoad_3_event( pa[0] );    
                });
            }
        };



    /* #2 - PAGEEXECUTE: Ablauf */
    /* ========================================================================================================================================================== */

        const func_swapiCORScbLazyLoad_2_executeAndOpen = function( mjv_LLcbPage, mjv_callTypePassthroughBoolean ) {

            // mjv_callTypePassthroughBoolean = false  --> Neue Section wurde aufgerufen per: "onpopstate"
            // mjv_callTypePassthroughBoolean = true   --> Neue Section wurde aufgerufen per: "LLsAndOpen", "Link Klick"
            
            if ( mjv_LLcbPage !== mjv_global_swapi_root ) {

                func_swapiCORScbLazyLoad_3_event('mjv_if_before');


                // Mache nur weiter wenn bereits .swapi_section da ist ( Beim allerersten Section Load (LLsAndOpen) gibt es noch keine Section, überspringe somit diesen Schritt! )
                // -----------------------------------------------------------------------------------------------------------------------------------------
                if ( swapinode.querySelector(mjv_global_classname_swapi_section) !== null ) {

                    const mjv_str = swapinode.querySelector(mjv_global_classname_swapi_section).dataset.swapiPage;              // Aktuelle .swapi_section Selektieren und Name lesen!

                    if ( mjv_callTypePassthroughBoolean ) {

                        window.history.replaceState({ swapi_history: mjv_str }, '', mjv_global_urlquery + mjv_str );            // Ersetze den HistoryStatus durch die aktuelle .swapi_section
                        window.history.pushState({ swapi_history: mjv_LLcbPage }, '', mjv_global_urlquery + mjv_LLcbPage );     // Hinzufügen des HistoryStatus der neuen .swapi_section
                    }


                    // Durchlaufe die aktuelle .swapi_section und Speichere die Scroll Position vom Container im Speicher ( .swapi_flow )
                    // Nur weitermachen well aktuelle .swapi_section gefunden wurde ( Dies ist nicht der Fall nach Login Logout da dort der Speicher zurückgesetzt wird! ) 
                    if ( func_obj_hasKey_and_valueHasType(mjv_CORScbLazyLoadSpeicher, mjv_str, 'object') ) {                   

                        let mjv_arr = [];

                        swapinode.querySelectorAll(':scope .swapi_scroll>.swapi_flow').forEach(function(e) {

                            mjv_arr.push([parseInt(e.scrollLeft, 10), parseInt(e.scrollTop, 10)]);      // Push Array to Array!
                        });

                        mjv_CORScbLazyLoadSpeicher[mjv_str].CacheScroll = mjv_arr;                      // Add Array to Object
                    }
                    

                    // Alle Sectionen aus dem DOM Löschen - Hinweis: Es gibt immer nur eine Section - Lösche also immer nur diese eine bestehende Section!
                    func_helper_stringSelectorAll_removeAll(mjv_global_classname_swapi_section);
                }


                swapinode.insertAdjacentHTML( 'afterbegin', mjv_CORScbLazyLoadSpeicher[mjv_LLcbPage].CacheHtml );       // Append .swapi_section to DOM


                // Wiederherstellen der vorherigen Scroll Position ( .swapi_flow ) ( Nur bei bei Vor / Zurück Window History )
                // -----------------------------------------------------------------------------------------------------------------------------------------
                if ( !mjv_callTypePassthroughBoolean ) {

                    mjv_CORScbLazyLoadSpeicher[mjv_LLcbPage].CacheScroll.forEach(function(item, i) {

                        swapinode.querySelector(':scope .swapi_scroll:nth-child('+(i+1)+')>.swapi_flow').scrollTo(item[0], item[1]);
                    });
                }


                func_swapiCORScbLazyLoad_3_event('mjv_if_after');                       // Aufrufen des Custom Events "before".
            }
            else {

                swapinode.insertAdjacentHTML( 'beforeend', mjv_CORScbLazyLoadSpeicher[mjv_LLcbPage].CacheHtml );        // Append .swapi_root to DOM
            }


            // Nachdem .swapi_root oder .swapi_section in den DOM geladen wurde - Scanne auf Links und Lade diese Seiten vorab nach (Muss aktuell per JS erfolgen, denn sonst wird die .swapi_root nicht berücksichtigt )
            // -----------------------------------------------------------------------------------------------------------------------------------------
            func_swapi_timeout( 75, [mjv_LLcbPage], function( pa ) {

                let mjv_arr = [pa[0]];

                swapinode.querySelectorAll(':scope [data-swapi-preload]').forEach(function(e) {

                    if ( !mjv_arr.includes(e.getAttribute('data-swapi-preload')) ) {        // Verhindere doppelte Einträge!

                        mjv_arr.push(e.getAttribute('data-swapi-preload'));
                    }
                });

                // Sende alle Seiten die geöffnet werden können, sowie die aktuelle Seite (Diese ist immer das erste Array Element) zu dem Server, für den Preload!
                func_swapiCORS_send_mj_encrypt( mjv_arr.map(function(e) { return { LLsPage: e }; }) );                      // CORS Anfrage stellen
            });
        };



    /* #1 - PAGELOADED: Aktion */

    /* Der globale Speicher mjv_CORScbLazyLoadSpeicher in Javascript dient dazu, bereits heruntergeladene HTML-Seiten zu speichern, damit sie nicht erneut vom Server übertragen werden müssen.
       Wenn eine Seite bereits im Speicher festgehalten wurde, kann die Anwendung direkt auf die gespeicherte Version zugreifen, anstatt sie erneut von dem Server zu laden.
       
       Bevor die Anwendung jedoch weitermacht, muss sichergestellt werden, dass die Seite tatsächlich im Speicher gespeichert wurde. Dies kann durch Überprüfung des Typs des CacheHtml-Elements überprüft werden.
       Wenn es sich um einen String handelt, bedeutet dies, dass die Seite vollständig im Speicher abgelegt wurde. In diesem Fall kann die Anwendung direkt weitermachen. Wenn es jedoch noch kein String ist,
       muss die Anwendung warten, bis die Seite vollständig geladen wurde. */
    /* ========================================================================================================================================================== */

        const func_swapiCORScbLazyLoad_1_waitloopAndOpen = function( mjv_LLcbPage, mjv_callTypePassthroughBoolean ) {

            if ( func_obj_hasKey_and_valueHasType(mjv_CORScbLazyLoadSpeicher, mjv_LLcbPage, 'object') ) {                       // 1 if: Mache nur weiter wenn die Seite bereits im Speicher festgehalten wurde. (Dies bedeutet nicht, das sie schon vollständig gespeichert ist).

                if ( func_obj_hasKey_and_valueHasType(mjv_CORScbLazyLoadSpeicher[mjv_LLcbPage], 'CacheHtml', 'string') ) {      // 2 if: Mache nur weiter wenn 'CacheHtml' type 'string' ist. Das bedeutet die Seite wurde bereits vollständig im Speicher abgelegt.
            
                    func_swapiCORScbLazyLoad_2_executeAndOpen( mjv_LLcbPage, mjv_callTypePassthroughBoolean );                          // ### Ablauf fortsetzen...
                }
                else {                                                                                                          // 2 else: Ansonsten warte bis die Seite vollständig im Speicher heruntergeladen wurde.

                    func_swapi_timeout( 4, [mjv_LLcbPage, mjv_callTypePassthroughBoolean], function( pa ) {                             // ### Warte...
                        func_swapiCORScbLazyLoad_1_waitloopAndOpen( pa[0], pa[1] );   
                    });
                }
            }
            else {                                                                                                              // 1 else: Ansonsten lade die Seite vom Server.

                func_swapiCORS_send_mj_encrypt( [{ LLsPage: mjv_LLcbPage, LLsAndOpen: true }] );                                        // ### CORS Anfrage stellen...
            }
        };


        window.onpopstate = function(e) {                       // Window History GetState - Funktion wird bei Vor / Zurück aufgerufen!

            if ( e.state !== null && e.state.hasOwnProperty("swapi_history") ) {

                func_swapiCORScbLazyLoad_1_waitloopAndOpen( e.state.swapi_history, false );
            }
        };


        swapinode.addEventListener('click', function(e) {       // MODULE_hyperlink --- Click Event - Da es direkt mit dem System Verstrickt ist, ist eine Auslagerung nicht möglich 

            let sel = func_helper_selectorIsSelfOrChildren( '.swapi_local_link', e.target );                                                       // sel or null

            if( sel !== null ) {                                                                                                                   // Mache nur weiter wenn der Selektor (Interner Link) vorhanden ist!

                e.preventDefault();                                                                                                                // Die <a href=""> die Standard Klick Aktion unterbinden!


                // NEU 2023 ------------------------ data-swapi-unload und data-swapi-preload ------------------------
                if ( sel.hasAttribute('data-swapi-unload') ) {

                    func_swapiCORS_send_mj_encrypt( [{ LLsPage: sel.getAttribute('data-swapi-unload'), LLsAndOpen: true, LLsUnload: true }] );
                }
                else if ( sel.hasAttribute('data-swapi-preload') && sel.getAttribute('data-swapi-preload') !== swapinode.querySelector(mjv_global_classname_swapi_section).dataset.swapiPage ) {

                    func_swapiCORScbLazyLoad_1_waitloopAndOpen( sel.getAttribute('data-swapi-preload'), true );
                }
                // NEU 2023 ------------------------ data-swapi-unload und data-swapi-preload ------------------------

            }
        });

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++





    /* FUNKTION: Empfängt die CORS Antwort. Entschlüsselt das ganze und führt dann die entsprechende Aktion aus */
    /* ========================================================================================================================================================== */

        const func_swapiCORScb = function( mjv_cipherblob ) {

            // 1. "Objekt Entschlüsseln"
            mj_aesGCM_decrypt_v5( mjv_cipherblob, mjv_actual_session, function( mjv_obj ) {

                // console.log( mjv_obj );  // HELPER DEVELOPER AUSGABE

                let mjv_callback_without_error = false;
                let mjv_sel                    = null;
                let mjv_elem                   = null;


                if ( typeof mjv_obj === 'object' && func_obj_hasKey_and_valueHasType(mjv_obj, 'cbSTORAGE', 'string') && func_obj_hasKey_and_valueHasType(mjv_obj, 'cbARR', 'object') ) {

                    // Ladebalken -1 Bei allen außer cbObjDetail
                    if ( !(mjv_obj.cbARR.length === 1 && func_obj_hasKey_and_valueHasType(mjv_obj.cbARR[0], 'ODcbOBJ', 'object')) ) { mjv_CORScbLoadCounter--; if ( mjv_CORScbLoadCounter < 1 ) { swapinode.style.removeProperty('--load'); } else { swapinode.style.setProperty('--load', mjv_CORScbLoadCounter); } }
    

                    // Das Array kann bei der Preload Funktion auch Leer sein!
                    if ( mjv_obj.cbARR.length <= 0 ) {

                        mjv_callback_without_error = true;
                    }
                    else {

                        mjv_obj.cbARR.forEach(function(e) {

                            if ( typeof e === 'object' ) {
    
                                // Reihenfolge EXTREM WICHTIG!!! Zuerst 'ODcbOBJ' dann 'FAcbForm' sowie am ende 'LLcbPage'!!!!
    
    
                                // CORS_cbObjDetail
                                // -----------------------------------------------------------------------------------------------------------------------------------------
                                if ( func_obj_hasKey_and_valueHasType(e, 'ODcbOBJ', 'object') ) {

                                    mjv_callback_without_error = true;
                                    swapinode.dispatchEvent(new CustomEvent('swapi_objdetail_callback', { detail: { cb: e.ODcbOBJ } }));
                                }
    
    
                                // CORS_cbFormAction
                                // -----------------------------------------------------------------------------------------------------------------------------------------
                                else if ( func_obj_hasKey_and_valueHasType(e, 'FAcbForm', 'string') && func_obj_hasKey_and_valueHasType(e, 'FAcbAllValid', 'boolean') && func_obj_hasKey_and_valueHasType(e, 'FAcbResult', 'object') ) {
    
                                    func_helper_stringSelectorAll_removeAll(e.FAcbForm + ' .swapi_form_result');                                        // Lösche alle .swapi_form_result sofern Vorhanden!

                                    Object.keys(e.FAcbResult).forEach(function(i) {                                                                     // Loop Durch das Objekt - Nehme das CORS result Objekt und gebe das Rückgabe HTML aus. Valid oder Invalid!
    
                                        mjv_sel = swapinode.querySelector(':scope ' + e.FAcbForm + ' [data-swapi-id="' + i + '"]').parentNode;          // Selektiere das <label> welches ein <input|textarea> Feld enthält
        
                                        mjv_sel.insertAdjacentHTML('afterend', e.FAcbResult[i].html);                                                   // Ausgabe des Valid oder Invalid HTML folgend dem <label> - Wichtig wegen Eingabefeld JSON muss das so sein! Sonst sind Fehlermeldungen dessen nicht Sichtbar! 
                                    });
                                    
                                    if ( e.FAcbAllValid === true ) {
    
                                        func_set_swapi_complete( swapinode.querySelector(':scope ' + e.FAcbForm) );
                                    }
    
                                    mjv_callback_without_error = true;
                                }
    
    
                                // CORS_cbLazyLoad
                                // -----------------------------------------------------------------------------------------------------------------------------------------
                                else if ( func_obj_hasKey_and_valueHasType(e, 'LLcbPage', 'string') && func_obj_hasKey_and_valueHasType(e, 'LLcbHtml', 'string') ) {

                                    // Reihenfolge des Ablaufes zum Einbinden:
                                    // - 1. Einbinden von <style>
                                    // - 2. Einbinden des HTML DOM
                                    // - 3. Einbinden von <script>
    
    
    
                                    // Liefert folgende Reihenfolge der <style> Tags:
    
                                    // - swapi_CORE_shadow_STYLE
                                    // - swapi_SECTION_shadow_STYLE_*
                                    // - ... [Reload weitere swapi_SECTION_shadow_STYLE_* im Verlauf der Session]
                                    // - swapi_ROOT_shadow_STYLE_*
    
                                    // 'CORE'     entspricht "swapi_CORE_shadow_STYLE"
                                    // 'SECTION'  entspricht "swapi_SECTION_shadow_STYLE_*"
                                    // 'ROOT'     entspricht "swapi_ROOT_shadow_STYLE_*"
    
                                    // 1. <style> Elemente anlegen (sofern vorhanden) und in den #shadow-root Schreiben
                                    if ( func_obj_hasKey_and_valueHasType(e, 'LLcbStyle', 'object') ) {
    
                                        Object.keys(e.LLcbStyle).forEach(function(i) {
    
                                            mjv_sel = swapinode.parentNode.querySelector('style[id*="ROOT"]');     // Selektiere das Erste Element, welches die ID "ROOT" enthält (gibt das 1. Element mit der ID _ROOT_ zurück oder null falls nicht vorhanden)
    
                                            if ( i.includes('CORE') ) {
    
                                                mjv_sel = swapinode.parentNode.firstChild;
                                            }
                                            else if ( i.includes('ROOT') || mjv_sel === null ) {
    
                                                mjv_sel = swapinode;
                                            }
    
                                            mjv_sel.insertAdjacentHTML('beforebegin', '<style id="'+i+'">' + e.LLcbStyle[i] + '</style>');
                                        });
                                    }
    
    
                                    // 2. Speicher Reset - Dies wird nur nach dem Formular absenden "_SYSTEM_login" und "_SYSTEM_logout" ausgeführt!
                                    if ( func_obj_hasKey_and_valueHasType(e, 'LLcbRenew', 'boolean') && e.LLcbRenew === true ) {
    
                                        mjv_CORScbLazyLoadSpeicher = {};
                                    }
    
    
                                    // 3. <html> Jede geladene HTML Seite in das JS Array Speichern!
                                    mjv_CORScbLazyLoadSpeicher[e.LLcbPage] = {CacheHtml: e.LLcbHtml, CacheScroll: []};
    
                                    if ( func_obj_hasKey_and_valueHasType(e, 'LLcbAndOpen', 'boolean') && e.LLcbAndOpen === true ) {

                                        func_swapiCORScbLazyLoad_2_executeAndOpen( e.LLcbPage, true );
                                    }
    
    
                                    // 4. Sofern <script> Elemente vorhanden sind. Passthrough bis das HTML in den DOM geladen wurde. Anschließend in den #shadow-root Schreiben
                                    if ( func_obj_hasKey_and_valueHasType(e, 'LLcbScript', 'object') ) {
    
                                        Object.keys(e.LLcbScript).forEach(function(i) {
    
                                            mjv_elem = document.createElement('script');
                                            mjv_elem.id = i;
                                            mjv_elem.setAttribute('charset', 'UTF-8');
                                            mjv_elem.innerHTML = e.LLcbScript[i];
                                            swapinode.parentNode.appendChild(mjv_elem);
                                        });
                                    }
    
    
                                    // 5. Anfrage erfolgreich (bei der Initialen übertragung mit Antwort von drei LazyLoad Seiten wird LLcbInit mit gesendet)
                                    if ( func_obj_hasKey_and_valueHasType(e, 'LLcbInit', 'number') ) {
    
                                        mjv_ev_swapi_ready_if_full_request_arrived += e.LLcbInit;
                                    }
                                    
    
                                    mjv_callback_without_error = true;
                                }
                            }
                            else {
                                func_error_handling();
                            }
                        });
                    }
                }
                

                // 2. Sofern kein Fehler aufgetreten ist setze den Ladebalken -1. Andernfalls kann es sein dass das BrowserStorage Objekt "swapiSession" fehlerhaft ist, oder ein Manipulationsverdacht vermutet wird!
                if ( mjv_callback_without_error ) {

                    func_override_browserstorage_and_actual_session_if_exists( mjv_obj.cbSTORAGE );     // Rufe die funktion auf, diese überschreibt unter anderem die Globale Variable in (swapi.js.php)
                }
                else {
                    func_error_handling();
                }
            });
        };





    /* FUNKTION: Sende via CORS an den Server: Beispiel: func_swapiCORS_send_mj_encrypt( [{test1: "test1", test1: "test1"}] ); */
    /* ========================================================================================================================================================== */

        const func_swapiCORS_send_mj_encrypt = function( mjv_arr ) {

            // console.log( mjv_arr );  // HELPER DEVELOPER AUSGABE

            if ( Array.isArray(mjv_arr) ) {

                // Ladebalken +1 Bei allen außer cbObjDetail
                if ( !(mjv_arr.length === 1 && func_obj_hasKey_and_valueHasType(mjv_arr[0], 'ODsObj', 'object')) ) { mjv_CORScbLoadCounter++; swapinode.style.setProperty('--load', mjv_CORScbLoadCounter); }


                mjv_arr.forEach(function(e) {

                    if ( typeof e === 'object' ) { 

                        // cbLazyLoad - Sofern die Seite NICHT im Objekt gefunden wurde lege einen neuen Datenpunkt im "Globalen JavaScript Speicher" an.
                        // Der Wert ist erst mal Empty Object und wird dann bei erfolgreicher Anfrage zu gefüllt!             
                        if ( func_obj_hasKey_and_valueHasType(e, 'LLsPage', 'string') && !func_obj_hasKey_and_valueHasType(mjv_CORScbLazyLoadSpeicher, e.LLsPage, 'object') ) {

                            mjv_CORScbLazyLoadSpeicher[e.LLsPage] = {};   // Leeres Object = Pending
                        }
                    }
                    else {
                        func_error_handling();
                    }
                });

                let mjv_str = mjv_actual_session.split(".")[0];     // Enthält "sid_encrypted" bis zum Punkt . Das gesammte mjv_actual_session ist der AES Key

                // Sende folgende Eigenschaften verschlüsselt mit!
                // s:  Session ID
                // c:  Um Welchen Kunden handelt es sich? ( bsp.: Webadresse markus-jaeger.de )
                // t:  Timestamp in ms ( Doppelt identische CORS Bugfix )
                // a:  Array mit den Datensatz
                // gz: Browser besitzt CompressionStream Support?


                // "Objekt in JSON-String" Konvertieren -> Dannach "Objekt Verschlüsseln" - AES PWD ist in der Datei swapi.js.php definiert!
                mj_aesGCM_encrypt_v5( { s: mjv_str, c: mjv_global_customerpage, t: String(Date.now()), a: mjv_arr, gz: mjv_aesGCM_compression_stream_support }, mjv_actual_session, function( mjv_cipherblob ) {

                    if ( mjv_cipherblob instanceof Blob ) {

                        let fd = new FormData();
                        fd.append('b', mjv_cipherblob, 'swapi.bin');    // Entspricht input file binary
                        fd.append('s', mjv_str);                        // Entspricht input text

                        let x  = new XMLHttpRequest();           
                        x.open("POST", mjv_global_selfuri + "assets/cors/CORS.php", true);
                        x.onreadystatechange = function() {
                        
                            if(x.readyState == 4 && x.status == 200) {

                                let cd = x.getResponseHeader('Content-Disposition');
                                if (cd && cd.includes('inline') && cd.includes('filename') && cd.includes('swapi.bin')) {
                                    
                                    func_swapiCORScb(x.response);
                                }
                                else {
                                    func_error_handling();
                                }
                            }
                            else {
                                // func_error_handling hier aufrufen klappt nicht!
                            }
                        };
                        x.withCredentials = false;
                        x.responseType = 'blob';
                        x.overrideMimeType("application/octet-stream");
                        x.send(fd);
                    }
                    else {
                        func_error_handling();
                    }
                });
            }
            else {
                func_error_handling();
            }
        };





    /* MODULE: MODULE_honeycomb_buzz slugtable_template_swapiForms --- Da es direkt mit dem System Verstrickt ist, ist eine Auslagerung nicht möglich */
    /* ========================================================================================================================================================== */

        const func_async_input_files_loop = function( mjv_all_files, i, files, mjv_obj ) {
            
            let reader = new FileReader();

            reader.readAsDataURL( mjv_all_files[i][1] );
            reader.onload = function(e) {                                                           // Asyncrones Event

                files.push( [ mjv_all_files[i][0], mjv_all_files[i][1].name, e.target.result ] );   // Hinzufügen zum finalen "mjv_all_files" array!    [ [0] InputFeldName, [1] Dateiname, [2] Base64 Kodierte Datei ]

                if ( typeof mjv_all_files[(i + 1)] === 'object' ) {                                 // Nächster Array Index vorhanden, mache weiter...

                    func_async_input_files_loop( mjv_all_files, (i + 1), files, mjv_obj );
                }
                else {                                                                              // Sofern der nächste Array Index nicht mehr vorhanden ist -> Abbruch!  

                    mjv_obj.FAsFiles = files;

                    func_swapiCORS_send_mj_encrypt( [mjv_obj] );                                    // Eingabefelder mittels CORS absenden!
                }
            };
        };

        swapinode.addEventListener('click', function(e) {

            const sel = func_helper_selectorIsSelfOrChildren( '.swapi_submit', e.target );   // sel or null

            if( sel !== null ) {

                e.preventDefault();

   
                // Objekt Inititalisieren ( Alles was sich im Objekt befindet wird mit dem Formular mit gesendet )
                let mjv_obj = {FAsForm: sel.name, FAsFields: {}};
                            

                // 1. Springe zurück zum <form> Element und finde alle darin enthaltenen Eingabefelder ".swapi_field" in der Schleife
                let mjv_sel = sel.parentNode.querySelectorAll(':scope [data-swapi-id]');

                mjv_sel.forEach(function(e) {

                    if ( e.classList.contains('swapi_field_selection') ) {                      // Ist es ein input type "checkbox,radio" verwende "checked" um den Inhalt auszulesen

                        mjv_obj.FAsFields[e.dataset.swapiId] = e.checked.toString();            // Hinzufügen der "data-swapi-id" + dessen Wert zum Objekt.
                    }
                    else if ( e.classList.contains('swapi_field_files') ) {                     // Ist es ein input type "file"

                        mjv_obj.FAsFields[e.dataset.swapiId] = e.files.length.toString();       // Hinzufügen der "data-swapi-id" und die anzahl der Hochgeladenen Dateien
                    }
                    else {                                                                      // Ist es ein input type "text,email,number,range,tel,url,search,password,textarea" verwende "value" um den Inhalt auszulesen
    
                        mjv_obj.FAsFields[e.dataset.swapiId] = e.value.toString();              // Hinzufügen der "data-swapi-id" + dessen Wert zum Objekt.
                    }
                });


                // 2. Springe erneut zurück zum <form> Element und finde jetzt aber NUR alle darin enthaltenen input type="file" felder. -> Splitte diese so auf, das auch bei mehreren input type="file" alle Dateien in einem Array liegen!
                let mjv_all_files = [];
                
                mjv_sel = sel.parentNode.querySelectorAll(':scope .swapi_field_files[data-swapi-id]');

                mjv_sel.forEach(function(e) {

                    if ( e.files.length > 0 ) {                                                 // Mache nur weiter, wenn das input file mehr als eine Datei enthält

                        for( let i = 0; i < e.files.length; i++ ) {

                            mjv_all_files.push( [e.dataset.swapiId, e.files[i]] );              // [ [0] InputFeld data-swapi-id, [1] instanceOfFile ]
                        }
                    }
                });

                
                if ( mjv_all_files.length > 0 ) {   // ### Es gibt gefüllte input type="file" Felder! (Sende alle Formularfelder Async)

                    func_async_input_files_loop( mjv_all_files, 0, [], mjv_obj );
                }
                else {                              // ### Es gibt kein input type="file" Feld, oder falls es welche gibt sind diese leer! (Sende alle Formularfelder Sync)

                    func_swapiCORS_send_mj_encrypt( [mjv_obj] );                                // Eingabefelder mittels CORS absenden!
                }

            }
        });





    /* MODULE: MODULE_browserstorage_form --- Da es direkt mit dem System Verstrickt ist, ist eine Auslagerung nicht möglich */
    /* ========================================================================================================================================================== */

        swapinode.addEventListener('click', function(e) {

            let sel = func_helper_selectorIsSelfOrChildren( '.swapi_cookie_set', e.target );   // sel or null
            
            if( sel !== null ) {

                if ( sel.dataset.swapiEv == 'add' ) {

                    func_set_or_remove_browserstorage(mjv_actual_session);
                }
                else if ( sel.dataset.swapiEv == 'del' ) {

                    func_set_or_remove_browserstorage('');      
                }

                func_set_swapi_complete( sel.parentNode );
            }
        });





    /* FUNKTION: SWAPI Script Starten */
    /* ========================================================================================================================================================== */

        const func_script_start = function() {

            let mjv_str = new URLSearchParams(window.location.search);

            mjv_str = ( ( mjv_str.has(mjv_global_queryname) ) ? mjv_str.get(mjv_global_queryname) : '' );           // Die Seite aus dem GET Parameter oder Empty String

            func_swapiCORS_send_mj_encrypt( [{ LLsPage: mjv_str, LLsInit: Date.now(), LLsTag: mjv_swapi_tag }] );   // CORS Anfrage stellen    

        };

        func_script_start();





    /* EXTERNE FUNKTION: Sende ein Objekt von Client zum Server, sowie erhalte die Antwort in dem Event "swapi_objdetail_callback" */
    /* ========================================================================================================================================================== */

        window.swapi_objdetail_submit = function swapi_objdetail_submit( mjv_obj ) {

            func_swapiCORS_send_mj_encrypt( [{ ODsObj: mjv_obj }] );
        };



}

