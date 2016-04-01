<?php
class ModelModuleMultiMerchComments extends Model {
	public function createSchema() {
		$this->db->query("
		CREATE TABLE " . DB_PREFIX . "ms_comments (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`parent_id` int(11) DEFAULT NULL,
		`product_id` int(11) DEFAULT NULL,
		`seller_id` int(11) DEFAULT NULL,
		`customer_id` int(11) DEFAULT NULL,
		`user_id` int(11) DEFAULT NULL,
		`name` varchar(128) NOT NULL DEFAULT '',
		`email` varchar(128) NOT NULL DEFAULT '',
		`comment` text NOT NULL,
		`display` tinyint(1) NOT NULL DEFAULT 1,
		`create_time` int(11) NOT NULL,
		PRIMARY KEY (`id`)) default CHARSET=utf8");
	}
	
	public function deleteSchema() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ms_comments`");
	}
}