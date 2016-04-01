<?php
final class MsSetting extends Model {
	private $_settings = array(
	);

	public function getSetting($data = array()) {
		$sql = "SELECT
					name,
					value,
					is_encoded
				FROM `" . DB_PREFIX . "ms_setting` mset
				WHERE 1 = 1 "
				. (isset($data['seller_id']) ? " AND seller_id =  " .  (int)$data['seller_id'] : '')
				. (isset($data['seller_group_id']) ? " AND seller_group_id =  " .  (int)$data['seller_group_id'] : '')

				. (isset($data['name']) ? " AND name = " . $this->db->escape('name') : '');

		$res = $this->db->query($sql);

		$settings = array();
		foreach ($res->rows as $result) {
			if (!$result['is_encoded']) {
				$settings[$result['name']] = $result['value'];
			} else {
				$setting[$result['name']] = json_decode($result['value'], true);
			}
		}
	}

	public function createSetting($data = array()) {
		foreach ($data['settings'] as $name => $value) {
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "ms_setting
				SET seller_id = " . (isset($data['seller_id']) ? (int)$data['seller_id'] : 'NULL') . ",
					seller_group_id = " . (isset($data['seller_group_id']) ? (int)$data['seller_group_id'] : 'NULL') . ",

					name = '" . $this->db->escape($name) . "',
					value = '" . is_array($value) ? $this->db->escape(json_encode($value)) : $this->db->escape($value) . "'"
			);
		}
	}

	public function updateSetting($data = array()) {
		foreach ($data['settings'] as $name => $value) {
			$this->db->query("
				UPDATE " . DB_PREFIX . "ms_setting
				SET value = '" . is_array($value) ? $this->db->escape(json_encode($value)) : $this->db->escape($value) . "'
				WHERE name = '" . $this->db->escape($name) . "'"
				. (isset($data['seller_id']) ? " AND seller_id =  " .  (int)$data['seller_id'] : '')
				. (isset($data['seller_group_id']) ? " AND seller_group_id =  " .  (int)$data['seller_group_id'] : '')
			);
		}
	}
}

?>
