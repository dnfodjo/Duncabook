<?php

class ControllerMultisellerProduct extends ControllerMultisellerBase {
	public function getTableData() {
		$colMap = array(
			'id' => 'product_id',
			'name' => 'pd.name',
			'status' => 'mp.product_status',
			'seller' => 'ms.nickname',
			'date_added' => 'p.date_added',
			'date_modified' => 'p.date_modified'
		);

		$sorts = array('name', 'seller', 'date_added', 'date_modified', 'status');
		$filters = array_diff($sorts, array('status'));
		
		list($sortCol, $sortDir) = $this->MsLoader->MsHelper->getSortParams($sorts, $colMap);
		$filterParams = $this->MsLoader->MsHelper->getFilterParams($filters, $colMap);

		$sellers = $this->MsLoader->MsSeller->getSellers(
			array(
				'seller_status' => array(MsSeller::STATUS_ACTIVE, MsSeller::STATUS_INACTIVE)
			),
			array(
				'order_by'  => 'ms.nickname',
				'order_way' => 'ASC'
			)
		);

		$results = $this->MsLoader->MsProduct->getProducts(
			array(),
			array(
				'order_by'  => $sortCol,
				'order_way' => $sortDir,
				'filters' => $filterParams,
				'offset' => $this->request->get['iDisplayStart'],
				'limit' => $this->request->get['iDisplayLength']
			)
		);

		$total = isset($results[0]) ? $results[0]['total_rows'] : 0;

		$columns = array();
		foreach ($results as $result) {
			// image
			if ($result['p.image'] && file_exists(DIR_IMAGE . $result['p.image'])) {
				$image = $this->MsLoader->MsFile->resizeImage($result['p.image'], 40, 40);
			} else {
				$image = $this->MsLoader->MsFile->resizeImage('no_image.png', 40, 40);
			}

			// actions
			$actions = "";
			$actions .= "<a class='ms-button ms-button-edit' href='" . $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL') . "' title='".$this->language->get('button_edit')."'></a>";
			$actions .= "<a class='ms-button ms-button-delete' href='" . $this->url->link('multiseller/product/delete', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL') . "' title='".$this->language->get('ms_delete')."'></a>";

			// seller select
			$sellerselect = "";
			$sellerselect .= "
			<select class='form-control'>
				<option value='0'>" . $this->language->get('ms_catalog_products_noseller') . "</option>";
				
				foreach($sellers as $s) {
					$sellerselect .= "<option value='{$s['seller_id']}'" . ($s['seller_id'] == $result['seller_id'] ? " selected='selected'" : "") . ">{$s['ms.nickname']}</option>";
				}

			$sellerselect .= "
			</optgroup>
			</select>
			<button type='button' data-toggle='tooltip' title='' class='ms-assign-seller btn btn-primary' data-original-title='" . $this->language->get('ms_apply') . "'><i class='fa fa-fw fa-check'></i></button>
			";
			
			$columns[] = array_merge(
				$result,
				array(
					'checkbox' => "<input type='checkbox' name='selected[]' value='{$result['product_id']}' />",
					'image' => "<img src='$image' style='padding: 1px; border: 1px solid #DDDDDD' />",
					'name' => $result['pd.name'],
					'seller' => $sellerselect,
					'status' => $result['mp.product_status'] ? $this->language->get('ms_product_status_' . $result['mp.product_status']) : '',
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['p.date_added'])),
					'date_modified' => date($this->language->get('date_format_short'), strtotime($result['p.date_modified'])),
					'actions' => $actions
				)
			);
		}

