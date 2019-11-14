<?php
  require_once dirname(__FILE__). '/omsi/util/globalConstants.php';
  require_once dirname(__FILE__). '/omsi/util/mysql.php';
  require_once dirname(__FILE__). '/omsi/helper/ProductsDbHelper.php';
  require_once dirname(__FILE__). '/omsi/loader/productsLoader.php';

  class Omsi
  {
      private static $instance;

      /**
       * @param object $registry Registry Object
       */
      public static function get_instance($registry) {
          if (is_null(static::$instance)) {
              static::$instance = new static($registry);
          }

          return static::$instance;
      }

      public function __construct() {

      }

      public function updateProduct($model) {
          $productsLoader = new ProductsLoader();
          $productsLoader->loadProduct('002223');
      }

      public function createCustomerOrder() {

      }

      public function ifCustomerExists($name, $surname) {

      }
  }
