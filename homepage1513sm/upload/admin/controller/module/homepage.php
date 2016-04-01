<?php
class ControllerModuleHomepage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/homepage');
		$this->load->model('fido/homepage');

		$this->model_fido_homepage->checkHomepages();

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('homepage', $this->request->post, $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'));
		}

		$this->getModule();
	}

	public function insert() {
		$this->load->language('module/homepage');
		$this->load->model('fido/homepage');

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_fido_homepage->addHomepage($this->request->post, $this->user->getId());

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/homepage/listing', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('module/homepage');
		$this->load->model('fido/homepage');

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_fido_homepage->editHomepage($this->request->get['homepage_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/homepage/listing', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('module/homepage');
		$this->load->model('fido/homepage');

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if ((isset($this->request->post['selected'])) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $homepage_id) {
				$this->model_fido_homepage->deleteHomepage($homepage_id, $this->user->getId());
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/homepage/listing', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'));
		}

		$this->getList();
	}

	public function listing() {
		$this->load->language('module/homepage');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	private function getModule() {
		$this->load->language('module/homepage');
		$this->load->model('fido/homepage');

		if (($this->config->get('manager_status') || $this->config->get('manager_restrictions')) && $this->model_fido_homepage->checkUser($this->user->getId())) {
			$manager_status = true;
		} else {
			$manager_status = false;
		}

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		

		$this->data['entry_layout_options'] = $this->language->get('entry_layout_options');
		$this->data['entry_grid_columns'] = $this->language->get('entry_grid_columns');
		$this->data['entry_split_module'] = $this->language->get('entry_split_module');
		$this->data['entry_image_size'] = $this->language->get('entry_image_size');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_homepages'] = $this->language->get('button_homepages');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
     		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
	  		'separator' => FALSE
  		);

		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
     		'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'),
     		'separator' => ' :: '
  		);

		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
     		'href'      => $this->url->link('module/homepage', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'),
     		'separator' => ' :: '
  		);

		$this->data['homepages'] = $this->url->link('module/homepage/listing', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');
		$this->data['action'] = $this->url->link('module/homepage', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');

		$this->data['layout_options'] = array();

		$this->data['layout_options'][] = array(
			'option' => 'tabbed',
			'title'  => $this->language->get('text_tabbed')
		);

		$this->data['layout_options'][] = array(
			'option' => 'list',
			'title'  => $this->language->get('text_list')
		);

		$this->data['layout_options'][] = array(
			'option' => 'grid',
			'title'  => $this->language->get('text_grid')
		);

		if (isset($this->request->post['homepage_layout_option'])) {
			$this->data['homepage_layout_option'] = $this->request->post['homepage_layout_option'];
		} else {
			$this->data['homepage_layout_option'] = $this->model_fido_homepage->getSetting('homepage_layout_option', $store_id);
		}

		if (isset($this->request->post['homepage_grid_cols'])) {
			$this->data['homepage_grid_cols'] = $this->request->post['homepage_grid_cols'];
		} else {
			$this->data['homepage_grid_cols'] = $this->model_fido_homepage->getSetting('homepage_grid_cols', $store_id);
		}

		if (isset($this->request->post['homepage_split_module'])) {
			$this->data['homepage_split_module'] = $this->request->post['homepage_split_module'];
		} else {
			$this->data['homepage_split_module'] = $this->model_fido_homepage->getSetting('homepage_split_module', $store_id);
		}

		$this->data['modules'] = array();

		if (isset($this->request->post['homepage_module'])) {
			$this->data['modules'] = $this->request->post['homepage_module'];
		} elseif ($this->model_fido_homepage->getSetting('homepage_module', $store_id)) { 
			$this->data['modules'] = $this->model_fido_homepage->getSetting('homepage_module', $store_id);
		}				

		$this->load->model('design/layout');

		if ($manager_status) {
			$this->data['layouts'] = $this->model_fido_homepage->getLayoutsByStore($store_id);
		} else {
			$this->data['layouts'] = $this->model_design_layout->getLayouts();
		}

		$this->template = 'module/homepage.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getList() {
		$this->load->language('module/homepage');
		$this->load->model('fido/homepage');

		if (($this->config->get('manager_status') || $this->config->get('manager_restrictions')) && $this->model_fido_homepage->checkUser($this->user->getId())) {
			$manager_status = true;
		} else {
			$manager_status = false;
		}

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_module'] = $this->language->get('button_module');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

  		$this->data['breadcrumbs'] = array();

  		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
     		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
	  		'separator' => FALSE
  		);

  		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
     		'href'      => $this->url->link('module/homepage/listing', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'),
     		'separator' => ' :: '
  		);

		$this->data['module'] = $this->url->link('module/homepage', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');
		$this->data['insert'] = $this->url->link('module/homepage/insert', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');
		$this->data['delete'] = $this->url->link('module/homepage/delete', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');

		$this->data['homepages'] = array();

		if ($manager_status) {
			$homepage_total = $this->model_fido_homepage->getTotalHomepagesByUser($this->user->getId());
			$results = $this->model_fido_homepage->getHomepagesByUser($this->user->getId());
		} else {
			$homepage_total = $this->model_fido_homepage->getTotalHomepages();
			$results = $this->model_fido_homepage->getHomepages();
		}

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/homepage/update', 'token=' . $this->session->data['token'] . '&homepage_id=' . $result['homepage_id'] . '&store_id=' . $store_id, 'SSL')
			);

			$this->data['homepages'][] = array(
				'homepage_id' => $result['homepage_id'],
				'title'       => $result['title'],
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['homepage_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->template = 'module/homepage/list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->load->language('module/homepage');
		$this->load->model('fido/homepage');

		if (($this->config->get('manager_status') || $this->config->get('manager_restrictions')) && $this->model_fido_homepage->checkUser($this->user->getId())) {
			$manager_status = true;
		} else {
			$manager_status = false;
		}

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_fullsize'] = $this->language->get('text_fullsize');
    	$this->data['text_thumbnail'] = $this->language->get('text_thumbnail');
    	$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_image'] = $this->language->get('entry_image');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

  		$this->data['breadcrumbs'] = array();

  		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
     		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
	  		'separator' => FALSE
  		);

  		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
     		'href'      => $this->url->link('module/homepage/listing', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'),
     		'separator' => ' :: '
  		);

		if (!isset($this->request->get['homepage_id'])) {
			$this->data['action'] = $this->url->link('module/homepage/insert',  'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/homepage/update', 'token=' . $this->session->data['token'] . '&homepage_id=' . $this->request->get['homepage_id'] . '&store_id=' . $store_id, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('module/homepage/listing', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL');

		if ((isset($this->request->get['homepage_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$homepage_info = $this->model_fido_homepage->getHomepage($this->request->get['homepage_id']);
		}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['homepage_description'])) {
			$this->data['homepage_description'] = $this->request->post['homepage_description'];
		} elseif (isset($this->request->get['homepage_id'])) {
			$this->data['homepage_description'] = $this->model_fido_homepage->getHomepageDescriptions($this->request->get['homepage_id']);
		} else {
			$this->data['homepage_description'] = array();
		}

		$this->load->model('setting/store');

		if ($manager_status) {
			$this->data['stores'] = $this->model_fido_homepage->getStores($this->user->getId());
		} else {
			$this->data['stores'] = $this->model_setting_store->getStores();
		}

		if (isset($this->request->post['homepage_store'])) {
			$this->data['homepage_store'] = $this->request->post['homepage_store'];
		} elseif (isset($homepage_info)) {
			$this->data['homepage_store'] = $this->model_fido_homepage->getHomepageStores($this->request->get['homepage_id']);
		} else {
			$this->data['homepage_store'] = array(0);
		}			

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($homepage_info)) {
			$this->data['status'] = $homepage_info['status'];
		} else {
			$this->data['status'] = '';
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($homepage_info)) {
			$this->data['sort_order'] = $homepage_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($homepage_info)) {
			$this->data['image'] = $homepage_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (!empty($homepage_info) && $homepage_info['image'] && file_exists(DIR_IMAGE . $homepage_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($homepage_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->template = 'module/homepage/form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/homepage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['homepage_module'])) {
			foreach ($this->request->post['homepage_module'] as $key => $value) {
				if (!$value['image_width'] || !$value['image_height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/homepage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['homepage_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/homepage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
