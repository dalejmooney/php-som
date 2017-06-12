# php-som

A simple SOM (Self-organizing map) implementation using PHP.  It can be used as follows.

```
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

```

Where ```getRandomData()``` will simply return random colour data to train SOM with. Render will then
render a table with colours. This is dependant on weights being between 0 -> 255.

**Example**

An example of SOM that would be produced would be.

![SOM example](https://github.com/dalejmooney/php-som/blob/master/web/SOM.png)