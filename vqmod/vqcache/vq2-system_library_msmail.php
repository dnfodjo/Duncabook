<?php
class MsMail extends Model {
	const SMT_SELLER_ACCOUNT_CREATED = 1;
	const SMT_SELLER_ACCOUNT_AWAITING_MODERATION = 2;
	const SMT_SELLER_ACCOUNT_APPROVED = 3;
	const SMT_SELLER_ACCOUNT_DECLINED = 4;
	const SMT_SELLER_ACCOUNT_DISABLED = 17;
	const SMT_SELLER_ACCOUNT_ENABLED = 18;
	const SMT_SELLER_ACCOUNT_MODIFIED = 19;
	
	const SMT_PRODUCT_CREATED = 5;
	const SMT_PRODUCT_AWAITING_MODERATION = 6;
	const SMT_PRODUCT_MODIFIED = 21;
		
	const SMT_PRODUCT_PURCHASED = 11;
	
	const SMT_WITHDRAW_REQUEST_SUBMITTED = 12;
	const SMT_WITHDRAW_REQUEST_COMPLETED = 13;
	const SMT_WITHDRAW_REQUEST_DECLINED = 14;
	const SMT_WITHDRAW_PERFORMED = 15;
	
	const SMT_TRANSACTION_PERFORMED = 16;
	
	const SMT_SELLER_CONTACT = 20;
	const SMT_PRIVATE_MESSAGE = 22;

	const SMT_SELLER_VOTE = 23;
	
	const SMT_REMIND_LISTING = 30;

				const SMT_MARK_SHIPPED = 50;
				const SMT_TRACKING_INFORMATION = 51;
			
	const CMT_ORDER_UPDATED = 31;
	
	const AMT_SELLER_ACCOUNT_CREATED = 101;
	const AMT_SELLER_ACCOUNT_AWAITING_MODERATION = 102;
	
	const AMT_PRODUCT_CREATED = 103;
	const AMT_NEW_PRODUCT_AWAITING_MODERATION = 104;
	const AMT_EDIT_PRODUCT_AWAITING_MODERATION = 105;
	
	const AMT_PRODUCT_PURCHASED = 106;
	
	const AMT_WITHDRAW_REQUEST_SUBMITTED = 107;
	const AMT_WITHDRAW_REQUEST_COMPLETED = 108;
	
	public function __construct($registry) {
		parent::__construct($registry);

				$this->load->language('multiseller/multiseller_physical');
			
		$this->errors = array();
	}

	private function _modelExists($model) {
		$file  = DIR_APPLICATION . 'model/' . $model . '.php';
		return file_exists($file);
	}

	private function _getRecipients($mail_type) {
		if ($mail_type < 100)
			return $this->registry->get('customer')->getEmail();
		else {
			if (!$this->config->get('msconf_notification_email'))
				return $this->config->get('config_email');
			else
				return $this->config->get('msconf_notification_email');
		}
	}

	//TODO
	private function _getAddressee($mail_type) {
		if ($mail_type < 100)
			return $this->registry->get('customer')->getFirstname();
		else
			return '';//$this->registry->get('customer')->getFirstname();
	}
	
	private function _getOrderProducts($order_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "order_product
				WHERE order_id = " . (int)$order_id;
		
		$res = $this->db->query($sql);

		return $res->rows;
	}

	public function sendOrderMails($order_id) {
		$order_products = $this->_getOrderProducts($order_id);
		
		if (!$order_products)
			return false;
			
		$mails = array();
		foreach ($order_products as $product) {
			$seller_id = $this->MsLoader->MsProduct->getSellerId($product['product_id']);
			if ($seller_id) {
				$mails[$seller_id] = array(
					'type' => MsMail::SMT_PRODUCT_PURCHASED,
					'data' => array(
						'recipients' => $this->MsLoader->MsSeller->getSellerEmail($seller_id),
						'addressee' => $this->MsLoader->MsSeller->getSellerName($seller_id),
						'order_id' => $order_id,
						'seller_id' => $seller_id
					)
				);
			}
		}
		$this->sendMails($mails);
	}
	
	public function sendMails($mails) {
		foreach ($mails as $mail) {
			if (!isset($mail['data'])) {
				$this->sendMail($mail['type']);
			} else {
				$this->sendMail($mail['type'], $mail['data']);
			}
		}
	}
	
