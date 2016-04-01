<?php
class MsShipping extends Model {
	// Shipping Types 
	const SHIPPING_TYPE_FIXED = 0;
	const SHIPPING_TYPE_COMBINABLE = 1;
	const SHIPPING_TYPE_NOT_DEFINED = 10;
	
	// ************************
	// * Seller Shipping Type *
	// ************************
	
	// Get seller shipping type
	public function getSellerShippingType($seller_id) {
		if ($seller_id != 0) {
			$sql = "SELECT mss.shipping_type as 'shipping_type'
					FROM " . DB_PREFIX . "ms_seller mss
					WHERE mss.seller_id = '" . (int)$seller_id . "'";
			
			$res = $this->db->query($sql);
			if ($res->num_rows > 0) {
				return $res->row['shipping_type'];
			}
			else {
				return MsShipping::SHIPPING_TYPE_NOT_DEFINED; // Seller not found
			}
		}
		
		return MsShipping::SHIPPING_TYPE_NOT_DEFINED; // Not a usual seller
	}
	
	// Set seller shipping type
	public function setSellerShippingType($seller_id, $shipping_type) {
		$sql = "UPDATE " . DB_PREFIX . "ms_seller 
				SET shipping_type = '" . (int)$shipping_type . "'
				WHERE seller_id = '" . (int)$seller_id . "'";
		
		$res = $this->db->query($sql);
	}
	
	// *********************************************************
	// * Seller Shipping Methods (for combined shipping rates) *
	// *********************************************************
	
	// Get a seller shipping method
	public function getSellerShippingMethod($seller_shipping_method_id, $data = array()) {
		$sql = "SELECT *
				FROM " . DB_PREFIX . "ms_seller_shipping_method msssm
				WHERE msssm.seller_shipping_method_id = '" . (int)$seller_shipping_method_id . "'";
		
		$res = $this->db->query($sql);
		
		$result = $res->row;
		$result['cost_per_unit'] = $this->currency->convert($result['cost_per_unit'], $this->config->get('config_currency'), $this->currency->getCode());
		
		return $result;
	}

	// Get all seller shipping methods for a seller
	public function getSellerShippingMethods($seller_id, $data = array()) {
		$sql = "SELECT * 
				FROM " . DB_PREFIX . "ms_seller_shipping_method msssm 
				WHERE msssm.seller_id = '" . (int)$seller_id . "'";

		$query = $this->db->query($sql);
		
		$result = array();
		foreach ($query->rows as $shipping_method) {
			$shipping_method['cost_per_unit'] = $this->currency->convert($shipping_method['cost_per_unit'], $this->config->get('config_currency'), $this->currency->getCode());
			$result[] = $shipping_method;
		}
		
		return $result;
	}
	
	// Create a shipping method for a seller
	public function createSellerShippingMethod($seller_id, $data) {
		$data['cost_per_unit'] = $this->currency->convert($this->MsLoader->MsHelper->uniformDecimalPoint($data['cost_per_unit']), $this->currency->getCode(), $this->config->get('config_currency'));
		$sql = "INSERT INTO " . DB_PREFIX . "ms_seller_shipping_method 
				SET seller_id = '" . (int)$seller_id . "',
					shipping_method_id = '" . (int)($data['shipping_method_id']) . "',
					comment = '" . $this->db->escape($data['comment']) . "',
					geo_zone_id = '" . (int)($data['geo_zone_id']) . "',
					weight_class_id = '" . (int)($data['weight_class_id']) . "',
					weight_step = '" . (float)$this->MsLoader->MsHelper->uniformDecimalPoint($data['weight_step']) . "',
					cost_per_unit = '" . (float)($data['cost_per_unit']) . "'";
		//currency_id = '" . (int)($data['currency_id']) . "',
		//currency_code = '" . $this->db->escape($data['currency_code']) . "',
		
		$this->db->query($sql);
		$seller_shipping_method_id = $this->db->getLastId();
		
		return $seller_shipping_method_id;
	}

