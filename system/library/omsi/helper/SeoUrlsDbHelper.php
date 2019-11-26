<?php
require_once dirname(__FILE__) . '/AbstractDbHelper.php';

class SeoUrlsDbHelper extends AbstractDbHelper {
    public function insertSeoUrl($id, $name, $isCategory) {

        $query = $isCategory ? 'category_id=' : 'product_id=';

        $data = array();
        $data[] = $query . $id;

        $result = $this->getDb()->query(SqlConstants::GET_SEO_URL_BY_QUERY, $data);
        if ($result->num_rows === 0) {
            $data = array();
            $data[] = $query . $id;
            $data[] = $name;
            $result = $this->getDb()->query(SqlConstants::INSERT_INTO_SEO_URL, $data);
            if ($result) {
                $resultId = parent::getLastInsertedId();
                echo "Successfully inserted seo URL with id " . $resultId . "<br>";
                return $resultId;
            }
        } else {
            echo "Seo URL for product/category " . $id . "already exists. <br>";
        }
    }

    public function cleanSeoUrls() {
        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_SEO_URL);
    }
}