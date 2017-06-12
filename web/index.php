<?php
require_once "Node.php";
require_once "SOM.php";

/**
 * @param int $rows
 * @return array
 */
function getRandomData($rows, $length = 3)
{
    $data = [];
    for ($i = 0; $i < $rows; $i++) {
        $data[$i] = [];
        for ($j = 0; $j < $length; $j++) {
            $data[$i][$j] = mt_rand(0, 255);
        }
    }

    return $data;
}


$SOM = new SOM(2000, 25, 25);

$SOM->epoch(getRandomData(5));
$SOM->render();
