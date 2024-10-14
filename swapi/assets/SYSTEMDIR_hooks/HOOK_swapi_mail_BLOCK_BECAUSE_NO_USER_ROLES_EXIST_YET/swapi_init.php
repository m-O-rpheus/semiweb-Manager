<?php


    hook_into_swapi_array('swapi_mail_sendallowed', function( array $passthrough ) {


        /* Da es aktuell noch keine Benutzerrollenkontrolle gibt, dürfen derzeit nur folgende E-Mail Adressen eine E-Mail erthalten */ 
        $allowed = array(
            'beowulf123456@googlemail.com',
            'beowulf123456smarthouse@gmail.com',
            'demo@markus-jaeger.de',
            'markus.jaeger@fyff.net',
            'markusjaeger114@hotmail.com',
            'mjaeger1988@web.de',
        );


        if ( in_array( $passthrough['READONLY_mail_to'], $allowed ) ) {

            $passthrough['return'] = true;
        }
        else {

            $passthrough['return'] = false;
        }

        return (array) $passthrough;

    }, 1 );
    

?>