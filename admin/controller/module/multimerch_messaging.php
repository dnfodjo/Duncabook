<?php

class ControllerModuleMultiMerchMessaging extends ControllerMultisellerBase {
	private $name = 'multimerch_messaging';
	private $version = '2.0.1';
	
	private $settings = Array(
		"mmess_conf_enable" => 1
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
		$set = $this->model_setting_setting->getSetting('mmess_conf');

		/* Set defaults */
		foreach ($this->settings as $name=>$value) {
			if (!array_key_exists($name,$set)) {
				$set[$name] = $value;
			}
		}

		/* Process modules */
		if (strcmp(VERSION,'1.5.1') >= 0) {
			if (isset($set["{$this->name}_module"]) && !is_array($set["{$this->name}_module"])) {
				$set["{$this->name}_module"] = unserialize($set["{$this->name}_module"]);
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
		
		$this->model_setting_setting->editSetting("mmess_conf", $set);
	}
	
	public function install() {
		$this->load->model("module/{$this->name}");
		$this->model_module_multimerch_messaging->createSchema();
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting("mmess_conf", $this->settings);
	}

	public function uninstall() {
		$this->load->model("module/{$this->name}");
		$this->model_module_multimerch_messaging->deleteSchema();
	}

	public function saveSettings() {
		$json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
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
		
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view("module/{$this->name}.tpl", $this->data));
	}
}
?>
