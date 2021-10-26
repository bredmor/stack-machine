<?php
require "vendor/autoload.php";

$function_map = include('functionmap.php');
$tokens = \Stackmachine\CoreLib::readFile($argv[1]);
$stack = [];
$token = '';
$ip = 0;
$labels = [];
$variables = [];

// Map labels
while($ip < count($tokens)) {
    if(preg_match("/^label\\((.+)\\)$/", $token, $parts)) {
        $label = $parts[1];
        if(array_key_exists($label, $labels)) {
            \Stackmachine\Errors::doError(\Stackmachine\Errors::ERR_LABEL_DEFINED, $label);
        } else {
            $labels[$label] = $ip;
        }
    }
    $ip ++;
    reset($stack);
    reset($tokens);
}

$ip = 0;

// Evaluate
while($ip < count($tokens)) {
    $token = $tokens[$ip];
    $parts = [];
    if (preg_match("/^label\\((.+)\\)$/", $token, $parts)) { // LABEL
        #ignore (these are pre-evaluated)
    } elseif(preg_match("/^#(.+)$/", $token)) { // COMMENT
        #ignore
    } elseif (preg_match("/[0-9]([0-9])*/", $token)) { // INT
        // Value for stack
        array_push($stack, (int)$token);
    } elseif (array_key_exists($token, $function_map)) { // Lib Functions
        $operands = [];
        $reflectedFunction = new \ReflectionMethod($function_map[$token]);

        // Get operands
        $arity = $reflectedFunction->getNumberOfParameters();
        while ($arity > 0) {
            $operands[] = array_pop($stack);
            $arity--;
        }

        // Get the result of the function
        $result = $function_map[$token](...$operands);

        // If non-IO method
        if ($result) {
            // Add the result back to the top of the stack
            array_push($stack, $result);
        }
    } elseif (preg_match("/^jmp\\((.+)\\)$/", $token, $parts)) { // JMP
        if (array_key_exists($parts[1], $labels)) {
            // Jump to defined label
            $ip = $labels[$parts[1]];
        } else {
            // Label does not exist
        }
    } elseif (preg_match("/^jz\\((.+)\\)$/", $token, $parts)) { // JZ
        if (array_key_exists($parts[1], $labels)) {
            // If zero
            if (array_pop($stack) == 0) {
                // Jump to defined label
                $ip = $labels[$parts[1]];
            }
        } else {
            // Label does not exist
        }
    } elseif (preg_match("/^dup\\((.+)\\)$/", $token, $parts)) { // DUP
        array_push($stack, $stack[($ip + 1)]);
    } elseif (preg_match("/^setv\\((.+)\\)$/", $token, $parts)) { // SETV
        $variables[$parts[1]] = array_pop($stack);
    } elseif (preg_match("/^getv\\((.+)\\)$/", $token, $parts)) { // GETV
        if(isset($variables[$parts[1]])) {
            array_push($stack, $variables[$parts[1]]);
        } else {
            \Stackmachine\Errors::doError(\Stackmachine\Errors::ERR_UNDEFINED_VARIABLE, $parts[1]);
        }
    } else {
        // Unknown instruction
        \Stackmachine\Errors::doError(\Stackmachine\Errors::ERR_UNKNOWN_INSTRUCTION, $tokens[$ip]);
    }
    $ip++;
    $count = count($tokens);
}