		$this->response->setOutput(json_encode(array(
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $total,
			'aaData' => $columns
		)));
	}
	
	public function index() {
		$this->document->addScript('//code.jquery.com/ui/1.11.2/jquery-ui.min.js');
		$this->validate(__FUNCTION__);
		
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}		

		$this->data['token'] = $this->session->data['token'];		
		$this->data['heading'] = $this->language->get('ms_catalog_products_heading');
		$this->document->setTitle($this->language->get('ms_catalog_products_heading'));

		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->admSetBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_menu_multiseller'),
				'href' => $this->url->link('multiseller/dashboard', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('ms_catalog_products_breadcrumbs'),
				'href' => $this->url->link('multiseller/product', '', 'SSL'),
			)
		));
		$this->data['sellers'] = $this->MsLoader->MsSeller->getSellers(
			array(
				'seller_status' => array(MsSeller::STATUS_ACTIVE, MsSeller::STATUS_INACTIVE)
			),
			array(
				'order_by'  => 'ms.nickname',
				'order_way' => 'ASC'
			)
		);
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('multiseller/product.tpl', $this->data));
	}	
	
	public function jxProductStatus() {
		$this->validate(__FUNCTION__);
		$mails = array();

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $product_id) {
				$seller = $this->MsLoader->MsSeller->getSeller($this->MsLoader->MsProduct->getSellerId($product_id));
				
				if ((int)$this->request->post['bulk_product_status'] > 0) {
					$this->MsLoader->MsProduct->createRecord($product_id, array());
					switch ($this->request->post['bulk_product_status']) {
						case MsProduct::STATUS_ACTIVE:
							if ($seller['ms.seller_status'] != MsSeller::STATUS_ACTIVE) {
								$this->session->data['error'] = $this->language->get('ms_error_product_publish');
							} else {
								$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_ACTIVE);
								$this->MsLoader->MsProduct->approve($product_id);
							}
							break;
						case MsProduct::STATUS_INACTIVE:
							$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_INACTIVE);
							$this->MsLoader->MsProduct->disapprove($product_id);
							break;
						case MsProduct::STATUS_DISABLED:
							$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_DISABLED);
							$this->MsLoader->MsProduct->disapprove($product_id);
							break;
						case MsProduct::STATUS_DELETED:
							$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_DELETED);
							$this->MsLoader->MsProduct->disapprove($product_id);
							break;
					}
					
					if (!isset($this->session->data['error']))
						$this->session->data['success'] = $this->language->get('ms_success_product_status');
				}
				
				if ($seller['ms.seller_status'] == MsSeller::STATUS_ACTIVE) {
					$mails[] = array(
						'type' => MsMail::SMT_PRODUCT_MODIFIED,
						'data' => array(
							'recipients' => $seller['c.email'],
							'addressee' => $seller['ms.nickname'],
							'message' => isset($this->request->post['product_message']) ? $this->request->post['product_message'] : '',
							'product_id' => $product_id
						)
					);
				}
			}
			
			if (isset($this->request->post['bulk_mail'])) {
				$this->MsLoader->MsMail->sendMails($mails);
			}
		} else {
			//$this->session->data['error'] = 'Error changing product status.';
		}
	}
	
	public function jxProductSeller() {
		$json = array();
		
		$this->validate(__FUNCTION__);
		if(isset($this->request->get['product_id'])){
			$products = array($this->request->get['product_id']);
		}
		else if(isset($this->request->post['selected'])){
			$products = $this->request->post['selected'];
		}
		$seller = $this->MsLoader->MsSeller->getSeller($this->request->get['seller_id']);
		foreach ($products as $product_id) {
			$this->MsLoader->MsProduct->createRecord($product_id, array('seller_id' => $this->request->get['seller_id']));
			$this->MsLoader->MsProduct->changeSeller($product_id, $this->request->get['seller_id']);
			$json['product_status'] = $this->language->get('ms_product_status_' . $seller['ms.seller_status']);
			switch($seller['ms.seller_status']) {
				case MsSeller::STATUS_INACTIVE:
				case MsSeller::STATUS_UNPAID:
					$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_INACTIVE);
					$this->MsLoader->MsProduct->disapprove($product_id);
					$json['product_status'] = $this->language->get('ms_product_status_' . MsProduct::STATUS_INACTIVE);			
					break;
				case MsSeller::STATUS_DISABLED:
				case MsSeller::STATUS_INCOMPLETE:
					$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_DISABLED);
					$this->MsLoader->MsProduct->disapprove($product_id);			
					break;
				case MsSeller::STATUS_DELETED:
					$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_DELETED);
					$this->MsLoader->MsProduct->disapprove($product_id);			
					break;
				case MsSeller::STATUS_UNPAID:
					$this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_DELETED);
					$this->MsLoader->MsProduct->disapprove($product_id);			
					break;				
				default:
					$product = $this->MsLoader->MsProduct->getProduct($product_id);
					$json['product_status'] = $this->language->get('ms_product_status_' . $product['mp.product_status']);
					break;
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function delete() {
		$product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : 0;
		$this->MsLoader->MsProduct->deleteProduct($product_id);
		$this->response->redirect($this->url->link('multiseller/product', 'token=' . $this->session->data['token'], 'SSL'));
	}	
}
?>
