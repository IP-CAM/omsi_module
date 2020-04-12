<?php
require_once dirname(__FILE__) . '/../util/MsConstants.php';
require_once dirname(__FILE__) . '/../loader/BaseLoader.php';
require_once dirname(__FILE__) . '/../helper/CustomersDbHelper.php';

class CustomerService extends BaseLoader
{

    private $customerHelper;
    private $log;

    public function __construct($db, $log)
    {
        $this->customerHelper = new CustomersDbHelper($db);
        $this->log = $log;
    }

    public function getCustomersByName($name)
    {
        $url = URL_BASE . URL_GET_CUSTOMER . "?" . URL_PARAM_SEARCH . urlencode($name);
        $this->log->write($name . " - " . $url);
        $resultArray = parent::load($url);
        //$this->log->write(var_export($resultArray, true));
        return $resultArray['rows'];
    }

    public function synchronizeCustomers()
    {
        $customers = $this->customerHelper->getAllCustomers();
        $this->log->write("Need to synchronize " . $customers->num_rows . " customers");
        if ($customers->num_rows > 0) {
            foreach ($customers->rows as $customer) {
                $trimmedName = trim($customer['lastname']);
                if (strlen($trimmedName) > 1) {
                    $foundCustomers = $this->getCustomersByName($trimmedName);
                    if (count($foundCustomers) === 0) {
                        $this->log->write("Customer " . $customer['lastname'] . " wasn't found in MoySklad. Creating...");
                        $this->createCustomer($customer['firstname'], $customer['lastname'], $customer['email'], $customer['telephone']);
                    } else {
                        $this->log->write("Customer " . $customer['lastname'] . " was found in MoySklad. Linking...");
                        $this->linkCustomer($foundCustomers[0], $customer['customer_id']);
                    }
                }
            }
        }
    }

    public
    function createCustomer($name, $lastname, $email, $phoneNumber)
    {
        $data = array("name" => $name . " " . $lastname, "description" => "Синхронизирован автоматически. Создан на сайте.", "email" => $email, "phone" => $phoneNumber);
        $url = URL_BASE . URL_GET_CUSTOMER;
        $customer = parent::post($url, $data);
        if ($customer != false) {
            $this->linkCustomer($customer, $this->customerHelper->getCustomerIdByEmail($email));
        }
        return $customer;
    }

    public
    function linkCustomer($customer, $customerId)
    {
        //$this->log->write($customerId . " " . $customer['id'] . " " . $customer['version']);
        $this->customerHelper->createCustomerAssociation($customerId, $customer['id'], $customer['version']);
    }
}