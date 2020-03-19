<?php
require_once dirname(__FILE__) . "/../../omsi.php";

$productsCount = 3000;

if (isset($argc)) {
    if (isset($argv[1])) {
        $productsCount = $argv[1];
    }
}

$omsi = Omsi::get_instance();
$omsi->synchronizeProducts($productsCount);
?>
