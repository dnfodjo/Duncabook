<?php

class ControllerModuleMultiMerchMasspay extends ControllerMultisellerBase {
	private $version = '2.0';
	private $name = 'multimerch_masspay';
	
	private $settings = Array(
		"msconf_masspay_sandbox" => 1,
		"msconf_masspay_api_username" => "",
		"msconf_masspay_api_password" => "",
		"msconf_masspay_api_signature" => "",
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
		$set = $this->model_setting_setting->getSetting('msconf_masspay');

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
		
		$this->model_setting_setting->editSetting('msconf_masspay', $set);
	}
	
	public function install() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('msconf_masspay', $this->settings);
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
		$this->load->language("module/{$this->name}");
		$this->load->model('setting/setting');
		
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
	
	/*
	 * Mass balance payout
	 */
	public function jxConfirmMasspay() {
		$this->validate(__FUNCTION__);
		$json = array();
	
		if (isset($this->request->post['selected']) || isset($this->request->post['ms-pay-all'])) {
			$payments = array();
			$total = $this->currency->format(0, $this->config->get('config_currency'), 1, false);
	
			if(isset($this->request->post['ms-pay-all']))
				$sellers_id = $this->MsLoader->MsSeller->getSellers();
			else
				$sellers_id = $this->request->post['selected'];
	
			foreach ($sellers_id as $seller_id) {
				if(isset($this->request->post['ms-pay-all'])) $seller_id = $seller_id['seller_id'];
	
				$seller = $this->MsLoader->MsSeller->getSeller($seller_id);
				if (!$seller || !$seller['ms.paypal']) continue;
				$amount = $this->MsLoader->MsBalance->getSellerBalance($seller_id) - $this->MsLoader->MsBalance->getReservedSellerFunds($seller_id);
				if ($amount <= 0) continue;
	
				$total += abs($amount);
				$payments[] = array (
					'nickname' => $seller['ms.nickname'],
					'paypal' => $seller['ms.paypal'],
					'amount' => $this->currency->format(abs($amount), $this->config->get('config_currency'), '', false)
				);
			}
	
			if ($payments) {
				$this->data['total_amount'] = $this->currency->format($total, $this->config->get('config_currency'), '', false);
				$this->data['payments'] = $payments;
				list($template, $children) = $this->MsLoader->MsHelper->admLoadTemplate('module/multimerch_masspay_dialog');
				$json['html'] = $this->load->view($template, $this->data);
			} else {
				$json['error'] = $this->language->get('ms_error_payment_norequests');
			}
		} else {
			$json['error'] = $this->language->get('ms_error_payment_norequests');
		}
		return $this->response->setOutput(json_encode($json));
	}
	
