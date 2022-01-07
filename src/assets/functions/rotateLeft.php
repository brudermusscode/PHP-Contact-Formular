<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/functions.php";

header('Content-type: application/json');

// rotate given array by d times
function rotateLeft($d, $array)
{

    /* // rotate it
    for ($i = 0; $i < $d; $i++) {

        // save the first element of the array
        $first = $array[0];

        // perform the actual rotation
        for ($x = 0; $x < count($array) - 1; $x++) {

            // add 1 to every array index, so it shifts to the left
            $array[$x] = $array[$x + 1];
        }

        // place the first element of array at
        // the end of the array
        $array[$x] = $first;
    }

    return $array; */


    for ($i = 0; $i < $d; $i++) {
        array_push($array, array_shift($array));
    }

    return $array;
}

// output for js script
$errorInformation = [
    "status" => false,
    "message" => "fill 0ut all forms",
    "collection" => []
];

define("OUTPUT_PATH", $_SERVER["DOCUMENT_ROOT"] . "/test-justin/files/output.txt");

if (isset($_REQUEST["array"], $_REQUEST["d"]) && !empty($_REQUEST["array"]) && !empty($_REQUEST["d"])) {

    $d = intval($_REQUEST["d"]);
    $str = $_REQUEST["array"];
    $array = explode(" ", $str);

    // set file path to write to
    $fptr = fopen(OUTPUT_PATH, "w");

    // temporarily save the string (not array)
    $arr_temp = rtrim($str);

    // format array values into ints
    $array = array_map('intval', preg_split('/ /', $arr_temp, -1, PREG_SPLIT_NO_EMPTY));

    // rotate it
    $result = rotateLeft($d, $array);

    // create string from array
    $result = implode(" ", $result);

    // write to file
    fwrite($fptr, $result . "\n");
    fclose($fptr);

    // output
    $errorInformation["status"] = true;
    $errorInformation["message"] = $result;
    exit(json_encode($errorInformation));
} else {
    exit(json_encode($errorInformation));
}