	// Edit a shipping method for a seller
	public function editSellerShippingMethod($seller_shipping_method_id, $data) {
		$data['cost_per_unit'] = $this->currency->convert($this->MsLoader->MsHelper->uniformDecimalPoint($data['cost_per_unit']), $this->currency->getCode(), $this->config->get('config_currency'));
		$sql = "UPDATE " . DB_PREFIX . "ms_seller_shipping_method 
				SET shipping_method_id = '" . (int)($data['shipping_method_id']) . "',
					comment = '" . $this->db->escape($data['comment']) . "',
					geo_zone_id = '" . (int)($data['geo_zone_id']) . "',
					weight_class_id = '" . (int)($data['weight_class_id']) . "',
					weight_step = '" . (float)$this->MsLoader->MsHelper->uniformDecimalPoint($data['weight_step']) . "',
					cost_per_unit = '" . (float)($data['cost_per_unit']) . "'
				WHERE seller_shipping_method_id = '" . (int)$seller_shipping_method_id . "'";
		//currency_id = '" . (int)($data['currency_id']) . "',
		//currency_code = '" . $this->db->escape($data['currency_code']) . "',
		
		$this->db->query($sql);
	}
	
	// Delete a shipping method from a seller
	public function deleteSellerShippingMethod($seller_shipping_method_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ms_seller_shipping_method 
							WHERE seller_shipping_method_id = '" . (int)$seller_shipping_method_id . "'");
	}
	
	// Check whether seller shipping methods for the concrete shipping method exist
	public function sellerShippingMethodsExist($shipping_method_id) {
		$res = $this->db->query("SELECT * FROM " . DB_PREFIX . "ms_seller_shipping_method 
									WHERE shipping_method_id = '" . (int)$shipping_method_id . "'");
		
		return $res->num_rows;
	}
	
	// *******************************************************
	// * Product Shipping Methods (for fixed shipping rates) *
	// *******************************************************

	// Get a product shipping method
	public function getProductShippingMethod($product_shipping_method_id, $data = array()) {
		$sql = "SELECT *
				FROM " . DB_PREFIX . "ms_product_shipping_method mspsm
				WHERE mspsm.product_shipping_method_id = '" . (int)$product_shipping_method_id . "'";
		
		$res = $this->db->query($sql);
		
		$result = $res->row;
		$result['cost'] = $this->currency->convert($result['cost'], $this->config->get('config_currency'), $this->currency->getCode());
		
		return $result;
	}

	// Get all product shipping methods for a product
	public function getProductShippingMethods($product_id, $data = array()) {
		$sql = "SELECT * 
				FROM " . DB_PREFIX . "ms_product_shipping_method mspsm 
				WHERE mspsm.product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);
		
		$result = array();
		foreach ($query->rows as $shipping_method) {
			$shipping_method['cost'] = $this->currency->convert($shipping_method['cost'], $this->config->get('config_currency'), $this->currency->getCode());
			$result[] = $shipping_method;
		}
		
		return $result;
	}
	
	// Create a shipping method for a product
	public function createProductShippingMethod($product_id, $data) {
		$data['cost'] = $this->currency->convert($this->MsLoader->MsHelper->uniformDecimalPoint($data['cost']), $this->currency->getCode(), $this->config->get('config_currency'));
		$sql = "INSERT INTO " . DB_PREFIX . "ms_product_shipping_method 
				SET product_id = '" . (int)$product_id . "',
					shipping_method_id = '" . (int)($data['shipping_method_id']) . "',
					comment = '" . $this->db->escape($data['comment']) . "',
					geo_zone_id = '" . (int)($data['geo_zone_id']) . "',
					cost = '" . (float)($data['cost']) . "'";
		//currency_id = '" . (int)($data['currency_id']) . "',
		//currency_code = '" . $this->db->escape($data['currency_code']) . "',
		
		$this->db->query($sql);
		$product_shipping_method_id = $this->db->getLastId();
		
		return $product_shipping_method_id;
	}

