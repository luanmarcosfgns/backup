<?php

namespace App\Util;
class VariableSystem
{
    public static function set()
    {
        define("__APP__", getcwd());
    }
}