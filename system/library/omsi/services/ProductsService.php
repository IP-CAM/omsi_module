<?php
require_once dirname(__FILE__) . '/../helper/ProductsDbHelper.php';
require_once dirname(__FILE__) . '/../helper/CategoriesDbHelper.php';
require_once dirname(__FILE__) . '/../helper/SeoUrlsDbHelper.php';
require_once dirname(__FILE__) . '/../loader/ProductsLoader.php';
require_once dirname(__FILE__) . '/../loader/CategoriesLoader.php';

class ProductsService {

    private $productsDbHelper;
    private $categoriesDbHelper;
    private $seoUrlsDbHelper;

    private $productsLoader;
    private $categoriesLoader;

    public function __construct($db) {
        $this->productsDbHelper = new ProductsDbHelper($db);
        $this->categoriesDbHelper = new CategoriesDbHelper($db);
        $this->seoUrlsDbHelper = new SeoUrlsDbHelper($db);

        $this->productsLoader = new ProductsLoader($db);
        $this->categoriesLoader = new CategoriesLoader($db);
    }

    public function deleteAllProducts() {
        $this->productsDbHelper->deleteAllProducts();
        $this->categoriesDbHelper->deleteAllCategories();
    }

    public function syncCategories() {
        $this->categoriesDbHelper->deleteAllCategories();

        $categories = $this->categoriesLoader->loadAllCategories();
        foreach ($categories as $category) {
            if ($category instanceof Category) {
                echo "1";
                $category->setCategoryId($this->categoriesDbHelper->insertCategory($category));
                $this->categoriesDbHelper->insertCategoryDescription($category);
                $this->categoriesDbHelper->insertCategoryIntoStore($category->getCategoryId());
                $this->categoriesDbHelper->insertCategoryIntoTechnicalTable($category);
                $this->categoriesDbHelper->updateCategoriesParents();
            }
        }
        $categoriesParents = $this->categoriesDbHelper->getCategoriesParents();

        $mapCategoriesParents = array();
        foreach ($categoriesParents->rows as $row) {
            //var_dump($row);
            echo "row <br>";
            $mapCategoriesParents[$row['category_id']] = $row['parent_category_id'];
        }
        print_r($mapCategoriesParents);
        $this->fillCategories($mapCategoriesParents);
    }

    public function syncProducts($count) {
        $products = $this->productsLoader->loadProducts($count);
        foreach ($products as $product) {
            $product_id = $this->productsDbHelper->getProductIdIfExists($product->getModel());
            if ($product_id) {
                $product->setProductId($product_id);
            }

            if ($product_id) {
                //$product_version = $this->dbHelper->getProductVersion($product_id);
                //if ($product->getVersion() > $product_version) {
                $this->logger->warn("Version for product with ms_id " . $product->getModel() . " was changed. Updating product...");
                $this->productsDbHelper->updateProduct($product);
                //} else {
                //  $this->logger->info( "Product with ms_id " . $product->getModel() . " doesn't need to be updated.");
                //}
            } else {
                //$this->logger->warn("Product with ms_id " . $product->getModel() . " doesn't exists. Adding...");
                $product->setProductId($this->productsDbHelper->insertProduct($product));
                $this->productsDbHelper->insertProductDescription($product);
                $this->productsDbHelper->insertProductIntoStore($product->getProductId());
                $this->productsDbHelper->insertProductIntoTechnicalTable($product);
                $this->productsDbHelper->insertProductIntoCategory($product);
            }
            // $this->dbHelper->insertProductAttributes($product);

           // $this->dbHelper->closeTransaction();
        }

        $this->rebuildSeoUrls();
    }

    private function rebuildSeoUrls() {
        echo "Start SEO URLs sync." . PHP_EOL;
        $this->seoUrlsDbHelper->cleanSeoUrls();
        $products = $this->productsDbHelper->getAllProductsWithNames();
        foreach ($products as $product) {
            $this->seoUrlsDbHelper->insertSeoUrl($product['product_id'], CommonUtils::getSeoUrl($product['name']), false);
        }
        $categories = $this->categoriesDbHelper->getAllCategoriesWithNames();
        foreach ($categories as $category) {
            $this->seoUrlsDbHelper->insertSeoUrl($category['category_id'], CommonUtils::getSeoUrl($category['name']), true);
        }
        echo "End SEO URLs sync." . PHP_EOL;
    }

    private function fillCategories($mapCategoriesParents) {
        foreach ($mapCategoriesParents as $categoryId=>$parent) {
            $level = 0;
            $this->getCategoryLevel($mapCategoriesParents, $categoryId, $level);
            echo "CALL " . $categoryId . " + " . $level . "<br>";
            $this->insertCategoryPath($mapCategoriesParents, $categoryId, $categoryId, $level);
        }
    }

    private function insertCategoryPath($mapCategoriesParents, $categoryId, $parentCategoryId, $level) {
        echo "START " . $categoryId . " + " . $parentCategoryId . " + " . $level . "<br>";
        $this->categoriesDbHelper->insertCategoryPath($categoryId, $parentCategoryId, $level);
        if ($level != 0) {
            echo $categoryId . " + " . $parentCategoryId . " + " . $level . "<br>";
            $this->insertCategoryPath($mapCategoriesParents, $categoryId, $mapCategoriesParents[$categoryId], --$level);
        }
    }

    private function getCategoryLevel($mapCategoriesParents, $categoryId, &$level) {
        if($mapCategoriesParents[$categoryId] != null) {
            $this->getCategoryLevel($mapCategoriesParents, $mapCategoriesParents[$categoryId], ++$level);
        }
    }
}
?>