	public function sendMail($mail_type, $data = array()) {
		if (isset($data['product_id']) && $data['product_id']) {
			$product = $this->MsLoader->MsProduct->getProduct($data['product_id']);
			$n = reset($product['languages']);
			$product['name'] = $n['name'];
		}

		if (isset($data['order_id'])) {
			if ($this->_modelExists('checkout/order')) {
				// catalog
				$this->load->model('checkout/order');
				$this->load->model('account/order');
				$order_info = $this->model_checkout_order->getOrder($data['order_id']);
			} else {
				// admin
				$this->load->model('sale/order');
				$order_info = $this->model_sale_order->getOrder($data['order_id']);
			}
		}
		
		if (isset($data['seller_id'])) {
			$seller = $this->MsLoader->MsSeller->getSeller($data['seller_id']);
		}		

		//$message .= sprintf($this->language->get('ms_mail_regards'), HTTP_SERVER) . "\n" . $this->config->get('config_name');

		$mail = new Mail();

		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		if (!isset($data['recipients'])) {
			$mail->setTo($this->_getRecipients($mail_type));
		} else {
			$mail->setTo($data['recipients']);
		}
		
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		if (!isset($data['addressee'])) {
			if ($mail_type < 100) {
				$mail_text = sprintf($this->language->get('ms_mail_greeting'), $this->_getAddressee($mail_type));
			} else {
				$mail_text = $this->language->get('ms_mail_greeting_no_name');
			}
		} else {
			$mail_text = sprintf($this->language->get('ms_mail_greeting'), $data['addressee']);			
		}
		$mail_subject = '['.$this->config->get('config_name').'] ';
		
		//$mail_type = self::SMT_TRANSACTION_PERFORMED;
		
		// main switch
		switch($mail_type) {
			// seller
			case self::SMT_SELLER_ACCOUNT_CREATED:
				$mail_subject .= $this->language->get('ms_mail_subject_seller_account_created');
				$mail_text .= sprintf($this->language->get('ms_mail_seller_account_created'), $this->config->get('config_name'));
				break;
			case self::SMT_SELLER_ACCOUNT_AWAITING_MODERATION:
				$mail_subject .= $this->language->get('ms_mail_subject_seller_account_awaiting_moderation');
				$mail_text .= sprintf($this->language->get('ms_mail_seller_account_awaiting_moderation'), $this->config->get('config_name'));
				break;
			case self::SMT_SELLER_ACCOUNT_MODIFIED:
				$mail_subject .= $this->language->get('ms_mail_subject_seller_account_modified');
				$mail_text .= sprintf($this->language->get('ms_mail_seller_account_modified'), $this->config->get('config_name'), $this->language->get('ms_seller_status_' . $seller['ms.seller_status']));
				break;
				
				
			case self::SMT_PRODUCT_AWAITING_MODERATION:
				$mail_subject .= $this->language->get('ms_mail_subject_product_awaiting_moderation');
				$mail_text .= sprintf($this->language->get('ms_mail_product_awaiting_moderation'), $product['name'], $this->config->get('config_name'));
				break;
				
			case self::SMT_PRODUCT_MODIFIED:
				$mail_subject .= $this->language->get('ms_mail_subject_product_modified');
				$mail_text .= sprintf($this->language->get('ms_mail_product_modified'), $product['name'], $this->config->get('config_name'), $this->language->get('ms_product_status_' . $product['mp.product_status']));
				break;
			
			case self::SMT_PRODUCT_PURCHASED:
				$order_products = $this->MsLoader->MsOrderData->getOrderProducts(array('order_id' => $data['order_id'], 'seller_id' => $data['seller_id']));

				$shipping_type = $this->MsLoader->MsShipping->getOrderShippingType($data['order_id'], $data['seller_id']);
				$total_shipping_cost = 0.0;
				if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
					$seller_shipping = $this->MsLoader->MsShipping->getOrderSellerShipping($data['order_id'], $data['seller_id']);
					$total_shipping_cost = $seller_shipping['shipping_cost'];
				}
				$anything_shippable = false;
			


				$products = '';
				foreach ($order_products as $p) {
					if ($p['quantity'] > 1) $products .= "{$p['quantity']} x "; 
					$products .= "{$p['name']}\t" . $this->currency->format($p['seller_net_amt'], $this->config->get('config_currency')) . "\n";

					if ($this->_modelExists('account/order')) {
						$options = $this->model_account_order->getOrderOptions($data['order_id'], $p['order_product_id']);
					} else {
						$options = $this->model_sale_order->getOrderOptions($data['order_id'], $p['order_product_id']);
					}

					foreach ($options as $option)
					{
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}

						$option['value']	=  utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value;

						$products .= "\r\n";
						$products .= "- {$option['name']} : {$option['value']}";
					}


                    $products .= "\n";

					if ($this->MsLoader->MsShipping->getOrderProductShippable($data['order_id'], $p['product_id'])) {
						$anything_shippable = true;
						// Fixed shipping: show selected shipping method for each product, show shipping price
						if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) {
							$product_shipping = $this->MsLoader->MsShipping->getOrderProductShipping($data['order_id'], $p['product_id']);
							$total_shipping_cost += $product_shipping['shipping_cost'];
							$products .= "\t" . sprintf($this->language->get('ms_mail_product_shipping_info_fixed'), $product_shipping['shipping_method_name'], $this->currency->format($product_shipping['shipping_cost'], $this->config->get('config_currency')));
						}
					}
			
