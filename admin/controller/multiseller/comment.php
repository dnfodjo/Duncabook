<?php

class ControllerMultisellerComment extends ControllerMultisellerBase {
	public function __construct($registry) {
		parent::__construct($registry);
		$this->data = array_merge($this->data, $this->load->language('multiseller/multiseller'), $this->load->language('module/multimerch_comments'));
	}

	public function getTableData() {
		$colMap = array(
			'customer_name' => 'name',
			'date_created' => 'create_time',
		);

		$this->load->model('tool/image');
		$sorts = array('customer_name', 'product_name', 'comment', 'date_created');
		$filters = array_diff($sorts, array('date_created'));
		
		list($sortCol, $sortDir) = $this->MsLoader->MsHelper->getSortParams($sorts, $colMap);
		$filterParams = $this->MsLoader->MsHelper->getFilterParams($filters, $colMap);

		$results = $this->MsLoader->MsComments->getComments(
			array(),
			array(
				'order_by'  => $sortCol,
				'order_way' => $sortDir,
				'filters' => $filterParams,
				'offset' => $this->request->get['iDisplayStart'],
				'limit' => $this->request->get['iDisplayLength']
			),
			array(
				'product_name' => 1,
			)
		);

		$total = isset($results[0]) ? $results[0]['total_rows'] : 0;

		$columns = array();
		foreach ($results as $result) {
			// actions
			$actions = "";
			//$actions .= "<a class='ms-button ms-button-delete' href='" . $this->url->link('multiseller/comment/jxdelete', 'token=' . $this->session->data['token'] . '&comment_id=' . $result['id'], 'SSL') . "' title='".$this->language->get('text_delete')."'></a>";
			$actions .= "<a class='ms-button ms-button-delete' title='".$this->language->get('text_delete')."'></a>";

            $seller = $this->MsLoader->MsSeller->getSeller($result['seller_id']);

			$columns[] = array_merge(
				$result,
				array(
					'checkbox' => "<input type='checkbox' name='selected[]' value='{$result['id']}' />",
					'customer_name' => isset($result['customer_id']) ? "<a href='" . $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL') . "'>{$result['name']}({$result['email']})</a>" : "{$result['name']}({$result['email']})",
					'product_name' => $result['product_name'],
					'seller_name' => $seller['name'],
					'comment' => (mb_strlen($result['comment']) > 80 ? mb_substr($result['comment'], 0, 80) . '...' : $result['comment']),
					'date_created' => date($this->language->get('date_format_short'), $result['create_time']),
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
	
	public function jxDelete() {
		$this->validate(__FUNCTION__);
		$mails = array();
		
		$comment_ids = isset($this->request->post['selected']) ? $this->request->post['selected'] : (isset($this->request->get['comment_id']) ? array($this->request->get['comment_id']) : false);
		if ($comment_ids && !empty($comment_ids)) {
			foreach ($comment_ids as $id) {
				$this->MsLoader->MsComments->deleteComment($id);
			}
			
			$this->session->data['success'] = $this->language->get('ms_success_comments_deleted');
		}
	}
		
	public function index() {
		$this->validate(__FUNCTION__);
		$this->load->model('catalog/product');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
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
		$this->data['heading'] = $this->language->get('ms_comments_heading');
		$this->document->setTitle($this->language->get('ms_comments_heading'));
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->admSetBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_menu_multiseller'),
				'href' => $this->url->link('multiseller/dashboard', '', 'SSL'),
			),
			array(
				'text' => $this->language->get('ms_comments_breadcrumbs'),
				'href' => $this->url->link('multiseller/comment', '', 'SSL'),
			)
		));
		
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view("multiseller/comment.tpl", $this->data));
	}
}
?>

