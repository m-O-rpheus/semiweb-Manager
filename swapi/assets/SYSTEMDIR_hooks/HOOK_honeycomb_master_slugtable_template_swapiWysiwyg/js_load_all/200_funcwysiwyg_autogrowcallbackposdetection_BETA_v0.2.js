

    // --------------------------------------------------------------------------------------------------
    const funcwysiwyg_autogrowcallbackposdetection = function(thisval, prevval) {

        /*
            ERKLÄRUNG:

                0: Range aus [8] und [9]
                1: Prüfe auf Vage Präzision            precision_precise | precision_vague    - 'precision_precise' ist Gut
                2: Prüfe auf Länge unterschiedlich     length_same       | length_different   - 'length_same'       ist Gut
                3: Name der Action
                4: Priorisierte Länge wieviele Zeichen wurden 'added' oder 'removed'. Bei 'consistent' ist immer 0.

                5: (String wird zu einem Character Array Umgewandelt) Größeres Array von beiden! 
                6: (String wird zu einem Character Array Umgewandelt) ltr Array. Dieses wird über die for Schleife von LeftToRight gelesen
                7: (String wird zu einem Character Array Umgewandelt) rtl Array. Dieses wird über die for Schleife von RightToLeft gelesen
                8: Position Platzhalter ltr Index
                9: Position Platzhalter rtl Index

            ACHTUNG: Diese Lösung erzeugt eine ungenaue Positionsbestimmung. Anders lässt es sich derzeit nicht beheben!
        */


        // #1 Basis Array anlegen
        let thisvallen = thisval.length;
        let prevvallen = prevval.length;
        let mstrarr    = [];

        if (thisvallen > prevvallen) {

            mstrarr    = [ [], 'precision_precise', 'length_same', 'added', (thisvallen - prevvallen), Array.from(thisval), Array.from(prevval), Array.from(prevval), -1, -1 ];
        }
        else if (prevvallen > thisvallen) {

            mstrarr    = [ [], 'precision_precise', 'length_same', 'removed', (prevvallen - thisvallen), Array.from(prevval), Array.from(thisval), Array.from(thisval), -1, -1 ];
        }
        else {

            mstrarr    = [ [], 'precision_precise', 'length_same', 'consistent', 0, Array.from(thisval), Array.from(prevval), Array.from(prevval), -1, -1 ];
        }

        let largestlen = mstrarr[5].length;



        // #2 Fülle die zwei Array Elemente 'ltr[6]' und 'rtl[7]' bei 'added' und 'removed' auf die volle länge auf!
        while (largestlen > mstrarr[6].length) {
            mstrarr[6].push(null);
        }

        while (largestlen > mstrarr[7].length) {
            mstrarr[7].unshift(null);
        }

        

        // #3 Initialisieren der index Variablen sowie Schleife von LeftToRight (ltr) und Schleife von RightToLeft (rtl)
        for (let i = 0; i < largestlen; i++) {

            if (mstrarr[5][i] !== mstrarr[6][i]) {

                mstrarr[8] = i;
                break;
            }
        }

        for (let i = largestlen - 1; i >= 0; i--) {

            if (mstrarr[5][i] !== mstrarr[7][i]) {

                mstrarr[9] = i;
                break;
            }
        }



        // #4 Prüfe auf Vage Präzision...
        if ( mstrarr[8] > mstrarr[9] ) {

            mstrarr[1] = 'precision_vague';
        }

        let min = Math.min(mstrarr[8], mstrarr[9]);
        let max = Math.max(mstrarr[8], mstrarr[9]);
        mstrarr[8] = min;
        mstrarr[9] = max;



        // #5 UNGENAUIGKEIT AUSGLEICHEN: Bei Vage Präzision - Vergrößere den Range Bereich, aller Möglichkeiten!
        if ( mstrarr[1] === 'precision_vague' ) {

            mstrarr[8] = (mstrarr[8] - (mstrarr[4] - 1));
            mstrarr[9] = (mstrarr[9] + (mstrarr[4] - 1));
        }



        // #6 Range aus [8] und [9] erzeugen - Wenn die Position nicht genau ermittelt werden kann. Liefere ein längeres Array an möglichen Positionen.
        for (let i = mstrarr[8]; i <= mstrarr[9]; i++) {

            mstrarr[0].push(funcwysiwyg_indicator(i));
        }



        // #7 Prüfe auf Länge unterschiedlich...
        if ( mstrarr[0].length !== mstrarr[4] ) {

            mstrarr[2] = 'length_different';
        }



        // #8 Reset da diese Elemente nicht mehr benötigt werden.
        mstrarr[5] = null;
        mstrarr[6] = null;
        mstrarr[7] = null;
        mstrarr[8] = null;
        mstrarr[9] = null;



        // #9 Return
        return mstrarr;
    };
    // --------------------------------------------------------------------------------------------------

