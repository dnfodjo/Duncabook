<?php
class ModelModuleMultiMerchSocialLinks extends Model {
	public function createSchema() {
		$this->db->query("
		CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ms_channel` (
		`channel_id` int(11) NOT NULL AUTO_INCREMENT,
		`image` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`channel_id`)) default CHARSET=utf8");

		$this->db->query("
		CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ms_channel_description` (
		`channel_id` int(11) NOT NULL,
		`language_id` int(11) DEFAULT NULL,
		`name` VARCHAR(32) NOT NULL DEFAULT '',
		`description` TEXT NOT NULL DEFAULT '',
		PRIMARY KEY (`channel_id`, `language_id`)) default CHARSET=utf8");

		$this->db->query("
		CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ms_seller_channel` (
		`seller_id` int(11) NOT NULL,
		`channel_id` int(11) NOT NULL,
		`channel_value` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`seller_id`, `channel_id`)) default CHARSET=utf8");
	}

	public function createData() {
		// default social links
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		foreach(array('Facebook', 'Twitter', 'LinkedIn', 'Google+') as $channel) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ms_channel SET image = 'catalog/multimerch/social_links/GraphicBurger/{$channel}.png'");
			$channel_id = $this->db->getLastId();

			foreach ($languages as $code => $language) {
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "ms_channel_description
					SET channel_id = $channel_id,
					 	language_id = ". (int)$language['language_id'] . ",
						name = '" . $this->db->escape($channel) . "',
						description = 'Please specify your " . $this->db->escape($channel) . " link'
				");
			}
		}
	}

	public function deleteSchema() {
		$this->db->query("DROP TABLE IF EXISTS
		`" . DB_PREFIX . "ms_channel`,
		`" . DB_PREFIX . "ms_channel_description`,
		`" . DB_PREFIX . "ms_seller_channel`");
	}
}