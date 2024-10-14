<?php


/* Erzeugt die URL der Unterseiten */
/* ========================================================================================================================================================== */

    function mj_generate_urlquery( string $domain, string $queryname = '', string $page = '' ): string {

        /* Erklärung:

            $domain     hängt bei '?page=' mit an dem <script> Tag an und legt die Kunden Seite fest, welche aufgerufen werden soll.
            $queryname  wird in der config definiert
            $page       ist Optional und legt die Seite fest welche an den Parameter angehängt wird!

        */

        // NOCH MACHEN - Kommentare überarbeiten

        if ( empty($queryname) && empty($page) ) {

            return (string) 'https://' . $domain . '/';
        }
        else {

            return (string) 'https://' . $domain . '/?' . $queryname . '=' . $page;
        }



        // Sollen weitere GET Parameter verwendet werden, so muss der Aufbau wie folgt sein!
        
        //return (string) 'https://' . $domain . '/?test1=hallo1&test2=hallo2&' . $queryname . '=' . $page;

    }



?>