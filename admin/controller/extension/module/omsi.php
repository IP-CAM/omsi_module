<?php
class ControllerExtensionModuleOmsi extends Controller {
    private $error = array();

    const DEFAULT_MODULE_SETTINGS = [
        'name' => 'OMSI',
        'model' => '',
        'status' => 1 /* Enabled by default*/
    ];

    public function index() {
        echo "index start" .PHP_EOL;
        echo getcwd() . "\n";
        if (isset($this->request->get['product_model'])) {
            echo "OK" .PHP_EOL;
            $this->log->write("OK");
            $this->testOmsi($this->request->get['product_model']);
        } else {
            echo "Achtung" .PHP_EOL;
            $this->log->write("Achtung");
            if (!isset($this->request->get['module_id'])) {
                $module_id = $this->addModule();
                $this->response->redirect($this->url->link('extension/module/omsi', '&user_token=' . $this->session->data['user_token'] . '&module_id=' . $module_id));
            } else {
                $this->editModule($this->request->get['module_id']);
            }
        }
    }

    private function addModule() {
        $this->load->model('setting/module');

        $this->model_setting_module->addModule('omsi', self::DEFAULT_MODULE_SETTINGS);

        return $this->db->getLastId();
    }

    protected function editModule($module_id) {
        $data = array();
        $data['user_token'] = $this->session->data['user_token'];
        $this->load->language('extension/module/omsi');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/module');
        $module_setting = $this->model_setting_module->getModule($module_id);
        $data['name'] = $module_setting['name'];
        $data['status'] = $module_setting['status'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $htmlOutput = $this->load->view('extension/module/omsi', $data);
        $this->response->setOutput($htmlOutput);
    }

    public function testOmsi($model) {
        $this->load->library('omsi');
        $obj_omsi = Omsi::get_instance($this->registry);
        $obj_omsi->testReadProductName($model);
        $obj_omsi->testGetCustomerByName('Татьяна');
        $obj_omsi->сreateCustomer('Татьяна', 'Ааап', 'test@test.ru');
        echo "updateProduct" .PHP_EOL;
        $json = array();

        $json['success'] = 'success';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCustomerToMoySklad($eventRoute, &$data) {
        echo "Customer data:" . PHP_EOL;
        $this->log()->write("MAKO!!");
        var_dump($data);
    }

    public function validate() {}

    public function install() {
        $this->load->model("setting/event");
        $this->model_setting_event->addEvent("omsi", "catalog/model/account/customer/addCustomer/after", "Extension/Module/Omsi/addCustomerToMoySklad");

        // Create necessary tables
    }

    public function uninstall() {
        $this->load->model("setting/event");
        $this->model_setting_event->deleteEventByCode("omsi");
    }
}