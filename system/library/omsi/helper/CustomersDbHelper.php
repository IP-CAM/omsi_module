<?php
require_once dirname(__FILE__) . '/AbstractDbHelper.php';

class CustomersDbHelper extends AbstractDbHelper {

    public function createCustomerAssociation($customerId, $customerMsUuid, $customerVersion) {
        $params = array();
        $params[] = $customerId;
        $params[] = $customerMsUuid;
        $params[] = $customerVersion;
        echo "PARAMS:";
        var_dump($params);
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_MS_SAMOPEK_CUSTOMER, $params);
        if ($result) {
            echo "Successfully inserted customer into oc_ms_samopek_customer <br>";
        }
    }

    public function getCustomerIdByEmail($email) {
        $params = array();
        $params[] = $email;
        $result = $this->getDb()->query(SqlConstants::GET_CUSTOMER_ID_BY_EMAIL, $params);
        echo "RESULTTTTTTTT:";
        var_dump($result);
        if ($result->num_rows == 1) {
            return $result->row['customer_id'];
        } else {
            echo "Two customers with the same EMAIL. Dying..";
            die;
        }
    }

    public function getCustomerUuidByOrderId($orderId) {
        $params = array();
        $params[] = $orderId;
        $result = $this->getDb()->query(SqlConstants::GET_CUSTOMER_UUID_BY_ORDER_ID, $params);
        if ($result->num_rows == 1) {
            return $result->row['ms_customer_uuid'];
        } else {
            echo "Two customers for the same ORDER. Dying..";
            die;
        }
    }

    public function getAllCustomers() {
        $result = $this->getDb()->query(SqlConstants::GET_ALL_NOT_SYNCED_CUSTOMERS);
        return $result;
    }
}
?>
