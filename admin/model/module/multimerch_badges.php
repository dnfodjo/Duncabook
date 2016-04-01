<?php
class ModelModuleMultiMerchBadges extends Model {
	public function createSchema() {
		$this->db->query("
		CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ms_badge` (
		`badge_id` int(11) NOT NULL AUTO_INCREMENT,
		`image` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`badge_id`)) default CHARSET=utf8");
	
		$this->db->query("
		CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ms_badge_description` (
		`badge_id` int(11) NOT NULL,
		`name` varchar(32) NOT NULL DEFAULT '',
		`description` text NOT NULL,
		`language_id` int(11) DEFAULT NULL,
		PRIMARY KEY (`badge_id`, `language_id`)) default CHARSET=utf8");
	
		$this->db->query("
		CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ms_badge_seller_group` (
		`badge_id` INT(11) NOT NULL,
		`seller_id` int(11) DEFAULT NULL,
		`seller_group_id` int(11) DEFAULT NULL,
		PRIMARY KEY (`badge_id`, `seller_id`, `seller_group_id`)) default CHARSET=utf8");
	}
	
	public function deleteSchema() {
		$this->db->query("DROP TABLE IF EXISTS
		`" . DB_PREFIX . "ms_badge`,
		`" . DB_PREFIX . "ms_badge_description`,
		`" . DB_PREFIX . "ms_badge_seller_group`");
	}
}