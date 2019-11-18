<?php
include_once dirname(__FILE__) . '/BaseLoader.php';

class ProductsLoader extends BaseLoader {

    public function loadProduct($productModel) {
        $assortment = $this->loadAssortment();

        $url = URL_BASE . URL_GET_PRODUCT . "?" . URL_PARAM_FILTER . URL_PARAM_CODE . $productModel;

    }

    public function loadAssortment($productModel, $urlTail = "") {
        $logger = new Log('omsi.log');
        $url = URL_BASE . URL_GET_ASSORTMENT . "?" . URL_PARAM_FILTER . URL_PARAM_CODE . $productModel;

        $logger->write("Loading - " . $url);
        $resultArray = parent::load($url);
        $logger->write("resultArray - " . $resultArray);
    }
}