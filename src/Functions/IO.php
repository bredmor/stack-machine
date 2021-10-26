<?php

namespace Stackmachine\Functions;

class IO
{
    public static function printASCII(int $byte): void
    {
        echo chr($byte);
    }

    public static function printNum(int $int): void
    {
        echo $int;
    }
}