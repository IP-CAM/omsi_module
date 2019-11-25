<?php
require_once dirname(__FILE__) . '/../util/MsConstants.php';
require_once dirname(__FILE__) . '/../loader/BaseLoader.php';
require_once dirname(__FILE__) . '/../helper/CustomersDbHelper.php';

class CustomerService extends BaseLoader {

    private $customerHelper;

    public function __construct($db) {
        $this->customerHelper = new CustomersDbHelper($db);
    }

    public function getCustomersByName($name) {
        $url = URL_BASE . URL_GET_CUSTOMER . "?" . URL_PARAM_SEARCH . urlencode($name);
        echo $url;

        $resultArray = parent::load($url);
        return $resultArray['rows'];
    }

    public function synchronizeCustomers() {
        $customers = $this->customerHelper->getAllCustomers();
        if($customers->num_rows > 0) {
            foreach ($customers->rows as $customer) {
                $foundCustomers = $this->getCustomersByName($customer['lastname']);
                if (count($foundCustomers) === 0) {
                    $this->createCustomer($customer['firstname'], $customer['lastname'], $customer['email'], $customer['telephone']);
                } else {
                    echo "Customer " . $customer['name'] . " already exists in MoySklad. Just linking..." . PHP_EOL;
                    $this->linkCustomer($foundCustomers[0], $customer['customer_id']);
                }
            }
        } else {
            echo "Nothing to sync. All customers synced" . PHP_EOL;
        }
    }

    public function createCustomer($name, $lastname, $email, $phoneNumber) {
        $data = array("name" => $name . " " . $lastname, "description" => "Синхронизирован автоматически. Создан на сайте.", "email" => $email, "phone" => $phoneNumber);
        $url = URL_BASE . URL_GET_CUSTOMER;
        $customer = parent::post($url, $data);
        if ($customer != false) {
            $this->linkCustomer($customer, $this->customerHelper->getCustomerIdByEmail($email));
        }
        return $customer;
    }

    public function linkCustomer($customer, $customerId) {
        $this->customerHelper->createCustomerAssociation($customerId, $customer['id'], $customer['version']);
    }
}