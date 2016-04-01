<?php

class ControllerSellerAccountOrder extends ControllerSellerAccount {
	public function getTableData() {
		$colMap = array(
			'customer_name' => 'firstname',
			'date_created' => 'o.date_added',
		);
		
		$sorts = array('order_id', 'customer_name', 'date_created', 'total_amount');
		$filters = array_merge($sorts, array('products'));
		
		list($sortCol, $sortDir) = $this->MsLoader->MsHelper->getSortParams($sorts, $colMap);
		$filterParams = $this->MsLoader->MsHelper->getFilterParams($filters, $colMap);
		
		$seller_id = $this->customer->getId();
		$this->load->model('account/order');

		$orders = $this->MsLoader->MsOrderData->getOrders(
			array(
				'seller_id' => $seller_id,
				'order_status' => $this->config->get('msconf_display_order_statuses')
			),
			array(
				'order_by'  => $sortCol,
				'order_way' => $sortDir,
				'offset' => $this->request->get['iDisplayStart'],
				'limit' => $this->request->get['iDisplayLength'],
				'filters' => $filterParams
			),
			array(
				'total_amount' => 1,
				'products' => 1,
			)
		);

		$total_orders = isset($orders[0]) ? $orders[0]['total_rows'] : 0;

		$columns = array();
		foreach ($orders as $order) {
			$order_products = $this->MsLoader->MsOrderData->getOrderProducts(array('order_id' => $order['order_id'], 'seller_id' => $seller_id));
			
			if ($this->config->get('msconf_hide_customer_email')) {
				$customer_name = "{$order['firstname']} {$order['lastname']}";
			} else {
				$customer_name = "{$order['firstname']} {$order['lastname']} ({$order['email']})";
			}
			
			$products = "";
			foreach ($order_products as $p) {
                $products .= "<p style='text-align:left'>";
				$products .= "<span class='name'>" . ($p['quantity'] > 1 ? "{$p['quantity']} x " : "") . "<a href='" . $this->url->link('product/product', 'product_id=' . $p['product_id'], 'SSL') . "'>{$p['name']}</a></span>";

                $options   = $this->model_account_order->getOrderOptions($order['order_id'], $p['order_product_id']);

                foreach ($options as $option)
                {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                    }

                    $option['value']	=  utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value;

                    $products .= "<br />";
                    $products .= "<small> - {$option['name']} : {$option['value']} </small>";
                }

                $products .= "<span class='total'>" . $this->currency->format($p['seller_net_amt'], $this->config->get('config_currency')) . "</span>";
				$products .= "</p>";
			}

			$suborder = $this->MsLoader->MsOrderData->getSuborders(array(
				'order_id' => $order['order_id'],
				'seller_id' => $this->customer->getId(),
				'single' => 1
			));

			$status_name = $this->MsLoader->MsHelper->getStatusName(array('order_status_id' => $order['order_status_id']));

			if (isset($suborder['order_status_id']) && $suborder['order_status_id'] && $order['order_status_id'] != $suborder['order_status_id']) {
				$status_name .= ' (' . $this->MsLoader->MsHelper->getStatusName(array('order_status_id' => $suborder['order_status_id'])) . ')';
			}

			$columns[] = array_merge(
				$order,
				array(
					'order_id' => $order['order_id'],
					'customer_name' => $customer_name,
					'products' => $products,
					'suborder_status' => $status_name,
					'date_created' => date($this->language->get('date_format_short'), strtotime($order['date_added'])),
					'total_amount' => $this->currency->format($order['total_amount'], $this->config->get('config_currency')),
					'view_order' => '<a href="' . $this->url->link('seller/account-order/viewOrder', 'order_id=' . $order['order_id'], 'SSL') . '" class="ms-button ms-button-view" title="' . $this->language->get('ms_view_modify') . '"></a>'
				)
			);
		}
		