	public function jxCompleteMasspay() {
		$this->validate(__FUNCTION__);
		$json = array();
	
		if (!isset($this->request->post['selected']) && !isset($this->request->post['ms-pay-all'])) {
			$json['error'] = $this->language->get('ms_error_payment_norequests');
			$this->response->setOutput(json_encode($json));
			return;
		}
	
		require_once(DIR_SYSTEM . 'library/ms-paypal.php');
	
		$requestParams = array(
			'RECEIVERTYPE' => 'EmailAddress',
			'CURRENCYCODE' => $this->config->get('config_currency')
		);
	
		$paymentParams = array();
	
		$i = 0;
	
		if(isset($this->request->post['ms-pay-all']))
			$sellers_id = $this->MsLoader->MsSeller->getSellers();
		else
			$sellers_id = $this->request->post['selected'];
	
		foreach ($sellers_id as $seller_id) {
			if(isset($this->request->post['ms-pay-all'])) $seller_id = $seller_id['seller_id'];
	
			$seller = $this->MsLoader->MsSeller->getSeller($seller_id);
			if (!$seller || !$seller['ms.paypal']) continue;
			$amount = $this->MsLoader->MsBalance->getSellerBalance($seller_id) - $this->MsLoader->MsBalance->getReservedSellerFunds($seller_id);
			if ($amount <= 0) continue;
	
			//create payment
			$payment_id = $this->MsLoader->MsPayment->createPayment(array(
				'seller_id' => $seller_id,
				'payment_type' => MsPayment::TYPE_PAYOUT,
				'payment_status' => MsPayment::STATUS_UNPAID,
				'payment_data' => $seller['ms.paypal'],
				'payment_method' => MsPayment::METHOD_PAYPAL,
				'amount' => $this->currency->format($amount, $this->config->get('config_currency'), '', FALSE),
				'currency_id' => $this->currency->getId($this->config->get('config_currency')),
				'currency_code' => $this->currency->getCode($this->config->get('config_currency')),
				'description' => sprintf($this->language->get('ms_payment_royalty_payout'), $seller['name'], $this->config->get('config_name'))
			));
				
			if (!empty($payment_id)) {
				$paymentParams['L_EMAIL' . $i] = $seller['ms.paypal'];
				$paymentParams['L_AMT' . $i] = abs($amount);
				$i++;
			}
		}
	
		if (empty($paymentParams)) {
			$json['error'] = $this->language->get('ms_error_payment_norequests');
			$this->response->setOutput(json_encode($json));
			return;
		}
	
		$paypal = new PayPal($this->config->get('msconf_masspay_api_username'), $this->config->get('msconf_masspay_api_password'), $this->config->get('msconf_masspay_api_signature'), $this->config->get('msconf_masspay_sandbox'));
		$response = $paypal->request('MassPay',$requestParams + $paymentParams);
	
		if (!$response) {
			$json['error'] = $this->language->get('ms_error_withdraw_response');
			$json['response'] = print_r($paypal->getErrors(), true);
		} else if ($response['ACK'] != 'Success') {
			$json['error'] = $this->language->get('ms_error_withdraw_status');
			$json['response'] = print_r($response, true);
		} else {
			$json['success'] = $this->language->get('ms_success_transactions');
			$json['response'] = print_r($response, true);
			//$mails = array();
			foreach ($sellers_id as $seller_id) {
				$seller = $this->MsLoader->MsSeller->getSeller($seller_id);
				
				$result = $this->MsLoader->MsPayment->getPayments(
					array(
						'seller_id' => $seller_id,
						'payment_status' => array(MsPayment::STATUS_UNPAID),
						'payment_type' => array(MsPayment::TYPE_PAYOUT),
						'single' => 1
					)
				);
	
				$this->MsLoader->MsPayment->updatePayment($result['payment_id'],
					array(
						'payment_status' => MsPayment::STATUS_PAID,
						'date_paid' => date( 'Y-m-d H:i:s')
					)
				);
	
				$this->MsLoader->MsBalance->addBalanceEntry(
					$result['seller_id'],
					array(
						'payment_id' => $result['payment_id'],
						'balance_type' => MsBalance::MS_BALANCE_TYPE_WITHDRAWAL,
						'amount' => -$result['amount'],
						'description' => sprintf($this->language->get('ms_payment_royalty_payout'), $seller['name'], $this->config->get('config_name'))
					)
				);
			}
		}
	
		return $this->response->setOutput(json_encode($json));
	}
	
	
	/*
	 * Mass payment payout
	 */
	public function jxConfirmPayment() {
		$this->validate(__FUNCTION__);
		$json = array();
	
		if (isset($this->request->post['selected'])) {
			$payments = array();
			$total = $this->currency->format(0, $this->config->get('config_currency'), 1, false);
			foreach ($this->request->post['selected'] as $payment_id) {
				$result = $this->MsLoader->MsPayment->getPayments(
					array(
						'payment_id' => $payment_id,
						'payment_status' => array(MsPayment::STATUS_UNPAID),
						'payment_type' => array(MsPayment::TYPE_PAYOUT, MsPayment::TYPE_PAYOUT_REQUEST),
						'single' => 1
					)
				);
	
				if ($result) {
					$total += abs($result['amount']);
					$payments[] = array (
						'nickname' => $result['nickname'],
						'paypal' => $result['payment_data'],
						'amount' => $this->currency->format(abs($result['amount']), $result['currency_code'], '', false)
					);
				}
			}
				
			if ($payments) {
				$this->data['total_amount'] = $this->currency->format($total, $this->config->get('config_currency'), '', false);
				$this->data['payments'] = $payments;
				list($template, $children) = $this->MsLoader->MsHelper->admLoadTemplate('module/multimerch_masspay_dialog');
				$json['html'] = $this->load->view($template, $this->data);
			} else {
				$json['error'] = $this->language->get('ms_error_withdraw_norequests');
			}
		} else {
			$json['error'] = $this->language->get('ms_error_withdraw_norequests');
		}
		return $this->response->setOutput(json_encode($json));
	}
	
