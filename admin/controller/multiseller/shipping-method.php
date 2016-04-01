<?php

class ControllerMultisellerShippingMethod extends ControllerMultisellerBase {
	private $error = array();
	
	public function __construct($registry) {
		parent::__construct($registry);		
		$this->data = array_merge($this->data, $this->load->language('multiseller/multiseller_physical'));
	}
	
	// List all the shipping methods
	public function index() {
		$this->validate(__FUNCTION__);
		
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'msmd.name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		
		$url = '';
		
		$url .= isset($this->request->get['sort']) ? '&sort=' . $this->request->get['sort'] : '';
		$url .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] : '';
		$url .= isset($this->request->get['page']) ? '&page=' . $this->request->get['page'] : '';
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->admSetBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_menu_multiseller'),
				'href' => $this->url->link('multiseller/dashboard', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('ms_catalog_shipping_methods_breadcrumbs'),
				'href' => $this->url->link('multiseller/shipping-method', '', 'SSL'),
			)
		));
		
		$this->data['insert'] = $this->url->link('multiseller/shipping-method/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('multiseller/shipping-method/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
	
		$this->data['shipping_methods'] = array();
		
		$sort_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$total_shipping_methods = $this->MsLoader->MsShippingMethod->getTotalShippingMethods();
		$results = $this->MsLoader->MsShippingMethod->getShippingMethods($sort_data);
		
		foreach ($results as $result) {
			$this->data['shipping_methods'][] = array(
				'shipping_method_id' => $result['shipping_method_id'],
				'name'              => $result['name'],
				'selected'          => isset($this->request->post['selected']) && in_array($result['shipping_method_id'], $this->request->post['selected'])
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total_shipping_methods;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link("multiseller/shipping-method", 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();
		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($total_shipping_methods) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_shipping_methods - $this->config->get('config_limit_admin'))) ? $total_shipping_methods : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_shipping_methods, ceil($total_shipping_methods / $this->config->get('config_limit_admin')));

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['shipping'])) {
			$this->data['error_shipping'] = $this->error['shipping'];
		} else {
			$this->data['error_shipping'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['token'] = $this->session->data['token'];
		$this->data['heading'] = $this->language->get('ms_catalog_shipping_methods_heading');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->document->setTitle($this->language->get('ms_catalog_shipping_methods_heading'));
		
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('multiseller/shipping-method.tpl', $this->data));
	}
	
	// Insert a new shipping method
	public function insert() {
		$this->data['token'] = $this->session->data['token'];
		$this->data['heading'] = $this->language->get('ms_catalog_insert_shipping_method_heading');
		$this->document->setTitle($this->language->get('ms_catalog_insert_shipping_method_heading'));
		
		$this->data['cancel'] = $this->url->link('multiseller/shipping-method', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->data['shipping_method'] = NULL;
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->admSetBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_menu_multiseller'),
				'href' => $this->url->link('multiseller/dashboard', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('ms_catalog_shipping_methods_breadcrumbs'),
				'href' => $this->url->link('multiseller/shipping-method', '', 'SSL'),
			)
		));
		
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('multiseller/shipping-method-form.tpl', $this->data));
	}
	
	// Update a shipping method
	public function update() {
		$this->validate(__FUNCTION__);
		
		$this->data['token'] = $this->session->data['token'];
		$this->data['heading'] = $this->language->get('ms_catalog_edit_shipping_method_heading');
		$this->document->setTitle($this->language->get('ms_catalog_edit_shipping_method_heading'));
		
		$this->data['cancel'] = $this->url->link('multiseller/shipping-method', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$shipping_method = $this->MsLoader->MsShippingMethod->getShippingMethod($this->request->get['shipping_method_id']);
		
		$this->data['shipping_method'] = array(
			'shipping_method_id' => $shipping_method['shipping_method_id'],
			'description' => $this->MsLoader->MsShippingMethod->getShippingMethodDescriptions($this->request->get['shipping_method_id'])
		);
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->admSetBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_menu_multiseller'),
				'href' => $this->url->link('multiseller/dashboard', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('ms_catalog_shipping_methods_breadcrumbs'),
				'href' => $this->url->link('multiseller/shipping-method', '', 'SSL'),
			)
		));		
		
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('multiseller/shipping-method-form.tpl', $this->data));
	}
		
	// Single delete or bulk delete of shipping methods
	public function delete() { 
		if (isset($this->request->get['shipping_method_id'])) $this->request->post['selected'] = array($this->request->get['shipping_method_id']);
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $shipping_method_id) {
				$this->MsLoader->MsShippingMethod->deleteShippingMethod($shipping_method_id);
			}
			
			$this->session->data['success'] = $this->language->get('ms_success');
			
			$url = '';
			
			$url .= isset($this->request->get['sort']) ? '&sort=' . $this->request->get['sort'] : '';
			$url .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] : '';
			$url .= isset($this->request->get['page']) ? '&page=' . $this->request->get['page'] : '';
			
			$this->response->redirect($this->url->link('multiseller/shipping-method', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->index();
	}
	
	// Get form for adding/editing shipping methods
	private function getEditForm() {
		$this->data['heading'] = $this->language->get('ms_catalog_insert_shipping_method_heading');
		
		if (!isset($this->request->get['shipping_method_id'])) {
			$this->data['action'] = $this->url->link('multiseller/shipping-method/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('multiseller/shipping-method/update', 'token=' . $this->session->data['token'] . '&shipping_method_id=' . $this->request->get['shipping_method_id'] . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('multiseller/shipping-method', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->post['shipping_method_description'])) {
			$this->data['shipping_method_description'] = $this->request->post['shipping_method_description'];
		} elseif (isset($this->request->get['shipping_method_id'])) {
			$this->data['shipping_method_description'] = 'a';
		} else {
			$this->data['shipping_method_description'] = array();
		}
		
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('multiseller/shipping-method-form.tpl', $this->data));
	}
	
	// Validate form
	private function validateForm() {
	
	}
	
	// Validate delete of the shipping method
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'multiseller/shipping-method')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		// Check whether there already are product/seller shipping method records using this shipping method
		foreach ($this->request->post['selected'] as $shipping_method_id) {
			$productShippingMethods = $this->MsLoader->MsShipping->productShippingMethodsExist($shipping_method_id);
			$sellerShippingMethods = $this->MsLoader->MsShipping->sellerShippingMethodsExist($shipping_method_id);
			if ($productShippingMethods > 0 || $sellerShippingMethods > 0) {
				$this->error['shipping'] = $this->language->get('ms_error_shipping_methods_exist');
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function jxSave() {
		$data = $this->request->post['shipping_method'];
		$json = array();

		foreach ($data['description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$json['errors']['name_' . $language_id] = $this->language->get('ms_error_shipping_method_name');
			}
		}

		if (empty($json['errors'])) {
			if (empty($data['shipping_method_id'])) {
				$this->MsLoader->MsShippingMethod->createShippingMethod($data);
			} else {
				$this->MsLoader->MsShippingMethod->editShippingMethod($data['shipping_method_id'], $data);
			}
			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->response->setOutput(json_encode($json));
	}
}
?>
