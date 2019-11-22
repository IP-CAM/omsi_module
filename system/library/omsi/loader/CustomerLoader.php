<?php
require_once dirname(__FILE__) . '/BaseLoader.php';

class CustomerLoader extends BaseLoader {

    public function getCustomerMetadata($customerId) {
        $url = URL_BASE . URL_GET_CUSTOMER . "/" . $customerId;
        $result = parent::load($url);
        return $result['meta'];
    }
}

?>