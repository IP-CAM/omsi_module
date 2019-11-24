<?php
require_once dirname(__FILE__) . '/../loader/OrganizationLoader.php';
require_once dirname(__FILE__) . '/../loader/CustomerLoader.php';
require_once dirname(__FILE__) . '/../helper/CustomersDbHelper.php';
require_once dirname(__FILE__) . '/../helper/ProductsDbHelper.php';
require_once dirname(__FILE__) . '/../util/MsConstants.php';
require_once dirname(__FILE__) . '/../loader/BaseLoader.php';

// ToDo: Not logically correct extend
class OrderService extends BaseLoader {

    //private $orderHelper;
    private $customerHelper;
    private $productsHelper;
    private $organizationLoader;
    private $customerLoader;

    public function __construct($db) {
        //$this->orderHelper = new OrdersDbHelper($db);
        $this->customerHelper = new CustomersDbHelper($db);
        $this->productsHelper = new ProductsDbHelper($db);
        $this->organizationLoader = new OrganizationLoader();
        $this->customerLoader = new CustomerLoader();
    }

    public function createOrder($orderId, $orderStatus) {
        $organizationMetadata = array("meta" => $this->organizationLoader->getOrganizationMetadata());
        $customerUuid = $this->customerHelper->getCustomerUuidByOrderId($orderId);
        $customerMetadata = array("meta" => $this->customerLoader->getCustomerMetadata($customerUuid));
        $positions = $this->getPositionsByOrderId($orderId);
        $data = array(
            "organization" => $organizationMetadata,
            "agent" => $customerMetadata,
            "positions" => $positions
        );
        echo "RES " . var_export($positions);
        $url = URL_BASE . URL_GET_CUSTOMER_ORDER;
        $resultArray = parent::post($url, $data);
        if ($resultArray != false) {
            echo "RESULT " . var_export($resultArray);
        }
        return $resultArray;
    }

    private function getPositionsByOrderId($orderId) {
        $productsFromOrder = $this->productsHelper->getProductsByOrderId($orderId);
        $products = array();
        foreach ($productsFromOrder as $productFromOrder) {
            $product = array();
            $product['quantity'] = intval($productFromOrder['quantity']);
            $product['price'] = floatval($productFromOrder['price']) * 100;
            $product['assortment'] = $this->creteAssortment($productFromOrder['product_id']);
            $products[] = $product;
        }
        return $products;
    }

    private function creteAssortment($productId) {
        $productUuid = $this->productsHelper->getProductUuidByProductId($productId);
        $assortment = array();
        $meta = array();
        $meta['href'] = URL_BASE . URL_GET_PRODUCT . '/' . $productUuid;
        $meta['type'] = 'product';
        $meta['mediaType'] = 'application/json';
        $assortment['meta'] = $meta;
        return $assortment;
    }
}

?>