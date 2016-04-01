<?php

class ControllerMultisellerDashboard extends ControllerMultisellerBase {
	public function index() {
		$this->response->redirect($this->url->link('module/multiseller', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
?>
