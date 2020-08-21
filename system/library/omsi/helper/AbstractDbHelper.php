<?php
require_once dirname(__FILE__) . '/../util/SqlConstants.php';

class AbstractDbHelper {
    private $db;
    private $logger;

    public function __construct($db, $logger = null) {
        $this->db = $db;
        $this->logger = $logger;
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

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}