	// Edit a shipping method for a product
	public function editProductShippingMethod($product_shipping_method_id, $data) {
		$data['cost'] = $this->currency->convert($this->MsLoader->MsHelper->uniformDecimalPoint($data['cost']), $this->currency->getCode(), $this->config->get('config_currency'));
		$sql = "UPDATE " . DB_PREFIX . "ms_product_shipping_method 
				SET shipping_method_id = '" . (int)($data['shipping_method_id']) . "',
					comment = '" . $this->db->escape($data['comment']) . "',
					geo_zone_id = '" . (int)($data['geo_zone_id']) . "',
					cost = '" . (float)($data['cost']) . "'
				WHERE product_shipping_method_id = '" . (int)$product_shipping_method_id . "'";
		//currency_id = '" . (int)($data['currency_id']) . "',
		//currency_code = '" . $this->db->escape($data['currency_code']) . "',
		
		$this->db->query($sql);
	}
	
	// Delete a shipping method from a product
	public function deleteProductShippingMethod($product_shipping_method_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ms_product_shipping_method 
							WHERE product_shipping_method_id = '" . (int)$product_shipping_method_id . "'");
	}
	
	// Check whether product shipping methods for the concrete shipping method exist
	public function productShippingMethodsExist($shipping_method_id) {
		$res = $this->db->query("SELECT * FROM " . DB_PREFIX . "ms_product_shipping_method 
									WHERE shipping_method_id = '" . (int)$shipping_method_id . "'");
		
		return $res->num_rows;
	}
	
	// *********************************
	// * Seller Shipping Method Ranges *
	// *********************************
	
	// Get a shipping method range
	public function getShippingMethodRange($range_id, $data = array()) {
		$sql = "SELECT *
				FROM " . DB_PREFIX . "ms_shipping_method_range mssmr
				WHERE mssmr.range_id = '" . (int)$range_id . "'";
		
		$res = $this->db->query($sql);
		
		return $res->row;
	}

	// Get all ranges for the shipping method
	public function getShippingMethodRanges($seller_shipping_method_id, $data = array()) {
		$sql = "SELECT * 
				FROM " . DB_PREFIX . "ms_shipping_method_range mssmr 
				WHERE mssmr.seller_shipping_method_id = '" . (int)$seller_shipping_method_id . "'";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	// Create a range for the shipping method
	public function createShippingMethodRange($seller_shipping_method_id, $data) {
		$sql = "INSERT INTO " . DB_PREFIX . "ms_shipping_method_range 
				SET seller_shipping_method_id = '" . (int)$seller_shipping_method_id . "',
					from = '" . (float)($data['from']) . "',
					to = '" . (float)($data['to']) . "',
					cost = '" . (float)($data['cost']) . "'";
		$this->db->query($sql);
		$range_id = $this->db->getLastId();
		
		return $range_id;
	}

	// Edit a range for the shipping method
	public function editSellerShippingMethodRange($range_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "ms_shipping_method_range 
				SET seller_shipping_method_id = '" . (int)$seller_shipping_method_id . "',
					from = '" . (float)($data['from']) . "',
					to = '" . (float)($data['to']) . "',
					cost = '" . (float)($data['cost']) . "'
				WHERE range_id = '" . (int)$range_id . "'";
		
		$this->db->query($sql);
	}
	
	// Delete a range from the shipping method
	public function deleteSellerShippingMethodRange($range_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ms_shipping_method_range 
							WHERE range_id = '" . (int)$range_id . "'");
	}
	
	// ****************************
	// * Seller shipping settings *
	// ****************************
	
	// Save seller shipping settings
	public function saveSellerShippingSettings($data) {
		// Save the shipping type
		$this->setSellerShippingType($data['seller_id'], $data['shipping_type']);
		
		// Save seller shipping methods
		if (isset($data['ms_shipping_methods'])) {
			//$this->load->model('localisation/currency');
			//$currencies = $this->model_localisation_currency->getCurrencies();
			foreach ($data['ms_shipping_methods'] as $ms_shipping_method) {
				/*foreach ($currencies as $currency) {
					if ($currency['currency_id'] == $ms_shipping_method['currency_id']) {
						$ms_shipping_method['currency_code'] = $currency['code'];
					}
				}*/
				$this->MsLoader->MsShipping->createSellerShippingMethod($data['seller_id'], $ms_shipping_method);
			}
		}
	}
	
	// Edit seller shipping settings
	public function editSellerShippingSettings($data) {
		$seller_id = $data['seller_id'];
		$this->db->query("DELETE FROM " . DB_PREFIX . "ms_seller_shipping_method WHERE seller_id = '" . (int)$seller_id . "'");
		
		// Save the shipping type
		$this->setSellerShippingType($seller_id, $data['shipping_type']);
		
		// Save seller shipping methods
		if (isset($data['ms_shipping_methods'])) {
			//$this->load->model('localisation/currency');
			//$currencies = $this->model_localisation_currency->getCurrencies();
			foreach ($data['ms_shipping_methods'] as $ms_shipping_method) {
				/*foreach ($currencies as $currency) {
					if ($currency['currency_id'] == $ms_shipping_method['currency_id']) {
						$ms_shipping_method['currency_code'] = $currency['code'];
					}
				}*/
				$this->MsLoader->MsShipping->createSellerShippingMethod($seller_id, $ms_shipping_method);
			}
		}
	}
	
	// *****************
	// * Shipping Data *
	// *****************
	
	// Get data essential for shipping from product table
	public function getProductShippingData($product_id) {
		$sql = "SELECT 	p.product_id as 'product_id',
						p.length as 'length',
						p.width as 'width',
						p.height as 'height',
						p.weight as 'weight',
						p.weight_class_id as 'weight_class_id',
						p.length_class_id as 'length_class_id'
				FROM `" . DB_PREFIX . "product` p
				WHERE p.product_id = " . (int)$product_id;
				
		$res = $this->db->query($sql);
		
		return $res->row;
	}
	
	// Update data essential for shipping from product table
	public function updateProductShippingData($product_id, $data) {
		$sql = "UPDATE `" . DB_PREFIX . "product` 
				SET length = '" . (float)$this->MsLoader->MsHelper->uniformDecimalPoint($data['length']) . "',
					width = '" . (float)$this->MsLoader->MsHelper->uniformDecimalPoint($data['width']) . "',
					height = '" . (float)$this->MsLoader->MsHelper->uniformDecimalPoint($data['height']) . "',
					weight = '" . (float)$this->MsLoader->MsHelper->uniformDecimalPoint($data['weight']) . "',
					weight_class_id = '" . (int)$data['weight_class_id'] . "',
					length_class_id = '" . (int)$data['length_class_id'] . "'
				WHERE product_id = '" . (int)$product_id . "'";
		
		$this->db->query($sql);
	}
	
	// ********************************************
	// * Order shipping (shipping type for order) *
	// ********************************************
	
	// Get order shipping type
	public function getOrderShippingType($order_id, $seller_id) {
		$sql = "SELECT 	msos.shipping_type as 'shipping_type'
				FROM `" . DB_PREFIX . "ms_order_shipping` msos
				WHERE msos.order_id = " . (int)$order_id . " AND
					  msos.seller_id = " . (int)$seller_id;
				
		$res = $this->db->query($sql);
		//return $res->row;
		
		if ($res->num_rows > 0) {
			return $res->row['shipping_type'];
		}
		else {
			return MsShipping::SHIPPING_TYPE_NOT_DEFINED; // Order shipping not found
		}
	}
	
	// Create order shipping record
	public function createOrderShipping($data) {
		$sql = "INSERT INTO " . DB_PREFIX . "ms_order_shipping 
				SET shipping_type = '" . (int)$data['shipping_type'] . "',
					order_id = '" . (int)($data['order_id']) . "',
					seller_id = '" . (int)($data['seller_id']) . "'";
		
		$this->db->query($sql);
		$order_shipping_id = $this->db->getLastId();
		
		return $order_shipping_id;
	}
	
	// ************************************************************************
	// * Order product shippable (shippable parameter for each order product) *
	// ************************************************************************
	
	// Get order product shippable
	public function getOrderProductShippable($order_id, $product_id) {
		$sql = "SELECT 	msops.shippable as 'shippable'
				FROM `" . DB_PREFIX . "ms_order_product_shippable` msops
				WHERE msops.order_id = " . (int)$order_id . " AND
					  msops.product_id = " . (int)$product_id;
		
		$res = $this->db->query($sql);		
		if ($res->num_rows == 0) {
			return 0; // No record
		}
		return $res->row['shippable'];
	}
	
	// Create order product shippable record
	public function createOrderProductShippable($data) {
		$sql = "INSERT INTO " . DB_PREFIX . "ms_order_product_shippable 
				SET shippable = '" . (int)$data['shippable'] . "',
					order_id = '" . (int)($data['order_id']) . "',
					product_id = '" . (int)($data['product_id']) . "'";
		
		$this->db->query($sql);
		$order_product_shippable_id = $this->db->getLastId();
		
		return $order_product_shippable_id;
	}
	
	// ******************************************
	// * Get Product/Seller shipping order data *
	// ******************************************
	
	// Get shipping data from order product shipping
	public function getOrderProductShipping($order_id, $product_id, $convert = 1) {
		$sql = "SELECT 	msops.shipping_method_name as 'shipping_method_name',
						msops.shipping_cost as 'shipping_cost'
				FROM `" . DB_PREFIX . "ms_order_product_shipping` msops
				WHERE msops.order_id = " . (int)$order_id . " AND
					  msops.product_id = " . (int)$product_id;
				
		$res = $this->db->query($sql);
		
		$result = $res->row;
		if ($convert) {
			$result['shipping_cost'] = $this->currency->convert($result['shipping_cost'], $this->config->get('config_currency'), $this->currency->getCode());
		}
		
		return $result;
	}
	
	// Get shipping data from order seller shipping
	public function getOrderSellerShipping($order_id, $seller_id, $convert = 1) {
		$sql = "SELECT 	msoss.shipping_method_name as 'shipping_method_name',
						msoss.shipping_cost as 'shipping_cost'
				FROM `" . DB_PREFIX . "ms_order_seller_shipping` msoss
				WHERE msoss.order_id = " . (int)$order_id . " AND
					  msoss.seller_id = " . (int)$seller_id;
				
		$res = $this->db->query($sql);
		
		$result = $res->row;
		if ($convert) {
			$result['shipping_cost'] = $this->currency->convert($result['shipping_cost'], $this->config->get('config_currency'), $this->currency->getCode());
		}
		
		return $result;
	}
	
	// ***************************
	// * Order shipping tracking *
	// ***************************
	
	// Create order shipping tracking record
	public function createOrderShippingTracking($data) {
		$sql = "INSERT INTO " . DB_PREFIX . "ms_order_shipping_tracking 
				SET shipped = '" . (int)$data['shipped'] . "',
					tracking_number = '" . $this->db->escape($data['tracking_number']) . "',
					comment = '" . $this->db->escape($data['comment']) . "',
					order_id = '" . (int)($data['order_id']) . "',
					seller_id = '" . (int)($data['seller_id']) . "'";
		
		$this->db->query($sql);
		$order_shipping_tracking_id = $this->db->getLastId();
		
		return $order_shipping_tracking_id;
	}
	
	// Edit order shipping tracking record
	public function editOrderShippingTracking($data, $order_id, $seller_id) {
		$sql = "UPDATE " . DB_PREFIX . "ms_order_shipping_tracking 
				SET shipped = '" . (int)$data['shipped'] . "',
					tracking_number = '" . $this->db->escape($data['tracking_number']) . "',
					comment = '" . $this->db->escape($data['comment']) . "'
				WHERE order_id = " . (int)$order_id . " AND 
					  seller_id = " . (int)$seller_id;
		
		$this->db->query($sql);
	}
	
	// Get shipping data from order shipping tracking
	public function getOrderShippingTracking($order_id, $seller_id) {
		$sql = "SELECT 	msost.shipped as 'shipped',
						msost.tracking_number as 'tracking_number',
						msost.comment as 'comment'
				FROM `" . DB_PREFIX . "ms_order_shipping_tracking` msost
				WHERE msost.order_id = " . (int)$order_id . " AND
					  msost.seller_id = " . (int)$seller_id;
		$res = $this->db->query($sql);
		
		if ($res->num_rows == 0) {
			return false; // No record
		}
		
		return $res->row;
	}
	
	// ****************************
	// * Shipping Balance Entries *
	// ****************************
	
	// Get shipping balance amount for the fixed shipping (either for fixed shipping or for combinable)
	public function getSellerOrderProductShippingBalance($seller_id, $order_id, $product_id, $convert = 1) {
		$sql = "SELECT 	msb.amount as 'amount'
				FROM `" . DB_PREFIX . "ms_balance` msb
				WHERE msb.balance_type = " . (int)MsBalance::MS_BALANCE_TYPE_SHIPPING . " AND 
					  msb.seller_id = " . (int)$seller_id . " AND
					  msb.order_id = " . (int)$order_id . " AND
					  msb.product_id = " . (int)$product_id;
		$res = $this->db->query($sql);
		
		if ($res->num_rows == 0) {
			return 0; // No record
		}
		
		if ($convert) {
			return $this->currency->convert($res->row['amount'], $this->config->get('config_currency'), $this->currency->getCode());
		}
		
		return $res->row['amount'];
	}
	
	// **************************
	// * Get Shipping Geo Zones *
	// **************************
	
	public function getShippingGeoZones($shipping_type, $product_id = null, $seller_id = null) {
		if ($shipping_type == self::SHIPPING_TYPE_COMBINABLE) {
			$sql = "SELECT geo_zone_id 
				FROM " . DB_PREFIX . "ms_seller_shipping_method 
				WHERE seller_id = '" . (int)$seller_id . "'
				GROUP BY geo_zone_id";
		} else if ($shipping_type == self::SHIPPING_TYPE_FIXED) {
			$sql = "SELECT geo_zone_id
				FROM " . DB_PREFIX . "ms_product_shipping_method
				WHERE product_id = '" . (int)$product_id . "'
				GROUP BY geo_zone_id";
		}
		$geo_zones = $this->db->query($sql);
		
		$result = array();
		foreach ($geo_zones->rows as $geo_zone) {
			$sql = "SELECT name FROM " . DB_PREFIX . "geo_zone
					WHERE geo_zone_id = '" . (int)$geo_zone['geo_zone_id'] . "'";
			$geo_zone_name = $this->db->query($sql);
			$result[] = $geo_zone_name->row['name'];
		}
		
		return $result;
	}
	
	// *****************
	// * Miscellaneous *
	// *****************
	
	// Get quote about geozone
	public function getQuote($address, $geo_zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
	
		return $status;
	}
	
	// Get length classes
	public function getLengthClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'title',
				'unit',
				'value'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY title";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
	
			return $query->rows;			
		} else {
			$length_class_data = $this->cache->get('length_class.' . (int)$this->config->get('config_language_id'));

			if (!$length_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$length_class_data = $query->rows;
			
				$this->cache->set('length_class.' . (int)$this->config->get('config_language_id'), $length_class_data);
			}
			
			return $length_class_data;
		}
	}
	
	// Get weight classes
	public function getWeightClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'title',
				'unit',
				'value'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY title";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
	
			return $query->rows;			
		} else {
			$weight_class_data = $this->cache->get('weight_class.' . (int)$this->config->get('config_language_id'));

			if (!$weight_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$weight_class_data = $query->rows;
			
				$this->cache->set('weight_class.' . (int)$this->config->get('config_language_id'), $weight_class_data);
			}
			
			return $weight_class_data;
		}
	}
	
	// Get Geo Zones
	public function getGeoZones($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "geo_zone";
	
			$sort_data = array(
				'name',
				'description'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
	
			return $query->rows;
		} else {
			$geo_zone_data = $this->cache->get('geo_zone');

			if (!$geo_zone_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name ASC");
	
				$geo_zone_data = $query->rows;
			
				$this->cache->set('geo_zone', $geo_zone_data);
			}
			
			return $geo_zone_data;				
		}
	}
	
}
?>
