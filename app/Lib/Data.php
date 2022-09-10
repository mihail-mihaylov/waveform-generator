<?php

namespace App\Lib;

class Data
{
    public static function dump($variable): void
    {
        echo "<pre>";
        print_r($variable);
        echo "</pre>";
    }

    public static function dd($variable): void
    {
        self::dump($variable);
        die;
    }
}
