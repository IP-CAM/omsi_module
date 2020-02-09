<?php
require_once dirname(__FILE__) . "/../../omsi.php";

$omsi = Omsi::get_instance();
$omsi->synchronizeCategories();
?>