	public function jxCompletePayment() {
		$this->validate(__FUNCTION__);
		$json = array();
	
		if (!isset($this->request->post['selected'])) {
			$json['error'] = $this->language->get('ms_error_withdraw_norequests');
			$this->response->setOutput(json_encode($json));
			return;
		}
	
		require_once(DIR_SYSTEM . 'library/ms-paypal.php');
	
		$requestParams = array(
			'RECEIVERTYPE' => 'EmailAddress',
			'CURRENCYCODE' => $this->config->get('config_currency')
		);
	
		$paymentParams = array();
	
		$i = 0;
		foreach ($this->request->post['selected'] as $payment_id) {
			$result = $this->MsLoader->MsPayment->getPayments(
				array(
					'payment_id' => $payment_id,
					'payment_status' => array(MsPayment::STATUS_UNPAID),
					'payment_type' => array(MsPayment::TYPE_PAYOUT, MsPayment::TYPE_PAYOUT_REQUEST),
					'single' => 1
				)
			);
				
			if (!empty($result)) {
				$paymentParams['L_EMAIL' . $i] = $result['payment_data'];
				$paymentParams['L_AMT' . $i] = abs($result['amount']);
				$i++;
			}
		}
	
		if (empty($paymentParams)) {
			$json['error'] = $this->language->get('ms_error_withdraw_norequests');
			$this->response->setOutput(json_encode($json));
			return;
		}
	
		$paypal = new PayPal($this->config->get('msconf_masspay_api_username'), $this->config->get('msconf_masspay_api_password'), $this->config->get('msconf_masspay_api_signature'), $this->config->get('msconf_masspay_sandbox'));
		$response = $paypal->request('MassPay',$requestParams + $paymentParams);
	
		if (!$response) {
			$json['error'] = $this->language->get('ms_error_withdraw_response');
			$json['response'] = print_r($paypal->getErrors(), true);
		} else if ($response['ACK'] != 'Success') {
			$json['error'] = $this->language->get('ms_error_withdraw_status');
			$json['response'] = print_r($response, true);
		} else {
			$json['success'] = $this->language->get('ms_success_transactions');
			$json['response'] = print_r($response, true);
			//$mails = array();
			foreach ($this->request->post['selected'] as $payment_id) {
				$result = $this->MsLoader->MsPayment->getPayments(
					array(
						'payment_id' => $payment_id,
						'payment_status' => array(MsPayment::STATUS_UNPAID),
						'payment_type' => array(MsPayment::TYPE_PAYOUT, MsPayment::TYPE_PAYOUT_REQUEST),
						'single' => 1
					)
				);
	
				$this->MsLoader->MsPayment->updatePayment($payment_id,
					array(
						'payment_status' => MsPayment::STATUS_PAID,
						'description' => 'Paid',
						'date_paid' => 1
					)
				);
	
				$this->MsLoader->MsBalance->addBalanceEntry(
					$result['seller_id'],
					array(
						'payment_id' => $payment_id,
						'balance_type' => MsBalance::MS_BALANCE_TYPE_WITHDRAWAL,
						'amount' => -$result['amount'],
						'description' => 'Payout'
					)
				);
			}
		}
	
		return $this->response->setOutput(json_encode($json));
	}
}
?>
