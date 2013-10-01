<?php

ini_set('memory_limit', '1024M');

$getArray = function ($count) {
    $collection = Mage::getResourceModel('catalog/product_collection');
    $collection->getSelect()->limit($count);

    return $collection->getItems();
};

$exp1 = function ($array) {
    $start = microtime(true);
    for ($i = 0; $i < count($array); $i++) {
        $item = $array[$i];
    }
    return microtime(true) - $start;
};

$exp2 = function ($array) {
    $start = microtime(true);
    $count = count($array);
    for ($i = 0; $i < $count; $i++) {
        $item = $array[$i];
    }
    return microtime(true) - $start;
};

echo '<table><tr><td># of Products</td><td>Count in a loop</td><td>Count outside a loop</td></tr>';
for ($i = 10; $i <= 50000; $i = $i + 5000) {
    if ($i != 10) $n = $i - 10; else $n = $i;
    $array = $getArray($n);
    echo '<tr>';
    echo '<td>' . $n . '</td>';
    echo '<td>' . $exp1($array) . '</td>';
    echo '<td>' . $exp2($array) . '</td>';
    echo '</tr>';
    $array = array();
}
echo '</table>';
die();
