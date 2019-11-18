<?php
include_once('processing/AbstractDbHelper.php');

class OptionsDbHelper extends AbstractDbHelper {

    public function __construct($db) {
        parent::__construct($db);
    }

    public function insertOption($optionName, $optionUuid) {
        $this->getDb()->query(SqlConstants::INSERT_INTO_OPTION);
        $optionId = parent::getLastInsertedId();
        $this->logger->debug("Inserted option with ID = " . $optionId);
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_OPTION_DESCRIPTION, $optionId, $optionName);
        if ($result) {
            $this->logger->debug("Inserted option description: ID = " . $optionId . ", NAME = " . $optionName);
        }
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_MS_SAMOPEK_OPTION, $optionId, $optionUuid);
    }

    public function insertOptionValue($optionId, $optionValue, $optionUuid) {
        $this->getDb()->query(SqlConstants::INSERT_INTO_OPTION_VALUE, $optionId);
        $optionValueId = parent::getLastInsertedId();
        $this->logger->debug("Inserted optionValue with ID = " . $optionValueId);
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_OPTION_VALUE_DESCRIPTION, $optionValueId, $optionId, $optionValue);
        if ($result) {
            $this->logger->debug("Inserted optionValue description: ID = " . $optionValueId . ", NAME = " . $optionValue);
        }
    }

    public function insertProductOption($productId, $optionId) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_PRODUCT_OPTION, $productId, $optionId);
        if ($result) {
            $this->logger->debug("Inserted product-option: productId: " . $productId . ", optionId: " . $optionId);
        } else {
            $this->logger->error("NOT INSERTED product-option: productId: " . $productId . ", optionId: " . $optionId);
        }
    }

    public function insertProductOptionValue($productOptionId, $productId, $optionId, $optionValueId, $quantity, $price) {
        $result = $this->getDb()->query(SqlConstants::INSERT_INTO_PRODUCT_OPTION_VALUE,
            $productOptionId, $productId, $optionId, $optionValueId, $quantity, $price != null ? $price : 0);
        if ($result) {
            $this->logger->debug("Inserted product-option-value: productId: " . $productOptionId);
        } else {
            $this->logger->error("NOT INSERTED product-option-value: productId: " . $productOptionId);
        }
    }

    public function getOptionIdByUuid($optionUuid) {
        $result = $this->getDb()->query(SqlConstants::GET_OPTION_ID_BY_UUID, $optionUuid);
        return $result->row["option_id"];
    }

    public function getOptionValueIdByName($optionValueName) {
        $result = $this->getDb()->query(SqlConstants::GET_OPTION_VALUE_ID_BY_NAME, $optionValueName);
        $this->logger->debug($result);
        if ($result->num_rows === 0) {
            return false;
        } else {
            return $result->row['option_value_id'];
        }
    }

    public function getProductOption($productId, $optionId) {
        if ($productId) {
            $result = $this->getDb()->query(SqlConstants::GET_PRODUCT_OPTION, $productId, $optionId);
            if ($result->num_rows === 0) {
                return false;
            } else {
                return $result->row['product_option_id'];
            }
        }
    }

    public function clearAllOptions() {
        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_MS_SAMOPEK_OPTION);

        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_OPTION);

        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_OPTION_DESCRIPTION);

        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_OPTION_VALUE);

        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_OPTION_VALUE_DESCRIPTION);

        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_PRODUCT_OPTION);

        $this->getDb()->query(SqlConstants::DELETE_ALL_FROM_PRODUCT_OPTION_VALUE);
    }
}
?>