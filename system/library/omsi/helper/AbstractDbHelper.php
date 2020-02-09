<?php
require_once dirname(__FILE__) . '/../util/SqlConstants.php';

class AbstractDbHelper {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDb() {
        return $this->db;
    }

    public function getLastInsertedId() {
        $lastId = $this->db->query(SqlConstants::GET_LAST_INSERT_ID)->row['LAST_INSERT_ID()'];
        return $lastId;
    }

    public function closeTransaction() {
        $this->db->closeTransaction();
    }
}