<?php

namespace Stackmachine;

class Errors
{
    const ERR_UNKNOWN_INSTRUCTION = 'Abort: Unknown Instruction';
    const ERR_UNDEFINED_VARIABLE = 'Abort: Variable Undefined';
    const ERR_LABEL_DEFINED = 'Pre-Abort: Label already defined.';

    const ABORT_LEVEL_ERRORS = [
        self::ERR_UNKNOWN_INSTRUCTION,
        self::ERR_UNDEFINED_VARIABLE
    ];

    public static function doError(string $error, ?string $context = null): void
    {
        echo $error . PHP_EOL;
        if($context) {
            echo $context . PHP_EOL;
        }

        if(in_array($error, self::ABORT_LEVEL_ERRORS)) {
            exit();
        }
    }
}