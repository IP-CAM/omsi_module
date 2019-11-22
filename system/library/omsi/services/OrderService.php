<?php
require_once dirname(__FILE__) . '/../loader/OrganizationLoader.php';
require_once dirname(__FILE__) . '/../loader/CustomerLoader.php';
require_once dirname(__FILE__) . '/../helper/CustomersDbHelper.php';
require_once dirname(__FILE__) . '/../util/MsConstants.php';
require_once dirname(__FILE__) . '/../loader/BaseLoader.php';

// ToDo: Not logically correct extend
class OrderService extends BaseLoader {

    //private $orderHelper;
    private $customerHelper;
    private $organizationLoader;
    private $customerLoader;

    public function __construct($db) {
        //$this->orderHelper = new OrdersDbHelper($db);
        $this->customerHelper = new CustomersDbHelper($db);
        $this->organizationLoader = new OrganizationLoader();
        $this->customerLoader = new CustomerLoader();
    }

    public function createOrder($orderId, $orderStatus) {
        $organizationMetadata = array("meta" => $this->organizationLoader->getOrganizationMetadata());
        $customerUuid = $this->customerHelper->getCustomerUuidByOrderId($orderId);
        $customerMetadata = array("meta" => $this->customerLoader->getCustomerMetadata($customerUuid));
        $data = array(
            "organization" => $organizationMetadata,
            "agent" => $customerMetadata
        );
        $url = URL_BASE . URL_GET_CUSTOMER_ORDER;
        $resultArray = parent::post($url, $data);
        if ($resultArray != false) {

        }
        return $resultArray;
    }
}

?>