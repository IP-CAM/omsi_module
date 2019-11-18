<?php
require_once dirname(__FILE__) . '/../util/SqlConstants.php';
require_once dirname(__FILE__). '/../util/log4php/Logger.php';

class AbstractDbHelper {
    private $db;
    protected $logger;

    public function __construct($db) {
        $this->db = $db;
        $this->logger = Logger::getLogger(static::class);
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