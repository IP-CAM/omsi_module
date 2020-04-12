<?php
require_once dirname(__FILE__) . '/AbstractDbHelper.php';

class CustomersDbHelper extends AbstractDbHelper {

    public function createCustomerAssociation($customerId, $customerMsUuid, $customerVersion) {
        $params = array();
        $params[] = $customerId;
        $params[] = $customerMsUuid;
        $params[] = $customerVersion;

        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_MS_SAMOPEK_CUSTOMER, $params);
        if ($result) {

        } else {

        }
    }

    public function getCustomerIdByEmail($email) {
        $params = array();
        $params[] = $email;
        $result = $this->getDb()->query(SqlConstants::GET_CUSTOMER_ID_BY_EMAIL, $params);

        if ($result->num_rows == 1) {
            return $result->row['customer_id'];
        } else {
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
            return null;
        }
    }

    public function getAllCustomers() {
        $result = $this->getDb()->query(SqlConstants::GET_ALL_NOT_SYNCED_CUSTOMERS);
        return $result;
    }
}
?>
