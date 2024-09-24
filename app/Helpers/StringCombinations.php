<?php

namespace App\Helpers;

class StringCombinations
{
    public static function generate($length = 3)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $results = [];

        for ($i = 1; $i <= $length; $i++) {
            $results = array_merge($results, self::getCombinations($characters, $i));
        }

        return $results;
    }

    private static function getCombinations($characters, $length, $prefix = '')
    {
        if ($length == 0) {
            return [$prefix];
        }

        $results = [];
        for ($i = 0; $i < strlen($characters); $i++) {
            $results = array_merge($results, self::getCombinations($characters, $length - 1, $prefix . $characters[$i]));
        }

        return $results;
    }
}
