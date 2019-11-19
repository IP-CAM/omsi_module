<?php
include_once dirname(__FILE__) . '/BaseLoader.php';
require_once dirname(__FILE__) . '/../helper/ProductsDbHelper.php';

class ProductsLoader extends BaseLoader {

    // ToDo: Remove from here!!!
    private $productsHelper;

    public function __construct($db) {
        $this->productsHelper = new ProductsDbHelper($db);
    }

    public function loadProduct($productModel) {
        $assortment = $this->loadAssortment();

        $url = URL_BASE . URL_GET_PRODUCT . "?" . URL_PARAM_FILTER . URL_PARAM_CODE . $productModel;
    }

    public function loadProducts($count) {
        $assortment = $this->loadAssortment();
        $this->loadProductsAttributesMetadata();
        $products = $this->loadAllProducts($assortment, $count);
        return $products;
    }

    public function loadProductsAttributesMetadata() {
        $groupId = $this->productsHelper->insertAttributeGroup();
        if ($groupId) {
            $url = URL_BASE . URL_GET_PRODUCT_METADATA;
            $resultArray = parent::load($url);
            foreach ($resultArray['attributes'] as $attribute) {
                $this->productsHelper->insertAttribute($attribute[UUID], $attribute[NAME], $groupId);
            }
        }
    }

    public function loadAssortment($productModel, $urlTail = "") {
        //$logger = new Log('omsi.log');
        $url = URL_BASE . URL_GET_ASSORTMENT . "?" . URL_PARAM_FILTER . URL_PARAM_CODE . $productModel;

        //$logger->write("Loading - " . $url);
        $resultArray = parent::load($url);
        //$logger->write("resultArray - " . $resultArray);
    }

    public function loadAllProducts($assortment, $requestedCount = 100) {
        $products = [];
        $offset = 0;
        $i = 0;
        $count = 0;
        do {
            $url = URL_BASE . URL_GET_PRODUCT . "?" . URL_PARAM_OFFSET . $offset . "&" . URL_PARAM_LIMIT;
            $resultArray = parent::load($url);
            foreach ($resultArray['rows'] as $row) {
                $products[] = $this->fillData($row, $assortment);
                $count++;
            }
            $offset += 100;
            $i++;
        } while ($count < $requestedCount);
        return $products;
    }

    private function fillData($row, $assortment) {
        $product = new Product();
        if (!array_key_exists(NAME, $row)) {
            $this->logger->error("Name is empty! Stop!");
            die();
        } else {
            $rowName = str_replace("'", "", $row[NAME]);
            $product->setName($rowName);
            // ToDo: Temporary set meta name to Name!!!
            $product->setMetaTitle($rowName);
        }

        $product->setDescription(array_key_exists(P_DESC, $row) ? $row[P_DESC] : "");

        if (!array_key_exists(P_CODE, $row)) {
            var_dump($row);
            $this->logger->error("Code is empty! Stop!");
            die();
        } else {
            $product->setModel($row[P_CODE]);
        }

        if (!array_key_exists(UUID, $row)) {
            $this->logger->error("UUID is empty! Stop!");
            die();
        } else {
            $product->setUuid($row[UUID]);
        }

        if (!array_key_exists(UPDATED, $row)) {
            $this->logger->error("Updated is empty! Stop!");
            die();
        } else {
            $product->setDateAdded($row[UPDATED]);
        }



        $product->setPrice($row[P_SALE_PRICES][0][VALUE] / 100);
        $product->setWeight($row[P_WEIGHT]);
        $product->setVersion($row[MS_VERSION]);

        $category = ParseUtils::getParentCategoryUuid($row);
        if ($category != null) {
            $product->setCategoryId($category);
        } else {
            $this->logger->warn("No parent category exists for " . $row[P_CODE]);
        }

        if (array_key_exists($product->getModel(), $assortment)) {
            $product->setQuantity($assortment[$product->getModel()]);
        } else {
            $this->logger->warn("No assortment info for Product with ms_id " . $product->getModel());
        }

        $this->grabImage($product, $row);

        if (!array_key_exists(ATTRIBUTES, $row)) {
            $this->logger->debug("No attributes for product with code = " . $row[P_CODE]);
        } else {
            $attributes = array();

            foreach ($row['attributes'] as $attribute) {
                $attr = new Attribute();
                $attr->setAttributeId($attribute[UUID]);
                $attr->setValue($attribute[VALUE]);
                $attributes[] = $attr;
            }
            $product->setAttributes($attributes);
        }

        $this->logger->info("Model = " . $row[P_CODE] . " Version = " . $row[MS_VERSION]);

        return $product;
    }

    private function grabImage(Product &$product, $row) {
        if (array_key_exists(P_IMAGE, $row)) {
            $this->loadImage($row[P_IMAGE][META][HREF], "/var/www/html/image/catalog/" . $row[P_IMAGE][P_IMAGE_FILENAME]);
            $product->setImage("catalog/" . $row[P_IMAGE][P_IMAGE_FILENAME]);
        } else {
            $this->logger->warn("No image for Product with ms_id " . $product->getModel());
        }
    }
}