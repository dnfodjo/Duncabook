<?php

class ControllerModuleMultiMerchShipping extends ControllerMultisellerBase {
	private $version = "2.0.6";
	private $name = 'multimerch_shipping';

	private $settings = Array(
		"msship_product_shipping_cost_estimation" => 1,
		"msship_enable_minicart_shipping_estimate" => 1,
		"msship_download_limit_applies" => 1,
		"msship_physical_product_categories" => array(),
		"msship_digital_product_categories" => array()
	);

	private $error = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->registry = $registry;
		$this->data = array_merge($this->data, $this->load->language('multiseller/multiseller'), $this->load->language("module/{$this->name}"));
		$this->data['token'] = $this->session->data['token'];
	}

	private function editSettings() {
		$this->load->model('setting/setting');
		$set = $this->model_setting_setting->getSetting("msship");

		/* Set defaults */
		foreach ($this->settings as $name=>$value) {
			if (!array_key_exists($name,$set)) {
				$set[$name] = $value;
			}
		}

		/* Process settings */
		foreach($set as $s=>$v) {
			if ($s == "{$this->name}_module") {
				unset($this->request->post[$s][0]);
			}

			if (isset($this->request->post[$s])) {
				$set[$s] = $this->request->post[$s];
				$this->data[$s] = $this->request->post[$s];
			} elseif ($this->config->get($s)) {
				$this->data[$s] = $this->config->get($s);
			}
		}

		$this->model_setting_setting->editSetting("msship", $set);
	}

	public function install() {
		$this->load->model("module/{$this->name}");
		$this->load->model('setting/setting');
		$this->model_module_multimerch_shipping->createTable();
		$this->model_module_multimerch_shipping->addData();
		$this->model_setting_setting->editSetting("msship", $this->settings);
	}

	public function uninstall() {
		$this->load->model("module/{$this->name}");
		$this->model_module_multimerch_shipping->dropTable();
	}

	public function saveSettings() {
		$json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			if (!isset($this->request->post['msship_physical_product_categories']))
				$this->request->post['msship_physical_product_categories'] = array();

			if (!isset($this->request->post['msship_digital_product_categories']))
				$this->request->post['msship_digital_product_categories'] = array();

			$this->editSettings();
			$this->session->data['success'] = $this->language->get('text_success');
		} else {
			$json['errors'][] = $this->language->get('error_permission');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function index() {
		/* Initialize */
		$this->load->model('setting/setting');
		$this->document->addScript('view/javascript/multimerch/settings.js');

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		foreach($this->settings as $s=>$v) {
			$this->data[$s] = $this->config->get($s);
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				$this->session->data['success'] = '';
		} else {
				$this->data['success'] = '';
		}

		/* Main logic */


		/* Render page */
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->admSetBreadcrumbs(array(
			array(
				'text' => $this->language->get('text_module'),
				'href' => $this->url->link('extension/module', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link("module/{$this->name}", '', 'SSL'),
				)
				));


		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['action'] = $this->url->link("module/{$this->name}", 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['categories'] = $this->MsLoader->MsProduct->getCategories();

		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view("module/{$this->name}.tpl", $this->data));
	}
}
?>
