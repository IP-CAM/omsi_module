<?php
require_once dirname(__FILE__) . '/../util/msConstants.php';
require_once dirname(__FILE__) . '/../loader/baseLoader.php';

class customerService extends BaseLoader {

    public function getCustomersByName($name) {
        $url = URL_BASE . URL_GET_ALL_CUSTOMERS . "?" . URL_PARAM_SEARCH . urlencode($name);
        echo $url;

        $resultArray = parent::load($url);
        return $resultArray;
    }

    public function createCustomer($name, $surname, $email) {
        $data = array("name" => $name . " " . $surname, "email" => $email);
        $url = URL_BASE . URL_GET_ALL_CUSTOMERS;
        $resultArray = parent::post($url, $data);
        return $resultArray;
    }
}