<?php
class ControllerModuleHomepage extends Controller {
	protected function index($setting) {
		$this->load->model('fido/homepage');
		$this->load->model('tool/image');

		$this->data['homepages'] = array();

		foreach ($this->model_fido_homepage->getHomepages() as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}
			if ($this->model_fido_homepage->getSetting('homepage_split_module')) {
				if (($setting['position'] == 'content_top') && ($result['sort_order'] % 2 > 0)) {
					$this->data['homepages'][] = array(
						'title'       => $result['title'],
						'description' => html_entity_decode($result['description']),
						'sort_order'  => $result['sort_order'],
						'image'       => $image
					);
				}
				if (($setting['position'] == 'content_bottom') && ($result['sort_order'] % 2 == 0)) {
					$this->data['homepages'][] = array(
						'title'       => $result['title'],
						'description' => html_entity_decode($result['description']),
						'sort_order'  => $result['sort_order'],
						'image'       => $image
					);
				}
			} else {
				$this->data['homepages'][] = array(
					'title'       => $result['title'],
					'description' => html_entity_decode($result['description']),
					'sort_order'  => $result['sort_order'],
					'image'       => $image
				);
			}
		}

		$this->data['min_height'] = $setting['image_height'];

		$this->data['layout_option'] = $this->config->get('homepage_layout_option');

		if ($this->config->get('homepage_grid_cols')) {
			$cols = $this->config->get('homepage_grid_cols');
		} else {
			$cols = 2;
		}

		$this->data['cols'] = $cols;
		$this->data['col_width'] = 100 / $cols;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/homepage.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/homepage.tpl';
		} else {
			$this->template = 'default/template/module/homepage.tpl';
		}

		$this->render();
	}
}
?>
