<?php
class ModelTotalMsShipping extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('multiseller/multiseller_physical');
		$shipping_total = (float)0.0;
		$sellers = array();
		if ($this->cart->hasShipping() && (isset($this->session->data['shipping_methods']) || isset($this->session->data['seller_shipping_methods']))) {
			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$seller_id = $this->MsLoader->MsProduct->getSellerId($product['product_id']);
					$shipping_type = $this->MsLoader->MsShipping->getSellerShippingType($seller_id);
					
					// Fixed shipping
					if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED && isset($this->session->data['shipping_methods']) && isset($this->session->data['shipping_methods'][$product['product_id']])) {
						$total += $this->session->data['shipping_methods'][$product['product_id']]['cost_unformatted'];
						$shipping_total += $this->session->data['shipping_methods'][$product['product_id']]['cost_unformatted'];
					// Combinable shipping
					} else if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE && isset($this->session->data['seller_shipping_methods']) && isset($this->session->data['seller_shipping_methods'][$seller_id])) {
						if (!in_array($seller_id, $sellers)) {
							$total += $this->session->data['seller_shipping_methods'][$seller_id]['cost_unformatted'];
							$shipping_total += $this->session->data['seller_shipping_methods'][$seller_id]['cost_unformatted'];
							$sellers[] = $seller_id;
						}
					}
				}
			}
			
			$total_data[] = array(
				'code'			=> 'ms_shipping',
				'title'			=> $this->language->get('ms_shipping_totals'),
				'text'			=> $this->currency->format($shipping_total),
				'value'			=> $shipping_total,
				'sort_order'	=> $this->config->get('ms_shipping_sort_order')
			);
		}
	}
}
?>