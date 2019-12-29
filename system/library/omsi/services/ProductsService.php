<?php
require_once dirname(__FILE__) . '/../helper/ProductsDbHelper.php';
require_once dirname(__FILE__) . '/../helper/CategoriesDbHelper.php';
require_once dirname(__FILE__) . '/../helper/SeoUrlsDbHelper.php';
require_once dirname(__FILE__) . '/../loader/ProductsLoader.php';
require_once dirname(__FILE__) . '/../loader/CategoriesLoader.php';
require_once dirname(__FILE__) . '/../util/CommonUtils.php';

class ProductsService {

    private $productsDbHelper;
    private $categoriesDbHelper;
    private $seoUrlsDbHelper;

    private $productsLoader;
    private $categoriesLoader;

    private $log;

    public function __construct($registry, $db) {
        $this->log = $registry->get('log');
        $this->productsDbHelper = new ProductsDbHelper($db);
        $this->categoriesDbHelper = new CategoriesDbHelper($db);
        $this->seoUrlsDbHelper = new SeoUrlsDbHelper($db);

        $this->productsLoader = new ProductsLoader($db, $this->log);
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
            $mapCategoriesParents[$row['category_id']] = $row['parent_category_id'];
        }
        print_r($mapCategoriesParents);
        $this->fillCategories($mapCategoriesParents);
    }

    public function syncProducts($count) {
        $this->log->write("Loading " . $count . " products...");
        $products = $this->productsLoader->loadProducts($count);
        $this->log->write("End Loading ");
        $this->log->write(var_export($products, true));
        foreach ($products as $product) {
            $this->updateOrCreateProduct($product);
        }

        $this->rebuildSeoUrls();
    }

    private function updateOrCreateProduct(Product $product) {
        $product_id = $this->productsDbHelper->getProductIdIfExists($product->getModel());
        if ($product_id) {
            $product->setProductId($product_id);
        }

        if ($product_id) {
            //$product_version = $this->dbHelper->getProductVersion($product_id);
            //if ($product->getVersion() > $product_version) {
            $this->log->write("Version for product with ms_id " . $product->getModel() . " was changed. Updating product...");
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

    public function rebuildCategoriesRelations() {
        $productsIds = $this->productsDbHelper->getAllProductsIds();
        foreach ($productsIds as $productIdRow) {
            $productId = $productIdRow["product_id"];
            $categoriesIds = $this->categoriesDbHelper->getCategoryIdByProductId($productId);

            foreach ($categoriesIds as $categoryIdRow) {
                $categoryId = $categoryIdRow["category_id"];
                $this->insertIntoNextParentCategories($productId, $categoryId);
            }
        }
    }

    public function updateProduct($productId) {
        $product = $this->productsLoader->loadProduct($productId);
        $this->updateOrCreateProduct($product);
    }

    private function insertIntoNextParentCategories($productId, $categoryId) {
        $categoriesIds = $this->categoriesDbHelper->getAllCategoriesExcludingCurrent($categoryId);
        foreach ($categoriesIds as $categoryIdRow) {
            $categoryId = $categoryIdRow["path_id"];
            $this->productsDbHelper->insertProductIntoCategory2($productId, $categoryId);
            $this->insertIntoNextParentCategories($productId, $categoryId);
        }
    }

    private function rebuildSeoUrls() {
        $this->seoUrlsDbHelper->cleanSeoUrls();
        $products = $this->productsDbHelper->getAllProductsWithNames();
        foreach ($products as $product) {
            $this->seoUrlsDbHelper->insertSeoUrl($product['product_id'], CommonUtils::getSeoUrl($product['name']), false);
        }
        $categories = $this->categoriesDbHelper->getAllCategoriesWithNames();
        foreach ($categories as $category) {
            $this->seoUrlsDbHelper->insertSeoUrl($category['category_id'], CommonUtils::getSeoUrl($category['name']), true);
        }
    }

    private function fillCategories($mapCategoriesParents) {
        foreach ($mapCategoriesParents as $categoryId=>$parent) {
            $level = 0;
            $this->getCategoryLevel($mapCategoriesParents, $categoryId, $level);
            $this->insertCategoryPath($mapCategoriesParents, $categoryId, $categoryId, $level);
        }
    }

    private function insertCategoryPath($mapCategoriesParents, $categoryId, $parentCategoryId, $level) {
        $this->categoriesDbHelper->insertCategoryPath($categoryId, $parentCategoryId, $level);
        if ($level != 0) {
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