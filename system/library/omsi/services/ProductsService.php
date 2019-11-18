<?php
require_once dirname(__FILE__) . '/../helper/ProductsDbHelper.php';
require_once dirname(__FILE__) . '/../helper/CategoriesDbHelper.php';

class ProductsService {

    private $productsDbHelper;
    private $categoriesDbHelper;

    public function __construct($db) {
        $this->productsDbHelper = new ProductsDbHelper($db);
        $this->categoriesDbHelper = new CategoriesDbHelper($db);
    }

    public function deleteAllProducts() {
        $this->productsDbHelper->deleteAllProducts();
        $this->categoriesDbHelper->deleteAllCategories();
    }
}
?>