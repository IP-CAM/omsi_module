<?php
  require_once 'util/globalConstants.php';
  require_once 'util/mysql.php';
  require_once 'helper/ProductsDbHelper.php';

  class Omsi
  {
      private static $instance;

      /**
       * @param object $registry Registry Object
       */
      public static function get_instance($registry)
      {
          if (is_null(static::$instance)) {
              static::$instance = new static($registry);
          }

          return static::$instance;
      }
  }
