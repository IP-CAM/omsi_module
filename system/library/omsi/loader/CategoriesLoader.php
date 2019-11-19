<?php
require_once dirname(__FILE__) . '/BaseLoader.php';
require_once dirname(__FILE__) . '/../model/Category.php';
require_once dirname(__FILE__) . '/../util/ParseUtils.php';

class CategoriesLoader extends BaseLoader {

    public function loadAllCategories() {
        $groupArray = array();

        $offset = 0;
        $i = 0;
        do {

            $url = URL_BASE . URL_GET_GROUP . "?" . URL_PARAM_OFFSET . $offset . "&" . URL_PARAM_LIMIT;
            $resultArray = parent::load($url);
            foreach ($resultArray['rows'] as $row) {
                $category = new Category();
                $category->setUuid($row[UUID]);
                $category->setName($row[NAME]);
                $category->setMetaTitle($row[NAME]);
                $category->setParentId($this->getParentId($row));
                $category->setVersion($row[MS_VERSION]);
                $groupArray[] = $category;
            }

            $offset += 100;
            $i++;
        } while ($i < 3);
        return $groupArray;
    }

    private function getParentId($row) {
        if (array_key_exists('productFolder', $row)) {
            $parentCategory = ParseUtils::getParentCategoryUuid($row);
            if ($parentCategory != null) {
                return $parentCategory;
            } else {
                $this->logger->info("No parent category exists for category " . $row[P_CODE]);
            }
        }
        return 0;
    }
}
?>