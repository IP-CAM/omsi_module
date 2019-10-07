<?php
include_once('processing/dbHelperAbstract.php');

class SeoUrlsDbHelper extends DbHelperAbstract {
    public function insertSeoUrl($id, $name, $isCategory) {

        $query = $isCategory ? 'category_id=' : 'product_id=';

        $result = $this->getDb()->query(SqlConstants::GET_SEO_URL_BY_QUERY, $query . $id);
        if ($result->num_rows === 0) {
            $result = $this->getDb()->query(SqlConstants::INSERT_INTO_SEO_URL, $query . $id,
                $name);
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