<?php
namespace Stackmachine;

class CoreLib
{
    public static function readFile($path): array
    {
        $f = fopen($path, "r");
        $tokens = [];

        while ($line = fgets($f, 1000) )
        {
            $nl = mb_strtolower($line,'UTF-8');
            $tokens[] = trim($nl);
        }

        return $tokens;
    }
}