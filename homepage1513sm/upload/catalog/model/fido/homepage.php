<?php
class ModelFidoHomepage extends Model {
	public function getHomepages() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "homepage h LEFT JOIN " . DB_PREFIX . "homepage_description hd ON (h.homepage_id = hd.homepage_id) LEFT JOIN " . DB_PREFIX . "homepage_to_store h2s on (h.homepage_id = h2s.homepage_id) WHERE h.status = '1' AND hd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND h2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY h.sort_order ASC");
		return $query->rows;
	}

	public function getSetting($key, $store_id = 0) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");

		if ($query->row) {
			if (!$query->row['serialized']) {
				$data['value'] = $query->row['value'];
			} else {
				$data['value'] = unserialize($query->row['value']);
			}
		}

		if (isset($data['value'])) {
			return $data['value'];
		} else {
			return null;
		}
	}
}
?>
