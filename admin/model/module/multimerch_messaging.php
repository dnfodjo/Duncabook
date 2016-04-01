<?php
class ModelModuleMultiMerchMessaging extends Model {
	public function createSchema() {
		$this->db->query("
		CREATE TABLE `" . DB_PREFIX . "ms_conversation` (
		`conversation_id` int(11) NOT NULL AUTO_INCREMENT,
		`product_id` int(11) DEFAULT NULL,
		`order_id` int(11) DEFAULT NULL,
		`title` varchar(256) NOT NULL DEFAULT '',
		`date_created` DATETIME NOT NULL,
		PRIMARY KEY (`conversation_id`)) default CHARSET=utf8");
	
		$this->db->query("
		CREATE TABLE `" . DB_PREFIX . "ms_message` (
		`message_id` int(11) NOT NULL AUTO_INCREMENT,
		`conversation_id` int(11) NOT NULL,
		`from` int(11) DEFAULT NULL,
		`to` int(11) DEFAULT NULL,
		`message` text NOT NULL DEFAULT '',
		`read` tinyint(1) NOT NULL DEFAULT 0,
		`date_created` DATETIME NOT NULL,
		PRIMARY KEY (`message_id`)) default CHARSET=utf8");
	}
	
	public function deleteSchema() {
		$this->db->query("DROP TABLE IF EXISTS
		`" . DB_PREFIX . "ms_conversation`,
		`" . DB_PREFIX . "ms_message`");
	}
}