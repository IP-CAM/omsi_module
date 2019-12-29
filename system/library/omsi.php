<?php
require_once dirname(__FILE__) . '/omsi/util/globalConstants.php';
require_once dirname(__FILE__) . '/omsi/helper/ProductsDbHelper.php';
require_once dirname(__FILE__) . '/omsi/loader/ProductsLoader.php';
require_once dirname(__FILE__) . '/omsi/services/CustomerService.php';
require_once dirname(__FILE__) . '/omsi/services/OrderService.php';
require_once dirname(__FILE__) . '/omsi/services/ProductsService.php';

class Omsi {
    private static $instance;
    private $db;
    private $registry;
    private $log;

    /**
     * @param object $registry Registry Object
     */
    public static function get_instance($registry) {
        if (is_null(static::$instance)) {
            static::$instance = new static($registry);
        }

        return static::$instance;
    }

    public function __construct($registry) {
        $this->registry = $registry;
        $this->log = $registry->get('log');
    }

    public function testReadProductName($model) {
        try {
            $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
            $this->log->write("Great. You are connected!!!");
            if (is_resource($db)) {
                $this->log->write("Great. You are connected!!!");
            }
            $result = $db->query("select * from oc_product limit 1");
        } catch (Exception $e) {
            $this->log->write("Pi4al'ka. :( ");
        }
    }

    public function testGetCustomerByName($name) {

            $service = new CustomerService();
            $resultArray = $service->getCustomersByName($name);
            if (count($resultArray)) {

            } else {
            }
    }

    public function сreateCustomer($сustomerData) {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $service = new CustomerService($db);

        $lastName = $сustomerData['lastname'];

        $foundCustomers = $service->getCustomersByName($lastName);

        if (count($foundCustomers) == 0) {
            $this->log->write("Customer with lastname " . $lastName . " was not found in MoySklad. Creating...");

            $resultArray = $service->createCustomer($сustomerData['firstname'], $сustomerData['lastname'],
                $сustomerData['email'], $сustomerData['telephone']);
            if (count($resultArray)) {

            } else {

            }
        } else if (count($foundCustomers) == 1) {
           $this->log->write("Customer with lastname " . $lastName . " was found in MoySklad. Linking...");
        }
    }

    public function deleteAllProducts() {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $productsService = new ProductsService($this->registry, $db);
        $productsService->deleteAllProducts();
    }

    public function synchronizeCategories() {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $productsService = new ProductsService($this->registry, $db);
        $productsService->syncCategories();
    }

    public function synchronizeProducts($count) {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $productsService = new ProductsService($this->registry, $db);
        $productsService->syncProducts($count);
    }

    public function synchronizeCustomers() {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $customerService = new CustomerService($db);
        $customerService->synchronizeCustomers();
    }

    public function createCustomerOrder($orderData) {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $service = new OrderService($db);
        $result = $service->createOrder($orderData[0], $orderData[1]);

        return $orderData;
    }

    public function updateProducts($model) {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
    }

    public function updateProductsCategories() {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $service = new ProductsService($this->registry, $db);
        $service->rebuildCategoriesRelations();
    }

    public function updateProduct($productId) {
        $db = new \DB\mPDO('localhost', 'root', html_entity_decode('765b91475e', ENT_QUOTES, 'UTF-8'), "opencart_samopek", "3306");
        $service = new ProductsService($this->registry, $db);
        $service->updateProduct($productId);
    }
}
