<?php
require_once dirname(__FILE__) . '/../util/msConstants.php';
require_once dirname(__FILE__) . '/../loader/baseLoader.php';
require_once dirname(__FILE__) . '/../helper/customersDbHelper.php';

class customerService extends BaseLoader {

    private $customerHelper;

    public function __construct($db) {
        $this->customerHelper = new CustomersDbHelper($db);
    }

    public function getCustomersByName($name) {
        $url = URL_BASE . URL_GET_ALL_CUSTOMERS . "?" . URL_PARAM_SEARCH . urlencode($name);
        echo $url;

        $resultArray = parent::load($url);
        return $resultArray['rows'];
    }

    public function createCustomer($name, $surname, $email, $phoneNumber) {
        $data = array("name" => $name . " " . $surname, "description" => "Синхронизирован автоматически. Создан на сайте.", "email" => $email, "phone" => $phoneNumber);
        $url = URL_BASE . URL_GET_ALL_CUSTOMERS;
        $resultArray = parent::post($url, $data);
        if ($resultArray != false) {
            $this->customerHelper->createCustomerAssociation($this->customerHelper->getCustomerIdByEmail($email), $resultArray['id'], $resultArray['version']);
        }
        return $resultArray;
    }
}