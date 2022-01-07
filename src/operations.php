<?php

/*
 * Complete the 'getMax' function below.
 *
 * The function is expected to return an INTEGER_ARRAY.
 * The function accepts STRING_ARRAY operations as parameter.
 */

function getMax($operations)
{

    $n = array_shift($operations);
    $stack = [];

    for ($i = 0; $i < $n; $i++) {

        // explode it into an actual array
        $o = explode(" ", $operations[$i]);

        switch ($o[0]) {
            case 1:
                array_push($stack, $o[1]);
                break;
            case 2:
                array_pop($stack);
                break;
            case 3:
                max($stack);
        }
    }

    return $stack;
}


$operations = ["10", "1 97", "2", "1 20", "2", "1 26", "1 20", "2", "3", "1 91", "3"];

$res = getMax($operations);

echo (implode(" ", $res));
