<?php
require_once dirname(__FILE__) . '/BaseLoader.php';
require_once dirname(__FILE__) . '/../helper/ProductsDbHelper.php';
require_once dirname(__FILE__) . '/../model/Product.php';

class ProductsLoader extends BaseLoader {

    // ToDo: Remove from here!!!
    private $productsHelper;

    private $logger;

    public function __construct($db, $logger) {
        $this->productsHelper = new ProductsDbHelper($db);
        $this->logger = $logger;
    }

    public function loadProduct($productModel, $assortment = null) {
        $this->loadProductsAttributesMetadata();
        if ($assortment == null) {
            $assortment = $this->loadStock();
        }
        $url = URL_BASE . URL_GET_PRODUCT . "?" . URL_PARAM_FILTER . URL_PARAM_CODE . $productModel;
        $this->logger->write($url);
        $resultArray = parent::load($url);
        foreach ($resultArray['rows'] as $row) {
            // Assume that there is always only one row in response
            $product = $this->fillData($row, $assortment);
            return $product;
        }
        $this->logger->write("Cannot fetch product with " . $productModel . " from MoySklad.");
        return null;
    }

    // Quantity changed or product version changed
    /**
     * Product is updated if:
     * - quantity changed
     * - product version changed
     * - product not exists in DB at all
     */
    public function loadUpdatedProducts($onlyProductsInDb = false) {
        $productsForQuantityUpdate = array();
        $productsNotExist = array();
        // Load from MS
        $quantitiesMS = $this->loadStock();
        // Read from DB
        $quantitiesDB = $this->productsHelper->getAllProductsWithQuantity();

        foreach ($quantitiesMS as $codeMS=>$quantityMS) {
            if (!array_key_exists($codeMS, $quantitiesDB)) {
                if (!$onlyProductsInDb) {
                    $productsNotExist[$codeMS] = null;
                }
            } else {
                $quantityDB = $quantitiesDB[$codeMS];
                if ($quantityDB != $quantityMS) {
                    $this->logger->write("Product " . $codeMS . " quantity changed. SITE = " . $quantityDB . " MS = " . $quantityMS);
                    $productsForQuantityUpdate[$codeMS] = null;
                }
            }
        }

        $this->logger->write(count($productsForQuantityUpdate) . " products for quantity update:");
        $this->logger->write(implode(", ", array_keys($productsForQuantityUpdate)));

        $productsForVersionUpdate = array();


        // Load from MS
        $versionsMS = $this->loadAllProductsWithVersions();
        // Read from DB
        $versionsDB = $this->productsHelper->getAllProductsWithVersions();
        foreach($versionsMS as $codeMS=>$versionMS) {
            if (!array_key_exists($codeMS, $versionsDB)) {
                if (!$onlyProductsInDb) {
                    $productsNotExist[$codeMS] = null;
                }
            } else{
                $versionDB = $versionsDB[$codeMS];
                if ($versionDB != $versionMS) {
                    $this->logger->write("Product " . $codeMS . " version changed. SITE = " . $versionDB . " MS = " . $versionMS);
                    $productsForVersionUpdate[$codeMS] = null;
                }
            }
        }

        $this->logger->write(count($productsForVersionUpdate) . " products for version update:");
        $this->logger->write(implode(", ", array_keys($productsForVersionUpdate)));

        $this->logger->write(count($productsNotExist) . " products not exists in DB:");
        $this->logger->write(implode(", ", array_keys($productsNotExist)));

        $products = array();
        $this->logger->write("Q");
        $this->logger->write(var_export($productsForQuantityUpdate, true));
        $this->logger->write("V");
        $this->logger->write(var_export($productsForVersionUpdate, true));
        $productsForUpdate = array_merge($productsForQuantityUpdate, $productsForVersionUpdate, $productsNotExist);
        $productsForUpdate = array_slice($productsForUpdate, 0, 2000);
        $productsAmount = count($productsForUpdate);
        $this->logger->write("Merged result: " . $productsAmount . " products will be added or updated.");
        $this->logger->write(var_export($productsForUpdate, true));
        echo "Merged result: " . $productsAmount . " products will be added or updated." . PHP_EOL;

        $k = 0;
        $percentMult = 0;
        foreach ($productsForUpdate as $productKey=>$productValue) {
            $k++;
            $product = $this->loadProduct($productKey, $quantitiesMS);
            if ($product != null) {
                $products[] = $product;
            } else {
                // The fact that we cannot fetch the product tells us that the product most likely was archived.
                // Lets remove from OpenCart as well

            }
            if (((100*$k)/$productsAmount) > $percentMult + 5) {
                $percentMult = $percentMult + 5;
                echo  $percentMult . "% " . "(" . $k . ") Loaded" . PHP_EOL;
            }
        }

        $this->logger->write("Prepared " . count($products) . " products to insert into DB.");

        return $products;
    }

    public function loadAllProductsWithVersions() {
        $products = array();
        $offset = 0;
        $i = 0;
        do {
            $url = URL_BASE . URL_GET_PRODUCT . "?" . URL_PARAM_OFFSET . $offset . "&" . URL_PARAM_LIMIT;
            $resultArray = parent::load($url);
            foreach ($resultArray['rows'] as $row) {
                $products[$row[P_CODE]] = $row[MS_VERSION];
            }
            $offset += 100;
            $i++;
        } while ($i < 30);
        return $products;
    }

