<?php

class ControllerExtensionModuleOmsi extends Controller {

    private $logger;

    public function index() {
        $this->registry->set('log', new Log($this->config->get('config_error_filename') ? $this->config->get('config_error_filename') : $this->config->get('error_filename')));
    }

    public function addCustomerToMoySklad($eventRoute, &$data) {
        echo "Customer data:" . PHP_EOL;
        echo "<pre>" . var_dump($data) . "</pre>";
        $this->load->library('omsi');
        $obj_omsi = Omsi::get_instance($this->registry);
        $obj_omsi->сreateCustomer($data[0]);
    }

    public function addOrderToMoySklad($eventRoute, &$data) {
        echo "Order data:" . PHP_EOL;
        echo "<pre>" . var_dump($data) . "</pre>";
        $this->logger->write("as");
    }
}