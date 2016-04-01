<?php
class ModelFidoHomepage extends Model {
	public function checkHomepages() {
		$create_homepage = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "homepage` (`homepage_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `sort_order` int(3) NOT NULL default '0', `image` varchar(255) collate utf8_bin default NULL, PRIMARY KEY  (`homepage_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_homepage);

		$create_homepage_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "homepage_description` (`homepage_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` varchar(64) collate utf8_bin NOT NULL default '', `description` text collate utf8_bin NOT NULL, PRIMARY KEY  (`homepage_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_homepage_descriptions);

		$create_homepage_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "homepage_to_store` (`homepage_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY  (`homepage_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_homepage_to_store);

		$create_homepage_to_user = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "homepage_to_user` (`homepage_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, PRIMARY KEY  (`homepage_id`, `user_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_homepage_to_user);
	}

	public function checkUser($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
		if (isset($query->row['user_group_id']) && ($query->row['user_group_id'] == $this->config->get('manager_management_group_id'))) {
			return true;
		} else {
			return false;
		}
	}

	public function addHomepage($data, $user_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "homepage SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
		$homepage_id = $this->db->getLastId();
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "homepage SET image = '" . $this->db->escape($data['image']) . "' WHERE homepage_id = '" . (int)$homepage_id . "'");
		}
		foreach ($data['homepage_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "homepage_description SET homepage_id = '" . (int)$homepage_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		if (isset($data['homepage_store'])) {
			foreach ($data['homepage_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "homepage_to_store SET homepage_id = '" . (int)$homepage_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (($this->config->get('manager_status') || $this->config->get('manager_restrictions')) && $this->checkUser($user_id)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "homepage_to_user SET homepage_id = '" . (int)$homepage_id . "', user_id = '" . (int)$user_id . "'");
		}

		$this->cache->delete('homepage');
		return $homepage_id;
	}

	public function editHomepage($homepage_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "homepage SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE homepage_id = '" . (int)$homepage_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "homepage_description WHERE homepage_id = '" . (int)$homepage_id . "'");
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "homepage SET image = '" . $this->db->escape($data['image']) . "' WHERE homepage_id = '" . (int)$homepage_id . "'");
		}
		foreach ($data['homepage_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "homepage_description SET homepage_id = '" . (int)$homepage_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "homepage_to_store WHERE homepage_id = '" . (int)$homepage_id . "'");
		if (isset($data['homepage_store'])) {		
			foreach ($data['homepage_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "homepage_to_store SET homepage_id = '" . (int)$homepage_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		$this->cache->delete('homepage');
	}

	public function deleteHomepage($homepage_id, $user_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "homepage WHERE homepage_id = '" . (int)$homepage_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "homepage_description WHERE homepage_id = '" . (int)$homepage_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "homepage_to_store WHERE homepage_id = '" . (int)$homepage_id . "'");

		if (($this->config->get('manager_status') || $this->config->get('manager_restrictions')) && $this->checkUser($user_id)) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "homepage_to_user WHERE homepage_id = '" . (int)$homepage_id . "'");
		}

		$this->cache->delete('homepage');
	}	

	public function getHomepage($homepage_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "homepage WHERE homepage_id = '" . (int)$homepage_id . "'");
		return $query->row;
	}

	public function getHomepageDescriptions($homepage_id) {
		$homepage_description_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "homepage_description WHERE homepage_id = '" . (int)$homepage_id . "'");
		foreach ($query->rows as $result) {
			$homepage_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		return $homepage_description_data;
	}

	public function getHomepages() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "homepage h LEFT JOIN " . DB_PREFIX . "homepage_description hd ON (h.homepage_id = hd.homepage_id) WHERE hd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY h.sort_order");
		return $query->rows;
	}

	public function getHomepagesByUser($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "homepage h LEFT JOIN " . DB_PREFIX . "homepage_description hd ON (h.homepage_id = hd.homepage_id) LEFT JOIN " . DB_PREFIX . "homepage_to_user h2u ON (h.homepage_id = h2u.homepage_id) WHERE hd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND h2u.user_id = '" . (int)$user_id . "' ORDER BY h.sort_order");
		return $query->rows;
	}

	public function getList($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "homepage h LEFT JOIN " . DB_PREFIX . "homepage_description hd ON (h.homepage_id = hd.homepage_id) WHERE hd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $this->db->escape($data['sort']);
			} else {
				$sql .= " ORDER BY hd.title";	
			}
			if (isset($data['order'])) {
				$sql .= " " . $this->db->escape($data['order']);
			} else {
				$sql .= " ASC";
			}
			if (isset($data['start']) || isset($data['limit'])) {
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "homepage h LEFT JOIN " . DB_PREFIX . "homepage_description hd ON (h.homepage_id = hd.homepage_id) WHERE hd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY hd.title");
			return $query->rows;
		}
	}

	public function getHomepageStores($homepage_id) {
		$homepage_store_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "homepage_to_store WHERE homepage_id = '" . (int)$homepage_id . "'");
		foreach ($query->rows as $result) {
			$homepage_store_data[] = $result['store_id'];
		}
		return $homepage_store_data;
	}

	public function getTotalHomepages() {
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "homepage");
		return $query->row['total'];
	}	

	public function getTotalHomepagesByUser($user_id) {
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "homepage h LEFT JOIN " . DB_PREFIX . "homepage_to_user h2u ON (h.homepage_id = h2u.homepage_id) WHERE h2u.user_id = '" . (int)$user_id . "'");
		return $query->row['total'];
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

	public function getStores($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store s LEFT JOIN " . DB_PREFIX . "store_to_user s2u ON (s.store_id = s2u.store_id) WHERE s2u.user_id = '" . (int)$user_id . "'");
		return $query->rows;
	}

	public function getLayoutsByStore($store_id, $data = array()) {
		$sql = "SELECT *, l.name AS name FROM " . DB_PREFIX . "layout l LEFT JOIN " . DB_PREFIX . "layout_route lr ON (l.layout_id = lr.layout_id) WHERE lr.store_id = '" . (int)$store_id . "'";

		$sort_data = array('name');

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
	}
}
?>