    public function loadProducts($count) {
        $assortment = $this->loadStock();
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

    public function loadUpdatedAssortment($date) {
        $urlTail = "&" . URL_PARAM_UPDATED_FROM . $date;
        return $this->loadAssortment($urlTail);
    }

    public function loadStock($urlTail = "") {
        $time_start = microtime(true);
        $stock = [];
        $offset = 0;
        $i = 0;

        do {
            $url = URL_BASE . URL_GET_STOCK . "?" . STORE_ID . STORE_MAIN_UUID . "&" . URL_PARAM_OFFSET . $offset . "&" . URL_PARAM_LIMIT . $urlTail;
            $resultArray = parent::load($url);
            foreach ($resultArray['rows'] as $row) {
                // Here in assortment results we have Services, Variants as well.
                if ($row[META][META_TYPE] === META_TYPE_PRODUCT /*|| $row[META][META_TYPE] === META_TYPE_VARIANT*/) {
                    if (!array_key_exists($row[P_CODE], $stock)) {
                        if (array_key_exists(ASS_QUANTITY, $row)) {
                            $stock[$row[P_CODE]] = $row[ASS_QUANTITY];
                        } else {
                            //     $this->logger->error("No quantity for model " . $row[P_CODE]);
                        }

                        //$this->logger->trace("Model " . $row[P_CODE] . " - Quantity " . $row[ASS_QUANTITY]);
                    }
                }
            }
            $offset += 100;
            $i++;
        } while ($i < 30);

        $time_end = microtime(true);
        return $stock;
    }


    public function loadAssortment($urlTail = "") {
        $time_start = microtime(true);

        $assortment = [];
        $offset = 0;
        $i = 0;
        do {
            $url = URL_BASE . URL_GET_ASSORTMENT . "?" . URL_PARAM_OFFSET . $offset . "&" . URL_PARAM_LIMIT . $urlTail;
        //    $this->logger->info("Loading - " . $url);
            $resultArray = parent::load($url);
            foreach ($resultArray['rows'] as $row) {
                // Here in assortment results we have Services, Variants as well.
                if ($row[META][META_TYPE] === META_TYPE_PRODUCT || $row[META][META_TYPE] === META_TYPE_VARIANT) {
                    if (!array_key_exists($row[P_CODE], $assortment)) {
                        if (array_key_exists(ASS_QUANTITY, $row)) {
                            $assortment[$row[P_CODE]] = $row[ASS_QUANTITY];
                        } else {
                       //     $this->logger->error("No quantity for model " . $row[P_CODE]);
                        }

                    //$this->logger->trace("Model " . $row[P_CODE] . " - Quantity " . $row[ASS_QUANTITY]);
                    }
                }
            }
            $offset += 100;
            $i++;
        } while ($i < 30);

        $time_end = microtime(true);
        return $assortment;
    }

    public function loadAllProducts($assortment, $requestedCount = 3000) {
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
                if ($count >= $requestedCount) {
                    break;
                }
            }
            $offset += 100;
            $i++;
        } while ($count < $requestedCount);
        return $products;
    }

    private function fillData($row, $assortment, $attributesOnly = false) {
        $product = new Product();
        if (!array_key_exists(NAME, $row)) {
       //     $this->logger->error("Name is empty! Stop!");
            die();
        } else {
            $rowName = str_replace("'", "", $row[NAME]);
            $product->setName($rowName);
            // ToDo: Temporary set meta name to Name!!!
            $product->setMetaTitle($rowName);
        }

        $product->setDescription(array_key_exists(P_DESC, $row) ? $row[P_DESC] : "");

        if (!array_key_exists(P_CODE, $row)) {

            $this->logger->error("Code is empty! Stop!");
            die();
        } else {
            $product->setModel($row[P_CODE]);
        }

        if (!array_key_exists(UUID, $row)) {
        //    $this->logger->error("UUID is empty! Stop!");
            die();
        } else {
            $product->setUuid($row[UUID]);
        }

        if (!array_key_exists(ATTRIBUTES, $row)) {
            //      $this->logger->debug("No attributes for product with code = " . $row[P_CODE]);
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

        if (!array_key_exists(UPDATED, $row)) {
       //     $this->logger->error("Updated is empty! Stop!");
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
      //      $this->logger->warn("No parent category exists for " . $row[P_CODE]);
        }

        if (array_key_exists($product->getModel(), $assortment)) {
            $product->setQuantity($assortment[$product->getModel()]);
        } else {
      //      $this->logger->warn("No assortment info for Product with ms_id " . $product->getModel());
        }

        $this->grabImage($product, $row);



      //  $this->logger->info("Model = " . $row[P_CODE] . " Version = " . $row[MS_VERSION]);

        return $product;
    }

    private function grabImage(Product &$product, $row) {
        if (array_key_exists(P_IMAGE, $row)) {
            $this->loadImage($row[P_IMAGE][META][HREF], "/var/www/html/image/catalog/" . $row[P_IMAGE][P_IMAGE_FILENAME]);
            $product->setImage("catalog/" . $row[P_IMAGE][P_IMAGE_FILENAME]);
        } else {
    //        $this->logger->warn("No image for Product with ms_id " . $product->getModel());
        }
    }
}