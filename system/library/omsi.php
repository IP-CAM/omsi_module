<?php
if (!defined('DB_HOSTNAME')) {
    require_once dirname(__FILE__) . '/../../config.php';
}
require_once dirname(__FILE__) . '/log.php';
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/db/mpdo.php';

require_once dirname(__FILE__) . '/omsi/util/globalConstants.php';
require_once dirname(__FILE__) . '/omsi/helper/ProductsDbHelper.php';
require_once dirname(__FILE__) . '/omsi/loader/ProductsLoader.php';
require_once dirname(__FILE__) . '/omsi/services/CustomerService.php';
require_once dirname(__FILE__) . '/omsi/services/OrderService.php';
require_once dirname(__FILE__) . '/omsi/services/ProductsService.php';

/**
 * Class Omsi
 */
class Omsi {
    private static $instance;
    private $db;
    private $log;

    /**
     * @param object $registry Registry Object
     */
    public static function get_instance($registry = null) {
        if (is_null(static::$instance)) {
            static::$instance = new static($registry);
        }

        return static::$instance;
    }

    public function __construct($registry) {
        if (!is_null($registry)) {
            $this->log = $registry->get('log');
        } else {
            // If registry is null, then this script runs from outside of OC engine. It is from CRON...
            $this->log = new Log("omsi_cron.log");
        }
        $this->db = new \DB\mPDO(DB_HOSTNAME, DB_USERNAME, html_entity_decode(DB_PASSWORD, ENT_QUOTES, 'UTF-8'), DB_DATABASE, DB_PORT);
    }

    public function testReadProductName($model) {
        try {
            $this->log->write("Great. You are connected!!!");
            if (is_resource($this->db)) {
                $this->log->write("Great. You are connected!!!");
            }
            $result = $this->db->query("select * from oc_product limit 1");
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
        $service = new CustomerService($this->db);

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
        $productsService = new ProductsService($this->db, $this->log);
        $productsService->deleteAllProducts();
    }

    public function synchronizeCategories() {
        $productsService = new ProductsService($this->db, $this->log);
        $productsService->syncCatesynchronizeProductsgories();
    }

    public function synchronizeProducts($count = null) {
        $productsService = new ProductsService($this->db, $this->log);
        $productsService->syncProducts($count);
    }

    public function updateProducts() {
        $productsService = new ProductsService($this->db, $this->log);
        $productsService->updateProducts();
    }

    public function synchronizeCustomers() {
        $customerService = new CustomerService($this->db);
        $customerService->synchronizeCustomers();
    }

    public function createCustomerOrder($orderData) {
        $service = new OrderService($this->db);
        $result = $service->createOrder($orderData[0], $orderData[1]);

        return $orderData;
    }

    public function updateProductsCategories() {
        $service = new ProductsService($this->db, $this->log);
        $service->rebuildCategoriesRelations();
    }

    public function updateProduct($productId) {
        $service = new ProductsService($this->db, $this->log);
        $service->updateProduct($productId);
    }
}
