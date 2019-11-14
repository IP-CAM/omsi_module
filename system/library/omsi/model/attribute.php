<?php
    class Attribute
    {
        private $attribute_id;
        private $value;

        /**
         * @return mixed
         */
        public function getAttributeId()
        {
            return $this->attribute_id;
        }

        /**
         * @param mixed $attribute_id
         */
        public function setAttributeId($attribute_id)
        {
            $this->attribute_id = $attribute_id;
        }

        /**
         * @return mixed
         */
        public function getValue()
        {
            return $this->value;
        }

        /**
         * @param mixed $value
         */
        public function setValue($value)
        {
            $this->value = $value;
        }
    }
?>