					$products .= "\n";
				}
			
				
				$total_products = $this->MsLoader->MsOrderData->getOrderTotal($data['order_id'], array('seller_id' => $data['seller_id']));
				$total_products_formatted = $this->currency->format($total_products, $this->config->get('config_currency'));
				
				$mail_subject .= $this->language->get('ms_mail_subject_product_purchased');
				if (!$this->config->get('msconf_hide_emails_in_emails')) {
					$mail_text .= sprintf($this->language->get('ms_mail_product_purchased_physical'), $this->config->get('config_name'), $order_info['firstname'] . ' ' . $order_info['lastname'], $order_info['email'], $products);
				} else { 
					$mail_text .= sprintf($this->language->get('ms_mail_product_purchased_physical_no_email'), $this->config->get('config_name'), $order_info['firstname'] . ' ' . $order_info['lastname'], $products);
				}
				
				// Total Product Price
				$mail_text .= sprintf($this->language->get('ms_mail_product_total_price'), $total_products_formatted);
				
				if ($anything_shippable) {
					// Total Shipping
					if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) {
						$mail_text .= sprintf($this->language->get('ms_mail_product_total_shipping'), $this->currency->format($total_shipping_cost, $this->config->get('config_currency')));
					} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
						$mail_text .= sprintf($this->language->get('ms_mail_product_shipping_method'), $seller_shipping['shipping_method_name']);
						$mail_text .= sprintf($this->language->get('ms_mail_product_total_shipping'), $this->currency->format($total_shipping_cost, $this->config->get('config_currency')));
					}
					
					// Total
					$mail_text .= sprintf($this->language->get('ms_mail_total_price_with_shipping'), $this->currency->format($total_products + $total_shipping_cost, $this->config->get('config_currency')));
					
					// Shipping Address
					$mail_text .= sprintf($this->language->get('ms_mail_product_purchased_info'), $order_info['shipping_firstname'], $order_info['shipping_lastname'], $order_info['shipping_company'], $order_info['shipping_address_1'], $order_info['shipping_address_2'], $order_info['shipping_city'], $order_info['shipping_postcode'], $order_info['shipping_zone'], $order_info['shipping_country']);
				}
			








				$order_info['comment'] = $this->MsLoader->MsOrderData->getOrderComment(array('order_id' => $data['order_id'], 'seller_id' => $data['seller_id']));
				if ($order_info['comment']) {
					$mail_text .= sprintf($this->language->get('ms_mail_product_purchased_comment'), $order_info['comment']);
				}

				if ($this->config->get('msconf_provide_buyerinfo') == 1 || ($this->config->get('msconf_provide_buyerinfo') == 2 && $product['shipping'] == 1))
				{
					$mail_text .= sprintf($this->language->get('ms_mail_product_purchased_info'), $order_info['shipping_firstname'], $order_info['shipping_lastname'], $order_info['shipping_company'], $order_info['shipping_address_1'], $order_info['shipping_address_2'], $order_info['shipping_city'], $order_info['shipping_postcode'], $order_info['shipping_zone'], $order_info['shipping_country']);
				}
				break;				
			
			case self::SMT_WITHDRAW_REQUEST_SUBMITTED:
				$mail_subject .= $this->language->get('ms_mail_subject_withdraw_request_submitted');
				$mail_text .= sprintf($this->language->get('ms_mail_withdraw_request_submitted'));
				break;
			case self::SMT_WITHDRAW_REQUEST_COMPLETED:
				$mail_subject .= $this->language->get('ms_mail_subject_withdraw_request_completed');
				$mail_text .= sprintf($this->language->get('ms_mail_withdraw_request_completed'));
				break;
			case self::SMT_WITHDRAW_REQUEST_DECLINED:
				$mail_subject .= $this->language->get('ms_mail_subject_withdraw_request_declined');
				$mail_text .= sprintf($this->language->get('ms_mail_withdraw_request_declined'), $this->config->get('config_name'));
				break;
			/*
			case self::SMT_WITHDRAW_PERFORMED:
				$mail_subject .= $this->language->get('ms_mail_subject_withdraw_performed');
				$mail_text .= sprintf($this->language->get('ms_mail_withdraw_performed'), $this->config->get('config_name'));
				break;
			*/
			case self::SMT_TRANSACTION_PERFORMED:
				$mail_subject .= $this->language->get('ms_mail_subject_transaction_performed');
				$mail_text .= sprintf($this->language->get('ms_mail_transaction_performed'), $this->config->get('config_name'));
				break;
				
			case self::SMT_SELLER_CONTACT:
				$mail_subject .= $this->language->get('ms_mail_subject_seller_contact');
				if (!$this->config->get('msconf_hide_emails_in_emails')) {
					$mail_text .= sprintf($this->language->get('ms_mail_seller_contact'), $data['customer_name'], $data['customer_email'], isset($product['name']) ? $product['name'] : '', $data['customer_message']);
				} else {
					$mail_text .= sprintf($this->language->get('ms_mail_seller_contact_no_mail'), $data['customer_name'], isset($product['name']) ? $product['name'] : '', $data['customer_message']);
				}
				break;
				

				case self::SMT_MARK_SHIPPED:
					$seller = $this->MsLoader->MsSeller->getSeller($data['seller_id']);
					$order_shipping = $this->MsLoader->MsShipping->getOrderShippingTracking($data['order_id'], $data['seller_id']);
					$mail_subject .= $this->language->get('ms_mail_subject_mark_shipped');
					$mail_text .= sprintf($this->language->get('ms_mail_mark_shipped'), $data['order_id'], $seller['ms.nickname']);
					if (isset($order_shipping['tracking_number'])) {
						$mail_text .= sprintf($this->language->get('ms_mail_tracking_number'), $order_shipping['tracking_number']);
					}
					if (isset($order_shipping['comment'])) {
						$mail_text .= sprintf($this->language->get('ms_mail_tracking_info'), $order_shipping['comment']);
					}
					break;
			
				case self::SMT_TRACKING_INFORMATION:
					$seller = $this->MsLoader->MsSeller->getSeller($data['seller_id']);
					$order_shipping = $this->MsLoader->MsShipping->getOrderShippingTracking($data['order_id'], $data['seller_id']);
					$mail_subject .= $this->language->get('ms_mail_subject_tracking_information');
					$mail_text .= sprintf($this->language->get('ms_mail_tracking_information'), $data['order_id'], $seller['ms.nickname']);
					if (isset($order_shipping['tracking_number'])) {
						$mail_text .= sprintf($this->language->get('ms_mail_tracking_number'), $order_shipping['tracking_number']);
					}
					if (isset($order_shipping['comment'])) {
						$mail_text .= sprintf($this->language->get('ms_mail_tracking_info'), $order_shipping['comment']);
					}
					break;
			
			case self::SMT_PRIVATE_MESSAGE:
				$mail_subject .= $this->language->get('ms_mail_subject_private_message');
				$mail_text .= sprintf($this->language->get('ms_mail_private_message'), $data['customer_name'], $data['title'], $data['customer_message']);
				break;

			case self::CMT_ORDER_UPDATED:
				$mail_subject .= sprintf($this->language->get('ms_mail_subject_order_updated'), $order_info['order_id'], $seller['ms.nickname']);

				// get products
				$order_products = $this->MsLoader->MsOrderData->getOrderProducts(array('order_id' => $data['order_id'], 'seller_id' => $data['seller_id']));

				$shipping_type = $this->MsLoader->MsShipping->getOrderShippingType($data['order_id'], $data['seller_id']);
				$total_shipping_cost = 0.0;
				if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
					$seller_shipping = $this->MsLoader->MsShipping->getOrderSellerShipping($data['order_id'], $data['seller_id']);
					$total_shipping_cost = $seller_shipping['shipping_cost'];
				}
				$anything_shippable = false;
			
				$products = '';
				foreach ($order_products as $p) {
					if ($p['quantity'] > 1) $products .= "{$p['quantity']} x ";
					$products .= "{$p['name']} \n";
				}

				$mail_text .= sprintf($this->language->get('ms_mail_order_updated'), $this->config->get('config_name'), $seller['ms.nickname'], $order_info['order_id'], $products, $data['status'], $data['comment']);
				break;

			case self::SMT_SELLER_VOTE:
				$mail_subject .= $this->language->get('ms_mail_subject_seller_vote');
				$mail_text .= sprintf($this->language->get('ms_mail_seller_vote_message'), $data['customer_name'], $data['title'], $data['customer_message']);
				break;
			
			case self::SMT_REMIND_LISTING:
				$mail_subject .= $this->language->get('ms_mail_subject_remind_listing');
				$mail_text .= sprintf($this->language->get('ms_mail_seller_remind_listing'), $product['name']);
				break;
				
			// Admin
			case self::AMT_PRODUCT_CREATED:
				$mail_subject .= $this->language->get('ms_mail_admin_subject_product_created');
				$mail_text .= sprintf($this->language->get('ms_mail_admin_product_created'), $product['name'], $this->config->get('config_name'));
				break;
			
			case self::AMT_SELLER_ACCOUNT_CREATED:
				$mail_subject .= $this->language->get('ms_mail_admin_subject_seller_account_created');
				$mail_text .= sprintf($this->language->get('ms_mail_admin_seller_account_created'), $this->config->get('config_name'), $data['seller_name'], $data['customer_name'], $data['customer_email']);
				break;
			case self::AMT_SELLER_ACCOUNT_AWAITING_MODERATION:
				$mail_subject .= $this->language->get('ms_mail_admin_subject_seller_account_awaiting_moderation');
				$mail_text .= sprintf($this->language->get('ms_mail_admin_seller_account_awaiting_moderation'), $this->config->get('config_name'), $data['seller_name'], $data['customer_name'], $data['customer_email']);
				break;
				
			case self::AMT_NEW_PRODUCT_AWAITING_MODERATION:
				$mail_subject .= $this->language->get('ms_mail_admin_subject_new_product_awaiting_moderation');
				$mail_text .= sprintf($this->language->get('ms_mail_admin_new_product_awaiting_moderation'), $product['name'], $this->config->get('config_name'));
				break;

			case self::AMT_EDIT_PRODUCT_AWAITING_MODERATION:
				$mail_subject .= $this->language->get('ms_mail_admin_subject_edit_product_awaiting_moderation');
				$mail_text .= sprintf($this->language->get('ms_mail_admin_edit_product_awaiting_moderation'), $product['name'], $this->config->get('config_name'));
				break;
			
			case self::AMT_WITHDRAW_REQUEST_SUBMITTED:
				$mail_subject .= $this->language->get('ms_mail_admin_subject_withdraw_request_submitted');
				$mail_text .= sprintf($this->language->get('ms_mail_admin_withdraw_request_submitted'));
				break;

			default:
				break;
		}

		if (isset($data['message']) && !empty($data['message'])) {
			$mail_text .= sprintf($this->language->get('ms_mail_message'), $data['message']);			
		}

		$mail_text .= sprintf($this->language->get('ms_mail_ending'), $this->config->get('config_name'));

		$mail->setSubject($mail_subject);
		$mail->setText($mail_text);
		$mail->send();
	}
}
?>
