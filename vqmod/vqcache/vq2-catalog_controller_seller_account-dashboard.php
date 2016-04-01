<?php

class ControllerSellerAccountDashboard extends ControllerSellerAccount {
	public function index() {
		// paypal listing payment confirmation
		if (isset($this->request->post['payment_status']) && strtolower($this->request->post['payment_status']) == 'completed') {
			$this->data['success'] = $this->language->get('ms_account_sellerinfo_saved');
		}
		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
        $this->load->model('account/order');
		
		$seller_id = $this->customer->getId();
		
		$seller = $this->MsLoader->MsSeller->getSeller($seller_id);
		$seller_group_names = $this->MsLoader->MsSellerGroup->getSellerGroupDescriptions($seller['ms.seller_group']);
		$my_first_day = date('Y-m-d H:i:s', mktime(0, 0, 0, date("n"), 1));
		

				$this->data = array_merge($this->data, $this->load->language('module/multimerch_badges'));
			
				$badges = array_unique(array_merge(
					$this->MsLoader->MsBadge->getSellerGroupBadges(array('seller_id' => $seller['seller_id'], 'language_id' => $this->config->get('config_language_id'))),
					$this->MsLoader->MsBadge->getSellerGroupBadges(array('seller_group_id' => $seller['ms.seller_group'], 'language_id' => $this->config->get('config_language_id'))),
					$this->MsLoader->MsBadge->getSellerGroupBadges(array('seller_group_id' => $this->config->get('msconf_default_seller_group_id'), 'language_id' => $this->config->get('config_language_id')))
				), SORT_REGULAR);
		
				foreach ($badges as &$badge) {
					$badge['image'] = $this->model_tool_image->resize($badge['image'], $this->config->get('msconf_badge_width'), $this->config->get('msconf_badge_height'));
				}
				$seller['badges'] = $badges;
			
		$this->data['seller'] = array_merge(
			$seller,
			array('balance' => $this->currency->format($this->MsLoader->MsBalance->getSellerBalance($seller_id), $this->config->get('config_currency'))),
			array('commission_rates' => $this->MsLoader->MsCommission->calculateCommission(array('seller_id' => $seller_id))),
			array('total_earnings' => $this->currency->format($this->MsLoader->MsSeller->getTotalEarnings($seller_id), $this->config->get('config_currency'))),
			array('earnings_month' => $this->currency->format($this->MsLoader->MsSeller->getTotalEarnings($seller_id, array('period_start' => $my_first_day)), $this->config->get('config_currency'))),
			array('sales_month' => $this->MsLoader->MsOrderData->getTotalSales(array(
				'seller_id' => $seller_id,
				'period_start' => $my_first_day
			))),
			array('seller_group' => $seller_group_names[$this->config->get('config_language_id')]['name']),
			array('date_created' => date($this->language->get('date_format_short'), strtotime($seller['ms.date_created'])))
			//array('total_products' => $this->MsLoader->MsProduct->getTotalProducts(array(
				//'seller_id' => $seller_id,
				//'enabled' => ))
		);
		
		if ($seller['ms.avatar'] && file_exists(DIR_IMAGE . $seller['ms.avatar'])) {
			$this->data['seller']['avatar'] = $this->MsLoader->MsFile->resizeImage($seller['ms.avatar'], $this->config->get('msconf_seller_avatar_dashboard_image_width'), $this->config->get('msconf_seller_avatar_dashboard_image_height'));
		} else {
			$this->data['seller']['avatar'] = $this->MsLoader->MsFile->resizeImage('ms_no_image.jpg', $this->config->get('msconf_seller_avatar_dashboard_image_width'), $this->config->get('msconf_seller_avatar_dashboard_image_height'));
		}		
		
		$payments = $this->MsLoader->MsPayment->getPayments(
			array(
				'seller_id' => $seller_id,
			),
			array(
				'order_by'  => 'mpay.date_created',
				'order_way' => 'DESC',
				'offset' => 0,
				'limit' => 5
			)
		);
		 
		$orders = $this->MsLoader->MsOrderData->getOrders(
			array(
				'seller_id' => $seller_id,
				'order_status' => $this->config->get('msconf_display_order_statuses')
			),
			array(
				'order_by'  => 'date_added',
				'order_way' => 'DESC',
				'offset' => 0,
				'limit' => 5
			)
		);		

		$this->load->model('localisation/order_status');
		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();

    	foreach ($orders as $order) {

				$total = 0.0;
				$products = $this->MsLoader->MsOrderData->getOrderProducts(array('order_id' => $order['order_id'], 'seller_id' => $seller_id));
				$sellerShipping = $this->MsLoader->MsShipping->getOrderSellerShipping($order['order_id'], $seller_id, 0);
			
				$shippings = array();
				$atLeastOneShippable = false;
				if (empty($sellerShipping)) {
					foreach ($products as $key => $product) {
					    $products[$key]['options']	=  $this->model_account_order->getOrderOptions($order['order_id'], $product['order_product_id']);
						// Shippable
						if ($this->MsLoader->MsShipping->getOrderProductShippable($order['order_id'], $product['product_id'])) {
							$atLeastOneShippable = true;
							$productShipping = $this->MsLoader->MsShipping->getOrderProductShipping($order['order_id'], $product['product_id']);
							$shippings[] = array(
								'shipping_cost' => $productShipping['shipping_cost'],
								'name' => $productShipping['shipping_method_name']
								//'name' => $this->MsLoader->MsShippingMethod->getShippingMethodDescriptions($productShipping['product_shipping_method_id'])[$language_id]['name'],
							);
							$total += $productShipping['shipping_cost'];
						// Not shippable
						} else {
							$shippings[] = array(
								'shipping_cost' => "0",
								'name' => "--"
							);
						}
					}
				}
				else {
					$shippings[] = array(
						'shipping_cost' => $sellerShipping['shipping_cost'],
						'name' => $sellerShipping['shipping_method_name']
						//'name' => $this->MsLoader->MsShippingMethod->getShippingMethodDescriptions($sellerShipping['seller_shipping_method_id'])[$language_id]['name'],
					);
					$total += $sellerShipping['shipping_cost'];
				}
			
				$shipped = 0;
				$orderShipping = $this->MsLoader->MsShipping->getOrderShippingTracking($order['order_id'], $seller_id);
				if ($orderShipping) {
					$shipped = $orderShipping['shipped']; // Is shipped already
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

            $products = $this->MsLoader->MsOrderData->getOrderProducts(array('order_id' => $order['order_id'], 'seller_id' => $seller_id));

            foreach($products as $key=>$p)
                $products[$key]['options']	=  $this->model_account_order->getOrderOptions($order['order_id'], $p['order_product_id']);

    		$this->data['orders'][] = array(
    			'order_id' => $order['order_id'],
    			'customer' => "{$order['firstname']} {$order['lastname']} ({$order['email']})",
				'status' => $status_name,
    			
    			'products' => $products,
				'shippings' => $shippings,
				'shippable' => $atLeastOneShippable,
			
    			'date_created' => date($this->language->get('date_format_short'), strtotime($order['date_added'])),
   				
    			'total' => $this->currency->format($total + $this->MsLoader->MsOrderData->getOrderTotal($order['order_id'], array('seller_id' => $seller_id)), $this->config->get('config_currency'))
			
   			);
   		}
		

				$this->data = array_merge($this->load->language('module/multimerch_comments'), $this->data);
			
				$comments = $this->MsLoader->MsComments->getSellerProductComments(
					array(
						'seller_id' => $seller_id,
						'displayed' => 1
					),
					array(
						'order_by'  => 'create_time',
						'order_way' => 'DESC',
						'offset' => 0,
						'limit' => 5
					)
				);
		
				foreach ($comments as $result) {
					$product = $this->MsLoader->MsProduct->getProduct($result['product_id']);
					if (!$this->config->get('msconf_hide_customer_email')) {
						$customer_name = $result['name'];
					} else {
						$customer_name = "{$result['name']} ({$result['email']})";
					}
					$this->data['comments'][] = array(
						'name' => $customer_name,
						'product_id' => $result['product_id'],
						'product_name' => $product['languages'][$this->config->get('config_language_id')]['name'],
						'comment' => (mb_strlen($result['comment']) > 80 ? mb_substr($result['comment'], 0, 80) . '...' : $result['comment']),
						'date_created' => date($this->language->get('date_format_short'), $result['create_time']),
					);
				}
			
		$this->data['link_back'] = $this->url->link('account/account', '', 'SSL');
		
		$this->document->setTitle($this->language->get('ms_account_dashboard_heading'));
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
			array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('ms_account_dashboard_breadcrumbs'),
				'href' => $this->url->link('seller/account-dashboard', '', 'SSL'),
			)
		));
		
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-dashboard');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}
}

?>
