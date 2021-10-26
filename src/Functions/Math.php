<?php
namespace Stackmachine\Functions;

class Math {
    public static function add_int(int $operand_1, int $operand_2): int
    {
        return $operand_1 + $operand_2;
    }

    public static function sub_int(int $operand_1, int $operand_2): int
    {
        return $operand_2 - $operand_1;
    }
}