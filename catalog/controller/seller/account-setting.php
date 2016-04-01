<?php

class ControllerSellerAccountSetting extends ControllerSellerAccount {
	public function __construct($registry) {
		parent::__construct($registry);
		$this->response->redirect($this->url->link('seller/account-profile', '', 'SSL'));
	}

	public function index() {
		$this->response->redirect($this->url->link('seller/account-setting/invoice', '', 'SSL'));
	}
	
	private function _getMenu() {
		return array(
			array(
				'name' => $this->language->get('Invoicing'),
				'link' => $this->url->link('seller/account-setting/invoice')
			)
		);
	}
	
	public function invoice() {
		$this->document->setTitle($this->language->get(__FUNCTION__ . '_title'));
		$customer_id = $this->customer->getId();

		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
			array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('ms_account_dashboard_breadcrumbs'),
				'href' => $this->url->link('seller/account-dashboard', '', 'SSL'),
			),
			array(
				'text' => $this->language->get(__FUNCTION__ . '_breadcrumbs'),
				'href' => $this->url->link('seller/account-setting/' . __FUNCTION__, '', 'SSL'),
			)
		));

		//Save the data from Form
		if(isset($this->request->post) && $this->request->post){
			$this->MsLoader->MsSetting->setSetting(__FUNCTION__, $this->request->post, $customer_id);
		}

		//Get the data for Form
		$this->data['main_enabled'] = $this->MsLoader->MsSetting->getSetting(__FUNCTION__, 'main_enabled', $customer_id);
		$this->data['main_address_city'] = $this->MsLoader->MsSetting->getSetting(__FUNCTION__, 'main_address_city', $customer_id);
		
		$this->data['menu'] = $this->_getMenu();
		
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('multiseller/settings/seller_invoice');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}
}
?>