		$this->response->setOutput(json_encode(array(
			'iTotalRecords' => $total_orders,
			'iTotalDisplayRecords' => $total_orders,
			'aaData' => $columns
		)));
	}

	public function viewOrder() {
		$order_id = isset($this->request->get['order_id']) ? (int)$this->request->get['order_id'] : 0;
		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id, 'seller');
		$products = $this->MsLoader->MsOrderData->getOrderProducts(array(
			'order_id' => $order_id,
			'seller_id' => $this->customer->getId()
		));

		// stop if no order or no products belonging to seller
		if (!$order_info || empty($products)) $this->response->redirect($this->url->link('seller/account-order', '', 'SSL'));

		// load default OC language file for orders
		$this->data = array_merge($this->data, $this->load->language('account/order'));

		// order statuses
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$suborder = $this->MsLoader->MsOrderData->getSuborders(array(
			'order_id' => $order_id,
			'seller_id' => $this->customer->getId(),
			'single' => 1
		));

		$this->data['suborder_status_id'] = $suborder_status_id = $suborder['order_status_id'];
		$this->data['suborder_id'] = $suborder_id = $suborder['suborder_id'];

		// OC way of displaying addresses and invoices
		$this->data['invoice_no'] = isset($order_info['invoice_no']) ? $order_info['invoice_prefix'] . $order_info['invoice_no'] : '';

		$this->data['order_status_id'] = $order_info['order_status_id'];
		$this->data['order_id'] = $this->request->get['order_id'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

		$types = array("payment", "shipping");

		foreach ($types as $key => $type) {
			if ($order_info[$type . '_address_format']) {
				$format = $order_info[$type . '_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . '{telephone}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}',
				'{telephone}'
			);

			$replace = array(
				'firstname' => $order_info[$type . '_firstname'],
				'lastname'  => $order_info[$type . '_lastname'],
				'company'   => $order_info[$type . '_company'],
				'address_1' => $order_info[$type . '_address_1'],
				'address_2' => $order_info[$type . '_address_2'],
				'city'      => $order_info[$type . '_city'],
				'postcode'  => $order_info[$type . '_postcode'],
				'zone'      => $order_info[$type . '_zone'],
				'zone_code' => $order_info[$type . '_zone_code'],
				'country'   => $order_info[$type . '_country'],
				'telephone'   => $order_info['telephone']
			);

			$this->data[$type . '_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$this->data[$type . '_method'] = $order_info[$type . '_method'];
		}

		// products
		$this->data['products'] = array();
		foreach ($products as $product) {
			$this->data['products'][] = array(
				'product_id' => $product['product_id'],
				'name'     => $product['name'],
				'model'    => $product['model'],
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'return'   => $this->url->link('account/return/insert', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
			);
		}

		// sub-order history entries
		$this->data['order_history'] = $this->MsLoader->MsOrderData->getSuborderHistory(array(
			'suborder_id' => $suborder_id
		));

		// totals @todo
		$subordertotal = $this->currency->format($this->MsLoader->MsOrderData->getOrderTotal($order_id, array('seller_id' => $this->customer->getId() )));
		//$this->data['totals'] = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
		$this->data['totals'][0] = array('text' => $subordertotal, 'title' => 'Total');

		// render
		$this->data['link_back'] = $this->url->link('seller/account-order', '', 'SSL');
		$this->data['continue'] = $this->url->link('account/order', '', 'SSL');

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
				'text' => $this->language->get('ms_account_orders_breadcrumbs'),
				'href' => $this->url->link('seller/account-order', '', 'SSL'),
			)
		));

		$this->document->setTitle($this->language->get('text_order'));

		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-order-info');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}
	
	public function invoice() {
		$this->response->redirect($this->url->link('seller/account-order', '', 'SSL'));

		$this->data['title'] = $this->document->getTitle();
		
		if($this->customer->isLogged()){
			$customer_id = $this->customer->getId();
		}
		else{
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		$this->data['base'] = $server;
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');

		if ($this->config->get('config_google_analytics_status')) {
			$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['google_analytics'] = '';
		}
		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		$this->data['company'] = $this->MsLoader->MsSeller->getCompany();
		$this->data['phone'] = $this->customer->getTelephone();
		$this->data['fax'] = $this->customer->getFax();
		$this->data['mail'] = $this->customer->getEmail();
		$address_id = $this->customer->getAddressId();
		if($address_id != 0){
			$this->load->model('account/address');
			$address_fields = $this->model_account_address->getAddress($address_id);
			if(isset($address_fields['address_1']) && $address_fields['address_1']){
				$adr1 = $address_fields['address_1'].', ';
			} else{
				$adr1 = '';
			}
			if(isset($address_fields['city']) && $address_fields['city']){
				$adr2 = $address_fields['city'].', ';
			} else{
				$adr2 = '';
			}
			if(isset($address_fields['zone']) && $address_fields['zone']){
				$adr3 = $address_fields['zone'].', ';
			} else{
				$adr3 = '';
			}
			if(isset($address_fields['country']) && $address_fields['country']){
				$adr4 = $address_fields['country'];
			} else{
				$adr4 = '';
			}

			$this->data['address'] = $adr1.$adr2.$adr3.$adr4;
		}
		
		$avatar = $this->MsLoader->MsSeller->getSellerAvatar($customer_id);
		if (is_file(DIR_IMAGE . $avatar['avatar'])) {
			$this->data['logo'] = $server . 'image/' . $avatar['avatar'];
		} else {
			$this->data['logo'] = '';
		}

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));
			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;
					break;
				}
			}
		}
		
		$order_id = isset($this->request->get['order_id']) ? (int)$this->request->get['order_id'] : 0;
		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id, 'seller');
		$products = $this->MsLoader->MsOrderData->getOrderProducts(array(
			'order_id' => $order_id,
			'seller_id' => $customer_id
		));

		// stop if no order or no products belonging to seller
		if (!$order_info || empty($products)) $this->response->redirect($this->url->link('seller/account-order', '', 'SSL'));

		// load default OC language file for orders
		$this->data = array_merge($this->data, $this->load->language('account/order'));

		// order statuses
		$this->load->model('localisation/order_status');

		// OC way of displaying addresses and invoices
		$this->data['invoice_no'] = isset($order_info['invoice_no']) ? $order_info['invoice_prefix'] . $order_info['invoice_no'] : '';
		$this->data['order_status_id'] = $order_info['order_status_id'];
		$this->data['order_id'] = $this->request->get['order_id'];

		$types = array("payment");

		foreach ($types as $key => $type) {
			if ($order_info[$type . '_address_format']) {
				$format = $order_info[$type . '_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . ", " . '{city}' . "\n" . '{zone}' . ", " . '{country}' . "\n" . '{telephone}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{city}',
				'{zone}',
				'{country}',
				'{telephone}'
			);

			$replace = array(
				'firstname'	=> $order_info[$type . '_firstname'],
				'lastname'	=> $order_info[$type . '_lastname'],
				'company'	=> $order_info[$type . '_company'],
				'address_1'	=> $order_info[$type . '_address_1'],
				'city'		=> $order_info[$type . '_city'],
				'zone'		=> $order_info[$type . '_zone'],
				'country'	=> $order_info[$type . '_country'],
				'telephone'	=> $order_info['telephone']
			);

			$this->data[$type . '_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		}

		// products
		$this->data['products'] = array();
		foreach ($products as $product) {
			$this->data['products'][] = array(
				'product_id'=> $product['product_id'],
				'name'		=> $product['name'],
				'model'		=> $product['model'],
				'quantity'	=> $product['quantity'],
				'price'		=> $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'		=> $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'return'	=> $this->url->link('account/return/insert', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
			);
		}

		// totals @todo
		$subordertotal = $this->currency->format($this->MsLoader->MsOrderData->getOrderTotal($order_id, array('seller_id' => $this->customer->getId() )));
		$this->data['totals'][0] = array('text' => $subordertotal, 'title' => 'Total');
		$this->data['title'] = $this->language->get('heading_invoice_title');
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-invoice');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}
		
	public function index() {
		$this->data['link_back'] = $this->url->link('account/account', '', 'SSL');
		
		$this->document->setTitle($this->language->get('ms_account_order_information'));
		
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
				'text' => $this->language->get('ms_account_orders_breadcrumbs'),
				'href' => $this->url->link('seller/account-order', '', 'SSL'),
			)
		));
		
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-order');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}

	public function jxAddHistory() {
		if(!isset($this->request->post['order_comment']) || !isset($this->request->post['order_status']) || !isset($this->request->post['suborder_id'])) return false;
		if(empty($this->request->post['order_comment']) && !$this->request->post['order_status']) return false;

		// keep current status if not changing explicitly
		$suborderData = $this->MsLoader->MsOrderData->getSuborders(array(
			'suborder_id' => (int)$this->request->post['suborder_id'],
			'single' => 1
		));

		$suborder_status_id = $this->request->post['order_status'] ? (int)$this->request->post['order_status'] : $suborderData['order_status_id'];

		$this->MsLoader->MsOrderData->updateSuborderStatus(array(
			'suborder_id' => (int)$this->request->post['suborder_id'],
			'order_status_id' => $suborder_status_id
		));

		$this->MsLoader->MsOrderData->addSuborderHistory(array(
			'suborder_id' => (int)$this->request->post['suborder_id'],
			'comment' => $this->request->post['order_comment'],
			'order_status_id' => $suborder_status_id
		));

		// get customer information
		$this->load->model('checkout/order');
		$this->load->model('account/order');
		$order_info = $this->model_checkout_order->getOrder($suborderData['order_id']);

		$mails[] = array(
			'type' => MsMail::CMT_ORDER_UPDATED,
			'data' => array(
				'status' => $this->MsLoader->MsHelper->getStatusName(array('order_status_id' => $suborder_status_id)),
				'comment' => $this->request->post['order_comment'],
				'seller_id' => $this->customer->getId(),
				'order_id' => $suborderData['order_id'],

				// send email to customer
				'recipients' => $order_info['email'],
				'addressee' => $order_info['firstname']
			)
		);

		$this->MsLoader->MsMail->sendMails($mails);
	}
}

?>
