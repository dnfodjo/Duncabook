<?php

class ControllerModuleMultiMerchSocialLinks extends ControllerMultisellerBase {
	private $version = "2.0.3";
	private $name = 'multimerch_social_links';
	
	private $settings = Array(
		'msconf_sl_status' => 0,
		'msconf_sl_icon_width' => '75',
		'msconf_sl_icon_height' => '75'
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
		$set = $this->model_setting_setting->getSetting("msconf_sl");

		/* Set defaults */
		foreach ($this->settings as $name=>$value) {
			if (!array_key_exists($name,$set)) {
				$set[$name] = $value;
			}
		}

		/* Process settings */
		foreach($set as $s=>$v) {
			if (isset($this->request->post[$s])) {
				$set[$s] = $this->request->post[$s];
				$this->data[$s] = $this->request->post[$s];
			} elseif ($this->config->get($s)) {
				$this->data[$s] = $this->config->get($s);
			}
		}

		$this->model_setting_setting->editSetting("msconf_sl", $set);
	}
	
	public function install() {
		$this->load->model("module/{$this->name}");
		$this->model_module_multimerch_social_links->createSchema();
		$this->model_module_multimerch_social_links->createData();

		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting("msconf_sl", $this->settings);

		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'multiseller/social_link');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'multiseller/social_link');
	}

	public function uninstall() {
		$this->load->model("module/{$this->name}");
		$this->model_module_multimerch_social_links->deleteSchema();
	}

	public function saveSettings() {
		$json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->editSettings();
			$this->session->data['success'] = $json['success'] = $this->language->get('text_success');
		} else {
			$json['errors'][] = $this->language->get('error_permission');
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function index() {
		/* Initialize */
		$this->load->model('setting/setting');
		$this->document->addScript('view/javascript/multimerch/settings.js');
		
		foreach($this->settings as $s=>$v) {
			$this->data[$s] = $this->config->get($s);
		}

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
		
		
		$this->document->setTitle($this->language->get('heading_subtitle'));
		$this->data['action'] = $this->url->link("module/{$this->name}", 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');		
		
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view("module/{$this->name}.tpl", $this->data));
	}
}
?>
