<?php
class MsOrderData extends Model {
	/** orders **/
	public function getOrders($data = array(), $sort = array(), $cols = array()) {
		$hFilters = $wFilters = '';
		
		if(isset($sort['filters'])) {
			foreach($sort['filters'] as $k => $v) {
				if (!isset($cols[$k])) {
					$wFilters .= " AND {$k} LIKE '%" . $this->db->escape($v) . "%'";
				} else {
					$hFilters .= " AND {$k} LIKE '%" . $this->db->escape($v) . "%'";
				}
			}
		}
		
		$sql = "SELECT
					SQL_CALC_FOUND_ROWS
					*,"
					// additional columns
					. (isset($cols['total_amount']) ? "
						(SELECT SUM(seller_net_amt) AS total
						FROM " . DB_PREFIX . "ms_order_product_data mopd
						WHERE order_id = o.order_id
						AND seller_id = " . (int)$data['seller_id'] . ") as total_amount,
					" : "")
					
					// product names for filtering
					. (isset($cols['products']) ? "
						(SELECT GROUP_CONCAT(name)
						FROM " . DB_PREFIX . "order_product
						LEFT JOIN " . DB_PREFIX . "ms_order_product_data
						USING(order_id, product_id)
						WHERE order_id = o.order_id
						AND seller_id = mopd.seller_id) as products,
					" : "")
				."1
		FROM `" . DB_PREFIX . "order` o
		INNER JOIN `" . DB_PREFIX . "ms_order_product_data` mopd
		USING (order_id)
		WHERE seller_id = " . (int)$data['seller_id']
		. (isset($data['order_status']) && $data['order_status'] ? " AND o.order_status_id IN  (" .  $this->db->escape(implode(',', $data['order_status'])) . ")" : '')
		
		. $wFilters
		
		. " GROUP BY order_id HAVING 1 = 1 "
		
		. $hFilters
		
		. (isset($sort['order_by']) ? " ORDER BY {$sort['order_by']} {$sort['order_way']}" : '')
		. (isset($sort['limit']) ? " LIMIT ".(int)$sort['offset'].', '.(int)($sort['limit']) : '');

		$res = $this->db->query($sql);
		$total = $this->db->query("SELECT FOUND_ROWS() as total");

		if ($res->rows) $res->rows[0]['total_rows'] = $total->row['total'];
		return $res->rows;
	}

	public function getOrderTotal($order_id, $data) {
		/* SELECT SUM(seller_net_amt) as 'total_amt',
				  SUM(store_commission_pct) as 'total_pct',
				  SUM(store_commission_flat) as 'total_flat' */
		$sql = "SELECT SUM(seller_net_amt) as 'total'
				FROM `" . DB_PREFIX . "ms_order_product_data` mopd
				WHERE order_id = " . (int)$order_id
				. (isset($data['seller_id']) ? " AND seller_id =  " .  (int)$data['seller_id'] : ''); 

		$res = $this->db->query($sql);

		return $res->row['total'];		
	}

	public function getOrderData($data = array()) {
		$sql = "SELECT *
				FROM " . DB_PREFIX . "ms_order_product_data
				WHERE 1 = 1"
				. (isset($data['product_id']) ? " AND product_id =  " .  (int)$data['product_id'] : '')
				. (isset($data['order_id']) ? " AND order_id =  " .  (int)$data['order_id'] : '');
		
		$res = $this->db->query($sql);

		return ($res->num_rows == 1 && isset($data['single']) ? $res->row : $res->rows);
	}

	public function getOrderComment($data = array()) {
		$sql = "SELECT *
				FROM " . DB_PREFIX . "ms_order_comment
				WHERE 1 = 1 "
			. (isset($data['product_id']) ? " AND product_id =  " .  (int)$data['product_id'] : '')
			. (isset($data['seller_id']) ? " AND seller_id =  " .  (int)$data['seller_id'] : '')
			. (isset($data['order_id']) ? " AND order_id =  " .  (int)$data['order_id'] : '');

		$res = $this->db->query($sql);

		return isset($res->row['comment']) ? $res->row['comment'] : '';
	}

	public function getOrderProducts($data) {
		$sql = "SELECT *
				FROM " . DB_PREFIX . "order_product
				LEFT JOIN " . DB_PREFIX . "ms_order_product_data
					USING(order_id, product_id)
				WHERE 1 = 1"
				. (isset($data['order_id']) ? " AND order_id =  " .  (int)$data['order_id'] : '')
				. (isset($data['seller_id']) ? " AND seller_id =  " .  (int)$data['seller_id'] : '');

		$res = $this->db->query($sql);

		return $res->rows;
	}
	
	public function addOrderProductData($order_id, $product_id, $data) {
		$sql = "INSERT INTO " . DB_PREFIX . "ms_order_product_data
				SET order_id = " . (int)$order_id . ",
					product_id = " . (int)$product_id . ",
					seller_id = " . (int)$data['seller_id'] . ",
					store_commission_flat = " . (float)$data['store_commission_flat'] . ",
					store_commission_pct = " . (float)$data['store_commission_pct'] . ",
					seller_net_amt = " . (float)$data['seller_net_amt'];
				
		$this->db->query($sql);
		return $this->db->getLastId();
	}

	public function addOrderComment($order_id, $product_id, $data) {
		$sql = "INSERT INTO " . DB_PREFIX . "ms_order_comment
				SET order_id = " . (int)$order_id . ",
					product_id = " . (int)$product_id . ",
					seller_id = " . (int)$data['seller_id'] . ",
					comment = '" . $data['comment']. "'";

		$this->db->query($sql);

		$order_comment_id = $this->db->getLastId();
		return $order_comment_id;
	}

	public function getTotalSales($data = array()) {
		$sql = "SELECT SUM(quantity) as 'total' FROM (
					SELECT quantity FROM `" . DB_PREFIX . "ms_order_product_data` mopd
					LEFT JOIN (SELECT order_id, order_product_id, sum(quantity) as quantity FROM `" . DB_PREFIX . "order_product` op GROUP BY order_product_id) as op
						USING(order_id)
					INNER JOIN `" . DB_PREFIX . "order` o
						USING(order_id)
					WHERE 1 = 1"
					. (isset($data['order_id']) ? " AND mopd.order_id =  " .  (int)$data['order_id'] : '')
					. (isset($data['seller_id']) ? " AND seller_id =  " .  (int)$data['seller_id'] : '')
					. (isset($data['product_id']) ? " AND mopd.product_id =  " .  (int)$data['product_id'] : '')
					. (isset($data['period_start']) ? " AND DATEDIFF(o.date_added, '{$data['period_start']}') >= 0" : "")
					. " AND o.order_status_id IN  (" .  $this->db->escape(implode(',', $this->config->get('msconf_credit_order_statuses'))) . ")"
					. " GROUP BY order_product_id
				) t";

		$res = $this->db->query($sql);
		return (int)$res->row['total'];
	}

	/** suborder histories **/
	public function addSuborderHistory($data = array()) {
		$sql = "INSERT INTO " . DB_PREFIX . "ms_suborder_history
				SET suborder_id = " . (int)$data['suborder_id'] . ",
					order_status_id = " . (int)$data['order_status_id'] . ",
					comment = '" . $this->db->escape(isset($data['comment']) ? $data['comment'] : '') . "',
					date_added = NOW()";

		return $this->db->query($sql);
	}

	public function getSuborderHistory($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ms_suborder_history
				WHERE 1 = 1"
				. (isset($data['suborder_id']) ? " AND suborder_id =  " .  (int)$data['suborder_id'] : '');

		$res = $this->db->query($sql);
		return ($res->num_rows == 1 && isset($data['single']) ? $res->row : $res->rows);
	}

	/** suborders **/
	public function createSuborder($data = array())	{
		$sql = "INSERT INTO " . DB_PREFIX . "ms_suborder
				SET order_id = " . (int)$data['order_id'] . ",
					seller_id = " . (int)$data['seller_id'] . ",
					order_status_id = " . (int)$data['order_status_id'];

		return $this->db->query($sql);
	}

	public function getSuborders($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ms_suborder
				WHERE 1 = 1 "
				. (isset($data['seller_id']) ? " AND seller_id =  " .  (int)$data['seller_id'] : '')
				. (isset($data['order_id']) ? " AND order_id =  " .  (int)$data['order_id'] : '')
				. (isset($data['suborder_id']) ? " AND suborder_id =  " .  (int)$data['suborder_id'] : '');

		$res = $this->db->query($sql);
		return ($res->num_rows == 1 && isset($data['single']) ? $res->row : $res->rows);
	}

	public function updateSuborderStatus($data = array()) {
		$sql = "UPDATE " . DB_PREFIX . "ms_suborder
				SET order_status_id = " . (int)$data['order_status_id'] . "
				WHERE suborder_id = " . (int)$data['suborder_id'];

		$this->db->query($sql);
	}
}
?>
