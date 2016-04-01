<?php
class ControllerModuleMsShippingEstimate extends Controller {
	private $data = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->data = array_merge($this->data, $this->load->language('multiseller/multiseller_physical'));
	}

	public function getRates() {
		if (!$this->config->get('msship_product_shipping_cost_estimation')) return;
		
		$product_id = $this->request->get['product_id'];
		$seller_id = $this->MsLoader->MsProduct->getSellerId($product_id);
		
		$geo_zone_id = $this->request->get['geo_zone_id'];
		
		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		$shipping_type = $this->MsLoader->MsShipping->getSellerShippingType($seller_id);
		
		$product_weight = $product_info['weight'];
		$product_weight_class = $product_info['weight_class_id'];
		
		$product_quantity = $this->request->get['quantity'];;
		
		if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) {
			$shipping_methods_list = $this->MsLoader->MsShipping->getProductShippingMethods($product_id);
		} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
			$shipping_methods_list = $this->MsLoader->MsShipping->getSellerShippingMethods($seller_id);
		}
		
		$shipping_methods = array();
		if (!empty($shipping_methods_list) && isset($shipping_methods_list)) {
			foreach ($shipping_methods_list as $shipping_method) {
				// Check if user is logged in and get an address (?)
				if ($shipping_method['geo_zone_id'] == $geo_zone_id) {
					$skip = false;
					
					$cost_per_product_weight_class_unit = 0.0;
					if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
						$cost_per_product_weight_class_unit = ceil($shipping_method['cost_per_unit'] / $this->weight->convert($shipping_method['weight_step'], $shipping_method['weight_class_id'], $product_weight_class));
					}
					
					// Check for already existing shipping methods and skip if more general or replace if more specific geozone is found
					foreach ($shipping_methods as $index => $shipping_method_check) {
						if ($shipping_method_check['shipping_method_id'] == $shipping_method['shipping_method_id']) {
							if ($shipping_method['geo_zone_id'] == 0) {
								$skip = true;
							}
							else {
								if ( ( ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) && ($shipping_method_check['cost'] > $shipping_method['cost']) ) || ( ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) && ($shipping_method_check['cost_per_product_weight_class_unit'] > $cost_per_product_weight_class_unit) ) ) {
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
						if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) {
							$cost = $this->currency->format($shipping_method['cost'] * $product_quantity, $this->currency->getCode(), 1);
						} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) {
							$weight_units = ceil($product_weight / $this->weight->convert($shipping_method['weight_step'], $shipping_method['weight_class_id'], $product_weight_class));
							$cost = $this->currency->format($product_quantity * $weight_units * $shipping_method['cost_per_unit'], $this->currency->getCode(), 1);
						}
						
						$shipping_methods[] = array(
							'shipping_method_id'					=> $shipping_method['shipping_method_id'],
							'method_descriptions'					=> $shipping_method_descriptions,
							'comment'								=> $shipping_method['comment'],
							'cost'									=> $cost,
							'cost_per_product_weight_class_unit'	=> $cost_per_product_weight_class_unit,
							'geo_zone_id'							=> $shipping_method['geo_zone_id']
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
				$language_id = $language['language_id'];
				break;
			}
		}
		$this->data['language_id'] = $language_id;
		$this->data['shipping_type'] = $shipping_type;
		$this->data['shipping_methods'] = $shipping_methods;
		
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('shipping-estimate-table');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}
}
?>