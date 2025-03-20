<?php
// amountInWords.php

if (!function_exists('numberToWords')) {

    function numberToWords($number): string
    {
        $units = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
        $thousands = ['', 'thousand', 'million', 'billion', 'trillion'];

        if ($number == 0) {
            return 'zero';
        }

        $words = [];
        $chunks = array_reverse(str_split(strrev(strval($number)), 3));

        foreach ($chunks as $i => $chunkStr) {
            $chunk = intval(strrev($chunkStr));
            if ($chunk === 0) continue;

            $chunkWords = [];

            // Centaines
            if ($chunk >= 100) {
                $hundreds = floor($chunk / 100);
                $chunkWords[] = $units[$hundreds] . ' hundred';
                $chunk %= 100;
            }

            // Dizaines
            if ($chunk >= 20) {
                $tensKey = floor($chunk / 10);
                $chunkWords[] = $tens[$tensKey];
                $chunk %= 10;
            }

            // 10-19 et unités
            if ($chunk >= 10) {
                $chunkWords[] = $teens[$chunk - 10];
                $chunk = 0;
            }

            if ($chunk > 0) {
                $chunkWords[] = $units[$chunk];
            }

            // Ajout du suffixe (thousand, million...)
            if (!empty($chunkWords)) {
                $suffix = $thousands[$i];
                if ($suffix) $chunkWords[] = $suffix;
            }

            array_unshift($words, ...$chunkWords);
        }

        return implode(' ', $words);
    }

}