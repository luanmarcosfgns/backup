<?php

namespace App\Util;
class VariableSystem
{
    public static function set()
    {
        define($_ENV['APP_DIRECTORIES'], getcwd());
    }
}