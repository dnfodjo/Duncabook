<?php
class ModelModuleMultiMerchShipping extends Model {
	public function createTable() {
		// ms_product_shipping_method - table containing information about shipping methods for each product
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_product_shipping_method` (
				`product_shipping_method_id` int(11) NOT NULL AUTO_INCREMENT,
				`product_id` int(11) NOT NULL,
				`shipping_method_id` int(11) NOT NULL,
				`geo_zone_id` int(11) NOT NULL,
				`cost` DECIMAL(15,8) NOT NULL,
				`comment` TEXT DEFAULT '',
			PRIMARY KEY (`product_shipping_method_id`)) DEFAULT CHARSET=utf8";
			//`currency_id` int(11) NOT NULL,
			//`currency_code` VARCHAR(3) NOT NULL,
		
		$this->db->query($sql);
		
		// ms_seller_shipping_method - table containing information about shipping methods for each seller (if combinable shipping is enabled)
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_seller_shipping_method` (
				`seller_shipping_method_id` int(11) NOT NULL AUTO_INCREMENT,
				`seller_id` int(11) NOT NULL,
				`shipping_method_id` int(11) NOT NULL,
				`geo_zone_id` int(11) NOT NULL,
				`weight_class_id` int(11) NOT NULL,
				`weight_step` DECIMAL(15,4) NOT NULL,
				`cost_per_unit` DECIMAL(15,8) NOT NULL,
				`comment` TEXT DEFAULT '',
			PRIMARY KEY (`seller_shipping_method_id`)) DEFAULT CHARSET=utf8";
			//`currency_id` int(11) NOT NULL,
			//`currency_code` VARCHAR(3) NOT NULL,
		
		$this->db->query($sql);
		
		// ms_shipping_method_range - range table for the shipping methods
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_shipping_method_range` (
			`range_id` int(11) NOT NULL AUTO_INCREMENT,
			 `seller_shipping_method_id` int(11) NOT NULL,
			 `from` DECIMAL(15,4) NOT NULL,
			 `to` DECIMAL(15,4) NOT NULL,
			 `cost` DECIMAL(15,8) NOT NULL,
			PRIMARY KEY (`range_id`)) default CHARSET=utf8";
		
		// ms_shipping_method - table containing shipping methods (only IDs at the moment)
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_shipping_method` (
				`shipping_method_id` int(11) NOT NULL AUTO_INCREMENT,
			PRIMARY KEY (`shipping_method_id`)) default CHARSET=utf8";
		
		$this->db->query($sql);
		
		// ms_shipping_method_description - table containing information about shipping methods (for each language)
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_shipping_method_description` (
				`shipping_method_description_id` int(11) NOT NULL AUTO_INCREMENT,
				`shipping_method_id` int(11) NOT NULL,
				`name` VARCHAR(32) NOT NULL DEFAULT '',
				`description` TEXT DEFAULT '',
				`language_id` int(11) DEFAULT NULL,
			PRIMARY KEY (`shipping_method_description_id`)) default CHARSET=utf8";
		
		$this->db->query($sql);
		
		// ms_seller - add shipping type column
		$sql = "
			ALTER TABLE `" . DB_PREFIX . "ms_seller` ADD (
				`shipping_type` TINYINT NOT NULL)";
		$this->db->query($sql);
		
		// ms_order_shipping - table containing information (shipping_type) about shipping for each order
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_order_shipping` (
				`order_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
				`shipping_type` TINYINT NOT NULL,
				`order_id` int(11) NOT NULL,
				`seller_id` int(11) DEFAULT NULL,
			PRIMARY KEY (`order_shipping_id`)) DEFAULT CHARSET=utf8";
		
		$this->db->query($sql);
		
		// ms_order_product_shippable - table containing information (whether it is shippable in particular) about each order product
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_order_product_shippable` (
				`order_product_shippable_id` int(11) NOT NULL AUTO_INCREMENT,
				`shippable` tinyint(1) NOT NULL,
				`order_id` int(11) NOT NULL,
				`product_id` int(11) DEFAULT NULL,
			PRIMARY KEY (`order_product_shippable_id`)) DEFAULT CHARSET=utf8";
		
		$this->db->query($sql);
		
		// ms_order_product_shipping - table containing information about shipping for each order product
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_order_product_shipping` (
				`order_product_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
				`shipping_method_name` VARCHAR(32) NOT NULL DEFAULT '',
				`shipping_cost` DECIMAL(15,4) NOT NULL,
				`product_id` int(11) NOT NULL,
				`order_id` int(11) NOT NULL,
				`seller_id` int(11) DEFAULT NULL,
			PRIMARY KEY (`order_product_shipping_id`)) DEFAULT CHARSET=utf8";
			//`product_shipping_method_id` int(11) NOT NULL,
		
		$this->db->query($sql);
		
		// ms_order_seller_shipping - table containing information about shipping for each seller in order
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_order_seller_shipping` (
				`order_seller_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
				`shipping_method_name` VARCHAR(32) NOT NULL DEFAULT '',
				`shipping_cost` DECIMAL(15,4) NOT NULL,
				`order_id` int(11) NOT NULL,
				`seller_id` int(11) DEFAULT NULL,
			PRIMARY KEY (`order_seller_shipping_id`)) DEFAULT CHARSET=utf8";
			//`seller_shipping_method_id` int(11) NOT NULL,
		
