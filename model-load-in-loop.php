<?php

ini_set('memory_limit', '512M');

$getProductIds = function ($number) {
    $productIds = array();
    $collection = Mage::getResourceModel('catalog/product_collection');
    $collection->getSelect()->limit($number);
    foreach ($collection as $product) {
        $productIds[] = $product->getId();
    }
    $collection->clear();
    return $productIds;
};


$exp1 = function ($productIds) {
    $start = microtime(true);
    $r = array();
    $collection = Mage::getResourceModel('catalog/product_collection')
        ->addFieldToFilter('entity_id', array($productIds))
        ->addAttributeToSelect(array('name'));
    foreach ($collection as $product) {
        $r[] = $product->getName();
    }
    $end = microtime(true);
    $collection->clear();
    return $end - $start;
};

$exp2 = function ($productIds) {
    $start = microtime(true);
    $r = array();
    foreach ($productIds as $productId) {
        $product = Mage::getModel('catalog/product')->load($productId);
        $r[] = $product->getName();
    }
    $end = microtime(true);
    return $end - $start;
};

echo '<table><tr><td># of Products</td><td>Collection</td><td>Model Loop</td></tr>';
for ($i = 10; $i <= 1000; $i = $i + 20) {
    if ($i != 10) $n = $i - 10; else $n = $i;
    $start = microtime(true);
    $productIds = $getProductIds($n);
    $end = microtime(true);

    echo '<tr>';
    echo '<td>' . $n . '</td>';
    echo '<td>' . ($exp1($productIds) + ($end - $start)) . '</td>';
    echo '<td>' . $exp2($productIds) . '</td>';
    echo '</tr>';
}
echo '</table>';
die();
