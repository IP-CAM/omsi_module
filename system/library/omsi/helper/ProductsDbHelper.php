<?php
include_once 'model/attribute.php';
include_once('processing/dbHelperAbstract.php');

class ProductsDbHelper extends DbHelperAbstract {

    public function getAllProductsWithNames() {
        $result = $this->getDb()->query(SqlConstants::GET_ALL_PRODUCTS_IDS_WITH_NAMES);
        if ($result) {
            return $result->rows;
        }
    }

    public function insertProduct(Product $product) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_PRODUCT, $product->getModel(),
            $product->getQuantity() != null ? $product->getQuantity() : 0,
            $product->getImage(), $product->getPrice(), $product->getWeight(), $product->getDateAdded());
        if ($result) {
            $resultId = parent::getLastInsertedId();
            echo "Successfully inserted product with id " . $resultId . "<br>";
            return $resultId;
        }
    }

    public function insertProductDescription(Product $product) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_PRODUCT_DESCRIPTION, $product->getProductId(),
            $product->getName(), $product->getDescription(), $product->getMetaTitle());
        if ($result) {
            echo "Successfully inserted product description <br>";
        }
    }

    public function insertProductIntoStore($product_id) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_PRODUCT_TO_STORE, $product_id);
        if ($result) {
            echo "Successfully inserted product into store <br>";
        }
    }

    /**
     * @param Product $product
     */
    public function insertProductIntoCategory(Product $product) {
        $productCategory = $this->getCategoryIdByUuid($product->getCategoryId());
        if (!$productCategory) {
            return;
        }
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_PRODUCT_TO_CATEGORY, $product->getProductId(),
            $productCategory);
        if ($result) {
            echo "Successfully inserted product into category <br>";
        }
    }

    public function getCategoryIdByUuid($uuid) {
        $result = $this->getDb()->query(SqlConstants::GET_CATEGORY_ID_BY_UUID, $uuid);
        if ($result) {
            return $result->row['category_id'];
        }
    }

    public function insertProductIntoTechnicalTable(Product $product) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_MS_SAMOPEK_PRODUCT, $product->getProductId(),
                        $product->getModel(), $product->getUuid(), $product->getVersion());
        if ($result) {
            echo "Successfully inserted product into oc_ms_samopek_products <br>";
        }
    }

    public function insertAttributeGroup()
    {
        $result = $this->getDb()->query(SqlConstants::GET_ATTRIBUTE_GROUP_ID_BY_NAME, "Характеристики");
        if ($result->num_rows === 0) {
            $result = $this->getDb()->query(SqlConstants::INSERT_INTO_ATTRIBUTE_GROUP);
            if ($result) {
                $groupId = parent::getLastInsertedId();
                $result = $this->getDb()->query(SqlConstants::INSERT_INTO_ATTRIBUTE_GROUP_DESCRIPTION, $groupId, "Характеристики");
                if ($result) {
                    $this->logger->debug("Inserted Attribute group id = " . $groupId . " name = Характеристики");
                    return $groupId;
                }
            }
        } else {
            $this->logger->debug("Attribute group Haracteristiki exists.");
        }
        return null;
    }

    public function insertAttribute($uuid, $name, $groupId)
    {
        $id = $this->getAttributeIdByUuid($uuid);
        if ($id != 0) {
            $result = $this->getDb()->query(SqlConstants::UPDATE_ATTRIBUTE_DESCRIPTION, $name, $id);
        } else {
            $result = $this->getDb()->query(SqlConstants::INSERT_INTO_ATTRIBUTE, $groupId);
            if ($result) {
                $attributeId = parent::getLastInsertedId();
                $result = $this->getDb()->query(SqlConstants::INSERT_INTO_ATTRIBUTE_DESCRIPTION, $attributeId, $name);
                if ($result) {
                    $result = $this->getDb()->query(SqlConstants::INSERT_INTO_MS_ATTRIBUTES, $attributeId, $uuid);
                    if ($result) {
                        $this->logger->debug("Inserted attribute with id = " . $attributeId . " UUID = " . $uuid);
                    }
                }
            }
        }
    }

    public function insertProductAttributes(Product $product) {
        $attributes = $product->getAttributes();
        // Delete product attributes
        $result = $this->getDb()->query(SqlConstants::DELETE_PRODUCT_ATTRIBUTE, $product->getProductId());
        foreach ($attributes as $attribute) {
            $result = $this->getDb()->query(SqlConstants::INSERT_INTO_PRODUCT_ATTRIBUTE, $product->getProductId(),
                $this->getAttributeIdByUuid($attribute->getAttributeId()), $attribute->getValue());
            if ($result) {
                $this->logger->debug("Inserted product attribute productId = " . $product->getProductId() . " attributeId = " . $attribute->getAttributeId() . " value = " . $attribute->getValue());
            }
        }
    }

    public function getAttributeIdByUuid ($uuid) {
        $result = $this->getDb()->query(SqlConstants::GET_ATTRIBUTE_ID_BY_UUID, $uuid);
        if ($result->num_rows === 0) {
            $this->logger->debug("Attribute with UUID = " . $uuid . " NOT exists!");
            return 0;
        } else {
            $id = $result->row['attribute_id'];
            $this->logger->debug("Attribute with UUID = " . $uuid . " exists! Samopek ID = " . $id);
            return $id;
        }
    }

    public function getProductVersion($productId) {
        $result = $this->getDb()->query(SqlConstants::GET_VERSION_BY_PRODUCT_ID, $productId);
        if ($result) {
            echo "Successfully got version " . $result->row['ms_version'] . " for product " . $productId . "<br>";
            return $result->row['ms_version'];
        }
    }

    public function getProductByModel($model) {
        $result = $this->getDb()->query(SqlConstants::GET_PRODUCT_BY_MODEL, $model);
        if ($result->num_rows === 0) {
            echo "Product with model = " . $model . " not found!";
        } else {
            echo "ProductId = " . $result->row['product_id'] . " ProductModel = " . $result->row['model'] . "<br>";
        }
    }

    public function isProductExists($model) {
        $result = $this->getDb()->query(SqlConstants::GET_PRODUCT_BY_MODEL, $model);
        var_dump($result);
        if ($result->num_rows === 0) {
            echo "Product with Model = " . $model . " NOT exists!";
            return false;
        } else {
            echo "Product with Model = " . $model . " exists!";
            return true;
        }
    }

    public function getProductsVersions() {
        $result = $this->getDb()->query(SqlConstants::GET_PRODUCTS_VERSIONS);
        if ($result) {
            $productsVersionsOpencart = array();
            foreach ($result->rows as $row) {
                $productsVersionsOpencart[$row['ms_id']] = $row['ms_version'];
            }
            return $productsVersionsOpencart;
        }
    }

    public function getProductIdIfExists($model) {
        $result = $this->getDb()->query(SqlConstants::GET_PRODUCT_BY_MODEL, $model);
        if ($result->num_rows === 0) {
            return false;
        } else {
            return $result->row['product_id'];
        }
    }

    public function getProductIdByUuid($productUuid) {
        $result = $this->getDb()->query(SqlConstants::GET_PRODUCT_ID_BY_UUID, $productUuid);
        return $result->row["product_id"];
    }

    public function getPriceByProductId($productId) {
        $result = $this->getDb()->query(SqlConstants::GET_PRODUCT_PRICE_BY_PRODUCT_ID, $productId);
        return $result->row["price"];
    }

    public function getCategoryIdIfExists($model) {
        $result = $this->getDb()->query(SqlConstants::GET_PRODUCT_BY_MODEL, $model);
        if ($result->num_rows === 0) {
            return false;
        } else {
            return $result->row['product_id'];
        }
    }

    /**
     * Supports update name, description, price.
     *
     * @param Product $product
     * @return bool
     */
    public function updateProduct(Product $product) {
        $result = $this->getDb()->query(SqlConstants::GET_VERSION_BY_PRODUCT_ID, $product->getProductId());

        if ($result->num_rows === 0) {
            echo "Product with ID = " . $product->getProductId() . " NOT exists!";
            return false;
        } else {
            if ($product->getVersion() > $result->row['ms_version']) {
                echo "Product with ID = " . $product->getProductId() . " was updated in MySklad, updating in Opencart";
                $this->getDb()->query(SqlConstants::UPDATE_PRODUCT, $product->getPrice(), $product->getImage(),
                    $product->getQuantity(), $product->getProductId());
                $this->getDb()->query(SqlConstants::UPDATE_PRODUCT_DESCRIPTION, $product->getName(),
                    $product->getDescription(), $product->getProductId());
                $this->getDb()->query(SqlConstants::UPDATE_MS_SAMOPEK_PRODUCT, $product->getVersion(), $product->getProductId());
                return true;
            } else {
                echo "Product with ID = " . $product->getProductId() . " was NOT updated in MySklad, skipping update";
                return false;
            }
        }
    }

    public function updateProductQuantity(Product $product) {
        $this->getDb()->query(SqlConstants::UPDATE_PRODUCT_QUANTITY, $product->getQuantity(),
            $product->getModel());
    }

    public function updateProductQuantity2($model, $quantity) {
        $this->getDb()->query(SqlConstants::UPDATE_PRODUCT_QUANTITY, $quantity,
            $model);
    }

    public function updateProductVersion(Product $product) {
        $result = $this->getDb()->query(SqlConstants::UPDATE_MS_SAMOPEK_PRODUCT, $product->getVersion(), $product->getProductId());
        if ($result) {
            echo "Successfully updated product version to " . $product->getVersion() . " for product " . $product->getModel() . "<br>";
        }
    }

    public function deleteAllProducts() {
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_PRODUCT);
        if (!$result) {
            echo "Delete from oc_product failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_PRODUCT_DESCRIPTION);
        if (!$result) {
            echo "Delete from oc_product_description failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_PRODUCT_TO_STORE);
        if (!$result) {
            echo "Delete from oc_product_to_store failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_PRODUCT_TO_CATEGORY);
        if (!$result) {
            echo "Delete from oc_product_to_category failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_MS_SAMOPEK_PRODUCT);
        if (!$result) {
            echo "Delete from oc_product_description failed.";
        }
    }
}
?>