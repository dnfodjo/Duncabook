<?php
class MsShippingMethod extends Model {
	// Get a shipping method
	public function getShippingMethod($shipping_method_id, $data = array()) {
		$sql = "SELECT *
				FROM " . DB_PREFIX . "ms_shipping_method msm
				WHERE msm.shipping_method_id = '" . (int)$shipping_method_id . "'";
		
		$res = $this->db->query($sql);
		
		return $res->row;
	}

	// Get all shipping methods for current language
	public function getShippingMethods($data = array()) {
	
		// Get current language ID
		/*$language_code = $this->session->data['language'];
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if ($language['code'] == $language_code) {
				$language_id = $language['language_id'];
				break;
			}
		}*/
		$language_id = $this->config->get('config_language_id');
	
		$sql = "SELECT * 
				FROM " . DB_PREFIX . "ms_shipping_method msm 
				LEFT JOIN " . DB_PREFIX . "ms_shipping_method_description msmd 
					ON (msm.shipping_method_id = msmd.shipping_method_id) 
				WHERE msmd.language_id = '" . (int)$language_id/*$this->config->get('config_language_id')*/ . "'";
		
		$sort_data = array(
			'msmd.name'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY msmd.name";
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
	}
	
	// Get shipping method descriptions
	public function getShippingMethodDescriptions($shipping_method_id) {
		$shipping_method_data = array();
	
		$sql = "SELECT * 
				FROM " . DB_PREFIX . "ms_shipping_method_description 
				WHERE shipping_method_id = '" . (int)$shipping_method_id . "'";
	
		$res = $this->db->query($sql);
	
		foreach ($res->rows as $result) {
			$shipping_method_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $shipping_method_data;
	}
	
	// Get total number of shipping methods
	public function getTotalShippingMethods() {
		$sql = "SELECT COUNT(*) as total 
				FROM " . DB_PREFIX . "ms_shipping_method";

		$res = $this->db->query($sql);
		return $res->row['total'];
	}
	
	// Create shipping method record
	public function createShippingMethod($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ms_shipping_method () VALUES()");
		$shipping_method_id = $this->db->getLastId();
		
		foreach ($data['description'] as $language_id => $value) {
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "ms_shipping_method_description 
				SET shipping_method_id = '" . (int)$shipping_method_id . "', 
					language_id = '" . (int)$language_id . "', 
					name = '" . $this->db->escape($value['name']) . "', 
					description = '" . $this->db->escape($value['description']) . "'");
		}
	}
	
	// Edit shipping method record
	public function editShippingMethod($shipping_method_id, $data) {
		foreach ($data['description'] as $language_id => $language) {
			$sql = "UPDATE " . DB_PREFIX . "ms_shipping_method_description
					SET name = '". $this->db->escape($language['name']) ."',
						description = '". $this->db->escape(htmlspecialchars(nl2br($language['description']), ENT_COMPAT)) ."'
					WHERE shipping_method_id = " . (int)$shipping_method_id . "
					AND language_id = " . (int)$language_id;
					
			$this->db->query($sql);
		}
	}
		
	// Delete shipping method
	public function deleteShippingMethod($shipping_method_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ms_shipping_method_description 
							WHERE shipping_method_id = '" . (int)$shipping_method_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ms_shipping_method WHERE shipping_method_id = '" . (int)$shipping_method_id . "'");
	}
}
?>
