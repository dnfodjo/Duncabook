<?php
class ControllerCheckoutMultisellerShippingMethod extends Controller {
	private $data = array();

	function array_sort($array, $on, $order=SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}

  	public function index() {
		$this->data = array_merge($this->data, $this->load->language('multiseller/multiseller_physical'));
		$this->load->model('account/address');

		if (!isset($this->session->data['shipping_address'])) {
			$this->request->redirect($this->url->link('checkout/checkout', '', 'SSL'));
		} else {
			$shipping_address = $this->session->data['shipping_address'];
		}

		// >>> THIS (DOWN HERE) >>>
		$this->data['error_warning'] = "";
		// List all shipping methods for each product (or each seller for combinable)
		$this->data['products'] = array();
		$sellers = array();
		$checkout_totals = (float)0.0;
		$weight_class = 1;

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$filename = $this->encryption->decrypt($option['value']);
					$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}

			$seller_id = $this->MsLoader->MsProduct->getSellerId($product['product_id']);
			$shipping_type = (int)$this->MsLoader->MsShipping->getSellerShippingType($seller_id);

			$shipping_methods = array();
			$shipping_method_list = array();

			if ($seller_id == 0) {
				// Shop Shipping Methods
				$quote_data = array();
				$this->load->model('extension/extension');
				$results = $this->model_extension_extension->getExtensions('shipping');
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('shipping/' . $result['code']);
						$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);
						if ($quote) {
							$quote_data[$result['code']] = array(
								'title'      => $quote['title'],
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}
					}
				}
				$sort_order = array();
				foreach ($quote_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
				array_multisort($sort_order, SORT_ASC, $quote_data);
				$this->session->data['shop_shipping_methods'] = $quote_data;
			} else if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) { // Fixed shipping type
				$shipping_method_list = $this->MsLoader->MsShipping->getProductShippingMethods($product['product_id']);
			} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) { // Combinable shipping type
				if ($product['shipping']) {
					$shipping_method_list = $this->MsLoader->MsShipping->getSellerShippingMethods($seller_id);
				}
				$combine_sellers[] = $seller_id;
			}
			$sellers[] = $seller_id;

			if ($product['shipping']) {
				foreach ($shipping_method_list as $shipping_method) {
					if ($this->MsLoader->MsShipping->getQuote($shipping_address, $shipping_method['geo_zone_id']) || ($shipping_method['geo_zone_id'] == 0)) {
						$skip = false;

						$cost_per_default_weight_class_unit = 0.0;
						if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
							$cost_per_default_weight_class_unit = ceil($shipping_method['cost_per_unit'] / $this->weight->convert($shipping_method['weight_step'], $shipping_method['weight_class_id'], $weight_class));
						}

						// Check for already existing shipping methods and skip if more general or replace if more specific geozone is found
						foreach ($shipping_methods as $index => $shipping_method_check) {
							if ($shipping_method_check['shipping_method_id'] == $shipping_method['shipping_method_id']) {
								if ($shipping_method['geo_zone_id'] == 0) {
									$skip = true;
								}
								else {
									if ( ( ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) && ($shipping_method_check['cost'] > $shipping_method['cost']) ) || ( ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) && ($shipping_method_check['cost_per_default_weight_class_unit'] > $cost_per_default_weight_class_unit) ) ) { // TEST THIS THOROUGHLY
										// Replace this with more specific location (or less cost)
										unset($shipping_methods[$index]);
									}
									else {
										$skip = true;
									}
								}
							}
						}

						if (!$skip) {
							$shipping_method_descriptions = $this->MsLoader->MsShippingMethod->getShippingMethodDescriptions($shipping_method['shipping_method_id']);

							// Calculate the cost
							if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) { // Fixed shipping type
								//$cost = $this->currency->format($this->currency->convert($shipping_method['cost'] * $product['quantity'], $shipping_method['currency_code'], $this->currency->getCode()), $this->currency->getCode(), 1);
								$cost = $this->currency->format($shipping_method['cost'] * $product['quantity']);
								//$cost_unformatted = $this->currency->convert($shipping_method['cost'] * $product['quantity'], $shipping_method['currency_code'], $this->currency->getCode());
								$cost_unformatted = $shipping_method['cost'] * $product['quantity'];
								$cost_per_unit = 0;
								$weight_step = 0;
								$weight_class_id = 1;
							} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) { // Combinable shipping type
								// Preliminary proportional calculation of shipping cost per product (not per total weight with steps)
								//$cost = $this->currency->format($this->currency->convert($product['weight']/$this->weight->convert($shipping_method['weight_step'], $shipping_method['weight_class_id'], $product['weight_class_id']) * $shipping_method['cost_per_unit'], $shipping_method['currency_code'], $this->currency->getCode()), $this->currency->getCode(), 1);
								$cost = $this->currency->format($product['weight']/$this->weight->convert($shipping_method['weight_step'], $shipping_method['weight_class_id'], $product['weight_class_id']) * $shipping_method['cost_per_unit']);
								//$cost_unformatted = $this->currency->convert($product['weight']/$this->weight->convert($shipping_method['weight_step'], $shipping_method['weight_class_id'], $product['weight_class_id']) * $shipping_method['cost_per_unit'], $shipping_method['currency_code'], $this->currency->getCode());
								$cost_unformatted = $product['weight']/$this->weight->convert($shipping_method['weight_step'], $shipping_method['weight_class_id'], $product['weight_class_id']) * $shipping_method['cost_per_unit'];

								$cost_per_unit = $shipping_method['cost_per_unit'];
								$weight_step = $shipping_method['weight_step'];
								$weight_class_id = $shipping_method['weight_class_id'];
							}

							$shipping_methods[] = array(
								'shipping_method_id'					=> $shipping_method['shipping_method_id'],
								'method_descriptions'					=> $shipping_method_descriptions,
								'comment'								=> $shipping_method['comment'],
								'cost'									=> $cost,
								'cost_unformatted'						=> $cost_unformatted,
								'cost_per_unit'							=> $cost_per_unit,
								'cost_per_default_weight_class_unit'	=> $cost_per_default_weight_class_unit,
								'weight_step'							=> $weight_step,
								'weight_class_id'						=> $weight_class_id,
								'geo_zone_id'							=> $shipping_method['geo_zone_id'],
								//'currency_id'							=> $shipping_method['currency_id'],
								//'currency_code'						=> $shipping_method['currency_code'],
								'error'									=> ""
							);
						}
					}
				}

			}

			// Get current language ID
			$language_code = $this->session->data['language'];
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				if ($language['code'] == $language_code) {
					$this->data['language_id'] = $language['language_id'];
				}
			}

			if ($seller_id == 0) {
				$seller_name = $this->language->get('shop');;
				$seller_href = $this->url->link('common/home');
			} else {
				//$seller_name = $this->MsLoader->MsSeller->getSellerName($seller_id);
				$seller = $this->MsLoader->MsSeller->getSeller($seller_id);
				$seller_name = $seller['ms.nickname'];
				$seller_href = $this->url->link('seller/catalog-seller/profile', 'seller_id=' . $seller_id);
			}

			$this->data['products'][] = array(
				'product_id'		=> $product['product_id'],
				'shipping_type'		=> $shipping_type,
				'seller_id'			=> $seller_id,
				'seller_name'		=> $seller_name,
				'seller_href'		=> $seller_href,
				'shipping_methods'	=> $shipping_methods,
				'shippable'			=> $product['shipping'],
				'shipping_cost'		=> (isset($shipping_methods[0]) ? $this->currency->convert($shipping_methods[0]['cost_unformatted'],'',$this->currency->getCode()) : "0.0"),
				'name'				=> $product['name'],
				'model'				=> $product['model'],
				'option'			=> $option_data,
				'quantity'			=> $product['quantity'],
				'weight'			=> $product['weight'],
				'weight_class_id'	=> $product['weight_class_id'],
				'subtract'			=> $product['subtract'],
				'price'				=> $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
				'total'				=> $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
				'href'				=> $this->url->link('product/product', 'product_id=' . $product['product_id'])
			);

			//if ($shipping_methods || !$product['shipping']) {
				$checkout_totals += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
				if ($product['shipping'] && $shipping_type == MsShipping::SHIPPING_TYPE_FIXED && $shipping_methods) {
					$checkout_totals += $shipping_methods[0]['cost_unformatted'];
				}
			//}
		}

		if (!empty($combine_sellers)) {
			$combine_sellers = array_unique($combine_sellers);
		}
		if (!empty($sellers)) {
			$this->data['sellers'] = array_unique($sellers);
		}
		$this->array_sort($this->data['products'], 'seller_id', SORT_ASC);

		$this->data['seller_shipping'] = array();

		// Go through all products again and sum up the weights and shipping costs for each seller with combinable shipping type
		if (!empty($combine_sellers)) {
			foreach ($combine_sellers as $unique_seller_id) {
				// Calculate total weight
				$total_seller_weight = 0.0;
				$total_seller_products = 0;
				foreach ($this->data['products'] as $product) {
					if ($product['seller_id'] == $unique_seller_id) {
						$total_seller_products += 1;
						if ($product['shippable']) {
							$total_seller_weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $weight_class);
						}
					}
				}

				// Recalculate the cost for all the shipping methods considering the total weight calculated before
				foreach ($this->data['products'] as $product) {
					if ( ($product['shippable']) && ($product['seller_id'] == $unique_seller_id) ) {
						foreach ($product['shipping_methods'] as $seller_shipping_method) {
							$total_seller_weight_units = ceil($total_seller_weight / $this->weight->convert($seller_shipping_method['weight_step'], $seller_shipping_method['weight_class_id'], $weight_class));

							//$cost = $this->currency->convert($total_seller_weight_units * $seller_shipping_method['cost_per_unit'], $seller_shipping_method['currency_code'], $this->currency->getCode());
							$cost = $total_seller_weight_units * $seller_shipping_method['cost_per_unit'];

							$seller_shipping_method['cost'] = $this->currency->format($cost / $total_seller_products);
							$seller_shipping_method['cost_unformatted'] = $cost / $total_seller_products;

							$seller_shipping_methods[] = array(
								'shipping_method_id'					=> $seller_shipping_method['shipping_method_id'],
								'method_descriptions'					=> $seller_shipping_method['method_descriptions'],
								'cost'									=> $this->currency->format($cost),
								'cost_unformatted'						=> $cost,
								'geo_zone_id'							=> $seller_shipping_method['geo_zone_id'],
								//'currency_id'							=> $seller_shipping_method['currency_id'],
								//'currency_code'							=> $seller_shipping_method['currency_code'],
								'comment'								=> $seller_shipping_method['comment'],
								'error'									=> $seller_shipping_method['error']
							);
						}
						break;
					}
				}

				if ($unique_seller_id == 0) {
					$seller_name = $this->language->get('shop');
					$seller_href = $this->url->link('common/home');
					//$seller_shipping_methods = $this->session->data['shop_shipping_methods'];
				} else {
					//$seller_name = $this->MsLoader->MsSeller->getSellerName($unique_seller_id);
					$seller = $this->MsLoader->MsSeller->getSeller($unique_seller_id);
					$seller_name = $seller['ms.nickname'];
					$seller_href = $this->url->link('seller/catalog-seller/profile', 'seller_id=' . $unique_seller_id);
				}

				$this->data['seller_shipping'][] = array(
					'seller_id'				=> $unique_seller_id,
					'seller_name'			=> $seller_name,
					'seller_href'			=> $seller_href,
					'shipping_cost'			=> (isset($seller_shipping_methods[0]) ? $this->currency->convert($seller_shipping_methods[0]['cost_unformatted'],'',$this->currency->getCode()) : "0.0"),
					'total_seller_weight'	=> $total_seller_weight,
					'weight_class_unit'		=> $this->weight->getUnit($weight_class),
					'shipping_methods'		=> (isset($seller_shipping_methods) ? $seller_shipping_methods : null)
				);

				if (isset($seller_shipping_methods)) {
					$checkout_totals += $seller_shipping_methods[0]['cost_unformatted'];
				}
				unset($seller_shipping_methods);
			}
		}

		$this->data['checkout_totals'] = $this->currency->convert($checkout_totals,'',$this->currency->getCode());
		$this->data['checkout_totals_formatted'] = $this->currency->format($checkout_totals);

		// <<< THIS (UP THERE) <<<
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('checkout/multiseller_shipping_method');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
  	}

	public function jxChangeShippingMethod() {
		$json = array();
		$data = $this->request->post;
		$product_id = $this->request->get['product_id'];

		$json['checkout_totals_unformatted'] = $this->currency->convert($data['checkout_totals'] - $data['shipping_cost_old'][$product_id] + $data['shipping_cost_new'][$product_id][$data['shipping_method'][$product_id]],'',$this->currency->getCode());
		//$json['checkout_totals_unformatted'] = $data['checkout_totals'] - $data['shipping_cost_old[' . $product_id . ']'] + $data['shipping_cost_new[' . $product_id . '][' . $data['shipping_method[' . $product_id . ']'] . ']'];
		$json['checkout_totals'] = $this->currency->format($json['checkout_totals_unformatted']);

		return $this->response->setOutput(json_encode($json));
  	}

	public function jxChangeSellerShippingMethod() {
		$json = array();
		$data = $this->request->post;
		$seller_id = $this->request->get['seller_id'];

		$json['checkout_totals_unformatted'] = $this->currency->convert($data['checkout_totals'] - $data['seller_shipping_cost_old'][$seller_id] + $data['seller_shipping_cost_new'][$seller_id][$data['seller_shipping_method'][$seller_id]],'',$this->currency->getCode());
		$json['checkout_totals'] = $this->currency->format($json['checkout_totals_unformatted']);

		return $this->response->setOutput(json_encode($json));
  	}

	public function save() {
		$this->language->load('checkout/checkout');

		$json = array();

		// Validate whether shipping is required for at least one of the products in the cart. If not the customer should not have reached this page
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate if shipping address has been set.
		if (!isset($this->session->data['shipping_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		$products = $this->cart->getProducts();

		// Remove physical products with no shipping possible
		foreach ($products as $product) {
			if ($product['shipping']) {
				$seller_id = $this->MsLoader->MsProduct->getSellerId($product['product_id']);
				$shipping_type = $this->MsLoader->MsShipping->getSellerShippingType($seller_id);
				// Fixed shipping
				if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) {
					if (!isset( $this->request->post['shipping_method'][$product['product_id']] )) {
						$this->cart->remove($product['key']);
					}
				// Combinable shipping
				} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
					if (!isset( $this->request->post['seller_shipping_method'][$seller_id] )) {
						$this->cart->remove($product['key']);
					}
				// Not defined shipping type (shop products and products of deleted sellers)
				} else if ($shipping_type == MsShipping::SHIPPING_TYPE_NOT_DEFINED) {
					$this->cart->remove($product['key']);
				}
			}
		}

		// Validate whether cart has products and has stock
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate whether minimum quantity requirments are met
		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');
				break;
			}
		}

		// Set the shipping methods
		if (!$json) {

			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$seller_id = $this->MsLoader->MsProduct->getSellerId($product['product_id']);
					$shipping_type = $this->MsLoader->MsShipping->getSellerShippingType($seller_id);
					// Fixed shipping
					if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) {
						$shipping_method_id = $this->request->post['shipping_method'][$product['product_id']];
						$this->session->data['shipping_methods'][$product['product_id']]['shipping_method_id'] = $shipping_method_id;
						$this->session->data['shipping_methods'][$product['product_id']]['cost_unformatted'] = $this->request->post['shipping_cost_new'][$product['product_id']][$shipping_method_id];
						$this->session->data['shipping_methods'][$product['product_id']]['name'] = $this->request->post['shipping_method_name'][$product['product_id']][$shipping_method_id];
					// Combinable shipping
					} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
						$shipping_method_id = $this->request->post['seller_shipping_method'][$seller_id];
						$this->session->data['seller_shipping_methods'][$seller_id]['shipping_method_id'] = $shipping_method_id;
						$this->session->data['seller_shipping_methods'][$seller_id]['cost_unformatted'] = $this->request->post['seller_shipping_cost_new'][$seller_id][$shipping_method_id];
						$this->session->data['seller_shipping_methods'][$seller_id]['name'] = $this->request->post['seller_shipping_method_name'][$seller_id][$shipping_method_id];

						// Calculate the proportional shipping cost for the product with combined shipping
						//$this->session->data['shipping_methods'][$product['product_id']]['cost_unformatted'] = $this->request->post['seller_shipping_cost_new'][$seller_id][$shipping_method_id] * ($product['weight'] / $this->request->post['seller_total_weight'][$seller_id] );
						if ($product['weight']) {
							$this->session->data['shipping_methods'][$product['product_id']]['cost_unformatted'] = $this->request->post['seller_shipping_cost_new'][$seller_id][$shipping_method_id] * ($this->weight->convert($product['weight'], $product['weight_class_id'], 1) / $this->request->post['seller_total_weight'][$seller_id]);
						} else {
							$this->session->data['shipping_methods'][$product['product_id']]['cost_unformatted'] = 0;
						}
					}
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>