		$this->db->query($sql);
		
		// ms_order_shipping_tracking - table containing information about order shipping - in particular tracking information
		$sql = "
			CREATE TABLE `" . DB_PREFIX . "ms_order_shipping_tracking` (
				`order_shipping_tracking_id` int(11) NOT NULL AUTO_INCREMENT,
				`shipped` tinyint(1) NOT NULL,
				`tracking_number` VARCHAR(32) NOT NULL DEFAULT '',
				`comment` TEXT DEFAULT '',
				`order_id` int(11) NOT NULL,
				`seller_id` int(11) DEFAULT NULL,
			PRIMARY KEY (`order_shipping_tracking_id`)) DEFAULT CHARSET=utf8";
		
		$this->db->query($sql);
	}
	
	public function addData() {
		// Insert default carrier and descriptions for it
		$this->db->query("INSERT INTO " . DB_PREFIX . "ms_shipping_method () VALUES()");
		$shipping_method_id = $this->db->getLastId();
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $code => $language) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ms_shipping_method_description SET shipping_method_id = '" . (int)$shipping_method_id . "', language_id = '" . (int)$language['language_id'] . "', name = 'Default carrier', description = 'Default shipping carrier company (delete it)'");
		}
		
		// Permissions for Shipping Methods Area
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'multiseller/shipping-method');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'multiseller/shipping-method');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'total/ms_shipping');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'total/ms_shipping');
	}

	public function dropTable() {
		$sql = "
			DROP TABLE IF EXISTS
				`" . DB_PREFIX . "ms_product_shipping_method`,
				`" . DB_PREFIX . "ms_seller_shipping_method`,
				`" . DB_PREFIX . "ms_shipping_method_range`,
				`" . DB_PREFIX . "ms_shipping_method`,
				`" . DB_PREFIX . "ms_shipping_method_description`,
				`" . DB_PREFIX . "ms_order_shipping`,
				`" . DB_PREFIX . "ms_order_product_shippable`,
				`" . DB_PREFIX . "ms_order_product_shipping`,
				`" . DB_PREFIX . "ms_order_seller_shipping`,
				`" . DB_PREFIX . "ms_order_shipping_tracking`";
				
		$this->db->query($sql);

		$sql = "ALTER TABLE `" . DB_PREFIX . "ms_seller` 
				DROP COLUMN `shipping_type`";
		$this->db->query($sql);
	}
}

?>
