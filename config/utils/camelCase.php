<?php
// camelCase.php

use Illuminate\Support\Str;

if (!function_exists('arrayKeysToCamelCase')) {

    function arrayKeysToCamelCase($data): array
    {
        // Gère les objets et tableaux
        $data = is_object($data) ? (array)$data : $data;
        
        $result = [];
        foreach ($data as $key => $value) {
            // Conversion camelCase avec Str::camel()
            $newKey = Str::camel($key);

            // Appel récursif protégé
            if (is_array($value) || is_object($value)) {
                $value = arrayKeysToCamelCase($value);
            }

            $result[$newKey] = $value;
        }
        return $result;
    }

}