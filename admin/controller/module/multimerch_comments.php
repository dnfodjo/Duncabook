<?php

class ControllerModuleMultiMerchComments extends ControllerMultisellerBase {
	private $version = '2.0';
	private $name = 'multimerch_comments';
	
	private $settings = Array(
		"mscomm_comments_enable" => 1,
		"mscomm_comments_allow_guests" => 0,
		"mscomm_comments_enforce_customer_data" => 1,
		"mscomm_comments_enable_customer_captcha" => 0,
		"mscomm_comments_maxlen" => 500,
		"mscomm_comments_perpage" => 10,
		
		"mscomm_seller_comments_enable" => 1,
		"mscomm_seller_comments_allow_guests" => 0,
		"mscomm_seller_comments_enforce_customer_data" => 1,
		"mscomm_seller_comments_enable_customer_captcha" => 0,
		"mscomm_seller_comments_maxlen" => 500,
		"mscomm_seller_comments_perpage" => 10,
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
		$set = $this->model_setting_setting->getSetting("mscomm");

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
		
		$this->model_setting_setting->editSetting("mscomm", $set);
	}
	
	public function install() {
		$this->load->model("module/{$this->name}");
		$this->model_module_multimerch_comments->createSchema();
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting("mscomm", $this->settings);
		
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'multiseller/comment');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'multiseller/comment');
	}

	public function uninstall() {
		$this->load->model("module/{$this->name}");
		$this->model_module_multimerch_comments->deleteSchema();
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
