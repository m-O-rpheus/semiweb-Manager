<?php


/* MJ Mail klein Templates */
/* ========================================================================================================================================================== */

    /* TEMPLATE: Verlinkten Button einfügen! */
    function swapi_mail_TEMPLATE_button( string $text, string $href ): string {

        /* Hinweis: Damit Links in Outlook Funktionieren, müssen die Attribute in alphabetischer Reihenfolge angeordnet werden. class="" href="" id="" style="" target="" title="" */
        /* Hinweis: Der <div> darf kein style Attribute haben. Denn Sonst funktioniert der Link ebenfalls nicht */
        return (string) '<div><a class="swapi_hyperlink" href="'.$href.'" style="margin:0px;padding:0px;border:none;display:inline-block;background-color:red;width:auto;" target="_blank" title="'.$text.'">'.$text.'</a></div>';
    }


    /* TEMPLATE: Einen Zeilenumbruch einfügen, odere mehrere. Dies wird mit der Variable "$anzahl" festgelegt. */
    function swapi_mail_TEMPLATE_br( int $anzahl = 1 ): string {

        $br = '';

        for ($i = 1; $i <= $anzahl; $i++) {

            $br .= '&nbsp;<br aria-hidden="true">&nbsp;';
        }

        return (string) str_replace( '&nbsp;&nbsp;', '&nbsp;', $br );
    }





/* MJ Mail Grundgerüst */
/*

    Diese funktion kann für 2 Möglichkeiten genutzt werden.

    Um eine E-Mail zu senden:
        swapi_mail( 'subject', 'content', 'markusjaeger114@hotmail.com' );
        return (string) '';

    Oder um die HTML Mail Vorlage in der Webseite einzubinden:
        swapi_mail( 'subject', 'content' );
        return (string) {HTMLDOM};

*/
/* ========================================================================================================================================================== */

    function swapi_mail( string $subject, string $tbody, string $to = '' ): string {

        $send_mail = (bool) ( !empty( $to ) && swapi_validate_email( $to ) );


        $content = array(
            'subject' => $subject,
            'thead'   => '',
            'tbody'   => $tbody,
            'tfoot'   => '',
        );
        
        /* hook */ $content = (array) register_swapi_hook_point_passthrough_array( 'swapi_mail_content', ['return' => $content, 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return', 'READONLY_mail_to' => $to] )['return'];

        $subject  = (string) trim($content['subject']);

        if ( $send_mail === true ) {

            $tag  = (string) 'body';
            $attr = (array) ['class' => ['swapi_mail_main']];
        }
        else {

            $tag  = (string) 'article';
            $attr = (array) ['class' => ['swapi_mail_main'], 'data-swapi-subject' => $subject];
        }


        $msg_body = (string) '<' . $tag . swapi_prepare_attribute( $attr ) . '>' . MODULE_table_tabularasa( [], [[ MODULE_table_tabularasa( [[trim($content['thead'])]], [[trim($content['tbody'])]], [[trim($content['tfoot'])]], '_SYSTEM_mail-inner' ) ]], [], '_SYSTEM_mail-outer' ) . '</' . $tag . '>';
        $msg_body = str_replace( "'", '"', $msg_body );                                                                             // E-Mail kompatibilität: Das einfache Hochkomma in ein Doppeltes ersetzen.
        $msg_body = str_replace( array( '<thead>', '</thead>', '<tbody>', '</tbody>', '<tfoot>', '</tfoot>' ), '', $msg_body ) ;    // E-Mail kompatibilität: Entferne thead, tbody, tfoot. Funzt nur in Kombination mit Hook


        if ( $send_mail === true ) {

            /* hook */ $sendallowed = (bool) register_swapi_hook_point_passthrough_array( 'swapi_mail_sendallowed', ['return' => true, 'READONLY_return_type' => 'bool', 'READONLY_return_array_key' => 'return', 'READONLY_mail_to' => $to] )['return'];

            if ( $sendallowed === true ) {


                $headers = array(
                    'From' => 'semiweb.eu <admin@semiweb.eu>',
                    'Reply-To' => 'semiweb.eu <admin@semiweb.eu>',
                    'X-Sender' => 'semiweb.eu <admin@semiweb.eu>',
                    'X-Mailer' => 'PHP/' . phpversion(),
                    'X-Priority' => '1',
                    'Return-Path' => 'admin@semiweb.eu',
                    'MIME-Version' => '1.0',
                    'Content-Type' => 'text/html; charset=UTF-8'
                );

                $meta = array(
                    '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">',
                    '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
                    '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">',
                );

                /* hook */ $headers = (array) register_swapi_hook_point_passthrough_array( 'swapi_mail_headers', ['return' => $headers, 'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return', 'READONLY_mail_to' => $to, 'READONLY_mail_subject' => $subject] )['return'];
                /* hook */ $meta    = (array) register_swapi_hook_point_passthrough_array( 'swapi_mail_meta',    ['return' => $meta,    'READONLY_return_type' => 'array', 'READONLY_return_array_key' => 'return', 'READONLY_mail_to' => $to, 'READONLY_mail_subject' => $subject] )['return'];

                $msg_all  = (string) '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
                $msg_all .= (string) '<html lang="en-US">';
                    $msg_all .= (string) '<head>';
                        $msg_all .= (string) implode( '', $meta );
                        $msg_all .= (string) '<title>' . $subject . '</title>';
                    $msg_all .= (string) '</head>';
                    $msg_all .= (string) $msg_body;
                $msg_all .= (string) '</html>';


                $result = (bool) false;

                if ( MySQL_TABLE_users_append_user_emailnotification( $to, array( 'to' => $to, 'headers' => $headers, 'subject' => $subject, 'message' => $msg_all ) ) ) {  // Write complete e-mail to database!

                    $result = (bool) mail( $to, $subject, $msg_all, $headers );                                                                                             // Send PHP Mail
                }
                
                if ( $result ) {
    
                    MySQL_TABLE_errorlog_save( 'swapi_mail.php', 'Die E-Mail wurde efolgreich abgesendet.', '' );
                }
                else {
    
                    MySQL_TABLE_errorlog_save( 'swapi_mail.php', 'Fehler: Die E-Mail konnte nicht abgesendet werden.', '' );
                }
            }
            else {

                MySQL_TABLE_errorlog_save( 'swapi_mail.php', 'Fehler: Mail Senden ' . $to . ' durch HOOK swapi_mail_sendallowed blockiert.', '' );
            }
        }
        else {

            return (string) $msg_body;
        }

        return (string) '';
    }


?>