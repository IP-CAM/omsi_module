<?php
require_once dirname(__FILE__) . '/AbstractDbHelper.php';

class CategoriesDbHelper extends AbstractDbHelper {

    public function getAllCategoriesWithNames() {
        $result = $this->getDb()->query(SqlConstants::GET_ALL_CATEGORIES_IDS_WITH_NAMES);
        if ($result) {
            return $result->rows;
        }
    }

    public function getAllCategoriesExcludingCurrent($categoryId) {
        $data = array();
        $data[] = $categoryId;
        $result = $this->getDb()->query(SqlConstants::GET_CATEGORIES_FROM_PATH_EXCLUDING_CURRENT, $data);
        if ($result) {
            return $result->rows;
        }
    }

    public function insertCategory(Category $category) {
        $data = array();
        $data[] = $category->getParentId();
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY, $data);
        if ($result) {
            $resultId = parent::getLastInsertedId();
            echo "Successfully inserted category with id " . $resultId . "<br>";
            return $resultId;
        }
    }

    public function insertCategoryDescription(Category $category) {
        $data = array();
        $data[] = $category->getCategoryId();
        $data[] = $category->getName();
        $data[] = $category->getName();
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY_DESCRIPTION, $data);
        if ($result) {
            echo "Successfully inserted category description <br>";
        }
    }

    public function insertCategoryIntoStore($category_id) {
        $data = array();
        $data[] = $category_id;
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY_TO_STORE, $data);
        if ($result) {
            echo "Successfully inserted category into store <br>";
        }
    }

    public function insertCategoryIntoTechnicalTable(Category $category) {
        $data = array();
        $data[] = $category->getCategoryId();
        $data[] = $category->getUuid();
        $data[] = $category->getVersion();
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_MS_SAMOPEK_CATEGORY, $data);
        if ($result) {
            echo "Successfully inserted category into oc_ms_samopek_category <br>";
        }
    }

    public function insertCategoryPath($categoryId, $pathId, $level) {
        $data = array();
        $data[] = $categoryId;
        $data[] = $pathId;
        $data[] = $level;
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY_PATH, $data);
        if ($result) {
            echo "Successfully inserted category path";
        }
    }

    public function getCategoriesParents() {
        $result = $this->getDb()->query(SqlConstants::GET_CATEGORY_ID_TO_PARENT_CATEGORY_ID);
        var_dump($result);
        echo "<br><br>";
        if ($result) {
            return $result;
        }
    }

    public function updateCategoriesParents() {
        $result = $this->getDb()->query(SqlConstants::GET_CATEGORY_ID_TO_PARENT_CATEGORY_ID);
        foreach ($result->rows as $row) {
            if ($row['parent_category_id']) {
                $data = array();
                $data[] = $row['parent_category_id'];
                $data[] = $row['category_id'];
                $this->getDb()->query(SqlConstants::UPDATE_CATEGORY_PARENT, $data);
            }
        }
    }

    public function getCategoryIdByUuid($uuid) {
        return $this->getDb()->query(SqlConstants::GET_CATEGORY_ID_BY_UUID, $uuid);
    }

    public function getCategoryIdByProductId($productId) {
        $data = array();
        $data[] = $productId;
        return $this->getDb()->query(SqlConstants::GET_CATEGORY_ID_BY_PRODUCT_ID, $data);
    }

    public function deleteAllCategories() {
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_CATEGORY);
        if (!$result) {
            echo "Delete from oc_category failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_CATEGORY_DESCRIPTION);
        if (!$result) {
            echo "Delete from oc_category_description failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_CATEGORY_TO_STORE);
        if (!$result) {
            echo "Delete from oc_category_to_store failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_MS_SAMOPEK_CATEGORY);
        if (!$result) {
            echo "Delete from oc_ms_samopek_category failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_CATEGORY_PATH);
        if (!$result) {
            echo "Delete from oc_category_path failed.";
        }
        $result = $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_CATEGORY_TO_LAYOUT);
        if (!$result) {
            echo "Delete from oc_category_to_layout failed.";
        }
    }
}
?>