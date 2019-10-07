<?php
include_once('processing/dbHelperAbstract.php');

class CategoriesDbHelper extends DbHelperAbstract {

    public function getAllCategoriesWithNames() {
        $result = $this->getDb()->query(SqlConstants::GET_ALL_CATEGORIES_IDS_WITH_NAMES);
        if ($result) {
            return $result->rows;
        }
    }

    public function insertCategory(Category $category) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY, $category->getParentId());
        if ($result) {
            $resultId = parent::getLastInsertedId();
            echo "Successfully inserted category with id " . $resultId . "<br>";
            return $resultId;
        }
    }

    public function insertCategoryDescription(Category $category) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY_DESCRIPTION, $category->getCategoryId(),
            $category->getName(), $category->getName());
        if ($result) {
            echo "Successfully inserted category description <br>";
        }
    }

    public function insertCategoryIntoStore($category_id) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY_TO_STORE, $category_id);
        if ($result) {
            echo "Successfully inserted category into store <br>";
        }
    }

    public function insertCategoryIntoTechnicalTable(Category $category) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_MS_SAMOPEK_CATEGORY, $category->getCategoryId(),
            $category->getUuid(), $category->getVersion());
        if ($result) {
            echo "Successfully inserted category into oc_ms_samopek_category <br>";
        }
    }

    public function insertCategoryPath($categoryId, $pathId, $level) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_CATEGORY_PATH, $categoryId, $pathId, $level);
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
                $this->getDb()->query(SqlConstants::UPDATE_CATEGORY_PARENT, $row['parent_category_id'],
                    $row['category_id']);
            }
        }
    }

    public function getCategoryIdByUuid($uuid) {
        return $this->getDb()->query(SqlConstants::GET_CATEGORY_ID_BY_UUID, $uuid);
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