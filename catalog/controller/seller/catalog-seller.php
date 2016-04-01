<?php 
class ControllerSellerCatalogSeller extends ControllerSellerCatalog {
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->language->load('product/category');
		$this->load->model('localisation/country');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
	}
	
	public function index() {
		$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');

		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_grid'] = $this->language->get('text_grid');
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');
		
		if (isset($this->request->get['sort'])) {
			$order_by = $this->request->get['sort'];
		} else {
			$order_by = 'ms.nickname';
		}
		
		if (isset($this->request->get['order'])) {
			$order_way = $this->request->get['order'];
		} else {
			$order_way = 'ASC';
		}
		
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}
		
		$this->data['products'] = array();

		$total_sellers = $this->MsLoader->MsSeller->getTotalSellers(array(
			'seller_status' => array(MsSeller::STATUS_ACTIVE)
		));

		$results = $this->MsLoader->MsSeller->getSellers(
			array(
				'seller_status' => array(MsSeller::STATUS_ACTIVE)
			),
			array(
				'order_by'	=> $order_by,
				'order_way'	=> $order_way,
				'offset'	=> ($page - 1) * $limit,
				'limit'		=> $limit
			)
		);

		foreach ($results as $result) {
			if ($result['ms.avatar'] && file_exists(DIR_IMAGE . $result['ms.avatar'])) {
				$image = $this->MsLoader->MsFile->resizeImage($result['ms.avatar'], $this->config->get('msconf_seller_avatar_seller_list_image_width'), $this->config->get('msconf_seller_avatar_seller_list_image_height'));
			} else {
				$image = $this->MsLoader->MsFile->resizeImage('ms_no_image.jpg', $this->config->get('msconf_seller_avatar_seller_list_image_width'), $this->config->get('msconf_seller_avatar_seller_list_image_height'));
			}

			$country = $this->model_localisation_country->getCountry($result['ms.country_id']);
			$this->data['sellers'][] = array(
				'seller_id'  => $result['seller_id'],
				'thumb'       => $image,
				'nickname'        => $result['ms.nickname'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['ms.description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..',
				//'rating'      => $result['rating'],
				'country' => ($country ? $country['name'] : NULL),
				'company' => ($result['ms.company'] ? $result['ms.company'] : NULL),
				'website' => ($result['ms.website'] ? $result['ms.website'] : NULL),
				'country_flag' => ($country ? 'image/flags/' . strtolower($country['iso_code_2']) . '.png' : NULL),
				'total_sales' => $this->MsLoader->MsSeller->getSalesForSeller($result['seller_id']),
				'total_products' => $this->MsLoader->MsProduct->getTotalProducts(array(
					'seller_id' => $result['seller_id'],
					'product_status' => array(MsProduct::STATUS_ACTIVE)
				)),
				'href'        => $this->url->link('seller/catalog-seller/profile', '&seller_id=' . $result['seller_id'])
			);
		}
		
		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
						
		$this->data['sorts'] = array();
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_nickname_asc'),
			'value' => 'ms.nickname-ASC',
			'href'  => $this->url->link('seller/catalog-seller', '&sort=ms.nickname&order=ASC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_nickname_desc'),
			'value' => 'ms.nickname-DESC',
			'href'  => $this->url->link('seller/catalog-seller', '&sort=ms.nickname&order=DESC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_country_asc'),
			'value' => 'ms.country_id-ASC',
			'href'  => $this->url->link('seller/catalog-seller', '&sort=ms.country_id&order=ASC' . $url)
		); 

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_country_desc'),
			'value' => 'ms.country_id-DESC',
			'href'  => $this->url->link('seller/catalog-seller', '&sort=ms.country_id&order=DESC' . $url)
		); 
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		
		$this->data['limits'] = array();
		
		$this->data['limits'][] = array(
			'text'  => $this->config->get('config_product_limit'),
			'value' => $this->config->get('config_product_limit'),
			'href'  => $this->url->link('seller/catalog-seller', $url . '&limit=' . $this->config->get('config_product_limit'))
		);
					
		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => $this->url->link('seller/catalog-seller', $url . '&limit=25')
		);
		
		$this->data['limits'][] = array(
			'text'  => 50,
			'value' => 50,
			'href'  => $this->url->link('seller/catalog-seller', $url . '&limit=50')
		);

		$this->data['limits'][] = array(
			'text'  => 75,
			'value' => 75,
			'href'  => $this->url->link('seller/catalog-seller', $url . '&limit=75')
		);
		
		$this->data['limits'][] = array(
			'text'  => 100,
			'value' => 100,
			'href'  => $this->url->link('seller/catalog-seller', $url . '&limit=100')
		);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $total_sellers;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('seller/catalog-seller', $url . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();
		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($total_sellers) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($total_sellers - 5)) ? $total_sellers : ((($page - 1) * 5) + 5), $total_sellers, ceil($total_sellers / 5));
		
		$this->data['sort'] = $order_by;
		$this->data['order'] = $order_way;
		$this->data['limit'] = $limit;		
		
		$this->data['continue'] = $this->url->link('common/home');

		$this->document->setTitle($this->language->get('ms_catalog_sellers_heading'));
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_catalog_sellers'),
				'href' => $this->url->link('seller/catalog-seller', '', 'SSL'), 
			)
		));
		
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('catalog-seller');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}
		
	public function profile() {
		if (isset($this->request->get['seller_id'])) {
			$seller = $this->MsLoader->MsSeller->getSeller($this->request->get['seller_id']);
		}

		if (!isset($seller) || empty($seller) || $seller['ms.seller_status'] != MsSeller::STATUS_ACTIVE) {
			$this->response->redirect($this->url->link('seller/catalog-seller', '', 'SSL'));
			return;
		}

		$seller_id = $this->request->get['seller_id'];

		$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
		$this->document->addScript('catalog/view/javascript/dialog-sellercontact.js');

		if ($seller['ms.avatar'] && file_exists(DIR_IMAGE . $seller['ms.avatar'])) {
			$this->data['seller']['thumb'] = $this->MsLoader->MsFile->resizeImage($seller['ms.avatar'], $this->config->get('msconf_seller_avatar_seller_profile_image_width'), $this->config->get('msconf_seller_avatar_seller_profile_image_height'));
		} else {
			$this->data['seller']['thumb'] = $this->MsLoader->MsFile->resizeImage('ms_no_image.jpg', $this->config->get('msconf_seller_avatar_seller_profile_image_width'), $this->config->get('msconf_seller_avatar_seller_profile_image_height'));
		}

		if ($this->config->get('msconf_enable_seller_banner')) {
			if ($seller['banner'] && file_exists(DIR_IMAGE . $seller['banner'])) {
				$this->data['seller']['banner'] = $this->MsLoader->MsFile->resizeImage($seller['banner'], $this->config->get('msconf_product_seller_banner_width'), $this->config->get('msconf_product_seller_banner_height'), 'w');
			}
		}

		$this->data['seller']['nickname'] = $seller['ms.nickname'];
		$this->data['seller']['seller_id'] = $seller['seller_id'];
		$this->data['seller']['description'] = html_entity_decode($seller['ms.description'], ENT_QUOTES, 'UTF-8');
		$this->data['seller']['href'] = $this->url->link('seller/catalog-seller/products', 'seller_id=' . $seller['seller_id']);
		
		
		$country = $this->model_localisation_country->getCountry($seller['ms.country_id']);
		
		if (!empty($country)) {			
			$this->data['seller']['country'] = $country['name'];
		} else {
			$this->data['seller']['country'] = NULL;
		}
		
		if (!empty($seller['ms.company'])) {
			$this->data['seller']['company'] = $seller['ms.company'];
		} else {
			$this->data['seller']['company'] = NULL;
		}
		
		if (!empty($seller['ms.website'])) {
			$this->data['seller']['website'] = $seller['ms.website'];
		} else {
			$this->data['seller']['website'] = NULL;
		}
		
		$this->data['seller']['total_sales'] = $this->MsLoader->MsSeller->getSalesForSeller($seller['seller_id']);
		$this->data['seller']['total_products'] = $this->MsLoader->MsProduct->getTotalProducts(array(
			'seller_id' => $seller['seller_id'],
			'product_status' => array(MsProduct::STATUS_ACTIVE)
		));
				
		$products = $this->MsLoader->MsProduct->getProducts(
			array(
				'seller_id' => $seller['seller_id'],
				'language_id' => $this->config->get('config_language_id'),
				'product_status' => array(MsProduct::STATUS_ACTIVE)
			),
			array(
				'order_by'	=> 'pd.name',
				'order_way'	=> 'ASC',
				'offset'	=> 0,
				'limit'		=> 5
			)
		);

		if (!empty($products)) {
			foreach ($products as $product) {
				$product_info = $this->model_catalog_product->getProduct($product['product_id']);
				if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
					$image = $this->MsLoader->MsFile->resizeImage($product_info['image'], $this->config->get('msconf_product_seller_profile_image_width'), $this->config->get('msconf_product_seller_profile_image_height'));
				} else {
					$image = $this->MsLoader->MsFile->resizeImage('no_image.png', $this->config->get('msconf_product_seller_profile_image_width'), $this->config->get('msconf_product_seller_profile_image_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
							
				$this->data['seller']['products'][] = array(
					'product_id' => $product['product_id'],				
					'thumb' => $image,
					'description' => $product_info['description'],
					'tax' => $tax,
					'name' => $product_info['name'],
					'price' => $price,
					'special' => $special,
					'rating' => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
				);				
			}
		} else {
			$this->data['seller']['products'] = NULL;
		}


		$this->data['seller_id'] = $this->request->get['seller_id'];

		$this->data['ms_catalog_seller_profile_view'] = sprintf($this->language->get('ms_catalog_seller_profile_view'), $this->data['seller']['nickname']);


		$this->data['contactForm'] = $this->MsLoader->MsHelper->renderPmDialog($this->data);

		$this->document->setTitle(sprintf($this->language->get('ms_catalog_seller_profile_heading'), $this->data['seller']['nickname']));
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_catalog_sellers'),
				'href' => $this->url->link('seller/catalog-seller', '', 'SSL'), 
			),
			array(
				'text' => sprintf($this->language->get('ms_catalog_seller_profile_breadcrumbs'), $this->data['seller']['nickname']),
				'href' => $this->url->link('seller/catalog-seller/profile', '&seller_id='.$seller['seller_id'], 'SSL'),
			)
		));
		
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('catalog-seller-profile');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}

	public function products() {
		if (isset($this->request->get['seller_id'])) {
			$seller = $this->MsLoader->MsSeller->getSeller($this->request->get['seller_id']);
		}
			
		if (!isset($seller) || empty($seller) || $seller['ms.seller_status'] != MsSeller::STATUS_ACTIVE) {
			$this->response->redirect($this->url->link('seller/catalog-seller', '', 'SSL'));
			return;
		}

		$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
		
		/* seller info part */	
		if ($seller['ms.avatar'] && file_exists(DIR_IMAGE . $seller['ms.avatar'])) {
			$image = $this->MsLoader->MsFile->resizeImage($seller['ms.avatar'], $this->config->get('msconf_product_seller_products_image_width'), $this->config->get('msconf_product_seller_products_image_height'));
		} else {
			$image = $this->MsLoader->MsFile->resizeImage('ms_no_image.jpg', $this->config->get('msconf_product_seller_products_image_width'), $this->config->get('msconf_product_seller_products_image_height'));
		}
		
		$this->data['seller']['nickname'] = $seller['ms.nickname'];
		$this->data['seller']['description'] = html_entity_decode($seller['ms.description'], ENT_QUOTES, 'UTF-8');
		$this->data['seller']['thumb'] = $image;
		$this->data['seller']['href'] = $this->url->link('seller/catalog-seller/profile', 'seller_id=' . $seller['seller_id']);
		
		$country = $this->model_localisation_country->getCountry($seller['ms.country_id']);
		
		if (!empty($country)) {			
			$this->data['seller']['country'] = $country['name'];
		} else {
			$this->data['seller']['country'] = NULL;
		}
		
		if (!empty($seller['ms.company'])) {
			$this->data['seller']['company'] = $seller['ms.company'];
		} else {
			$this->data['seller']['company'] = NULL;
		}
		
		if (!empty($seller['ms.website'])) {
			$this->data['seller']['website'] = $seller['ms.website'];
		} else {
			$this->data['seller']['website'] = NULL;
		}
		
		$this->data['seller']['total_sales'] = $this->MsLoader->MsSeller->getSalesForSeller($seller['seller_id']);
		$this->data['seller']['total_products'] = $this->MsLoader->MsProduct->getTotalProducts(array(
			'seller_id' => $seller['seller_id'],
			'product_status' => array(MsProduct::STATUS_ACTIVE)
		));

		/* seller products part */
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_grid'] = $this->language->get('text_grid');
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');
		
		$available_sorts = array('pd.name-ASC', 'pd.name-DESC', 'ms.country_id-ASC', 'ms.country_id-DESC', 'pd.name', 'ms.country_id');
		if (isset($this->request->get['sort'])) {
			$order_by = $this->request->get['sort'];
			if (!in_array($order_by, $available_sorts)) {
				$order_by = 'pd.name';
			}
		} else {
			$order_by = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order_way = $this->request->get['order'];
		} else {
			$order_way = 'ASC';
		}
		
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;	
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

		$this->data['products'] = array();
		
		$sort = array(
			//'filter_category_id' => $category_id, 
			'order_by'               => $order_by,
			'order_way'              => $order_way,
			'offset'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
		
		$total_products = $this->MsLoader->MsProduct->getTotalProducts(array(
			'seller_id' => $seller['seller_id'],
			'product_status' => array(MsProduct::STATUS_ACTIVE)
		));
		
		$products = $this->MsLoader->MsProduct->getProducts(
			array(
				'seller_id' => $seller['seller_id'],
				'product_status' => array(MsProduct::STATUS_ACTIVE)
			),
			$sort
		);

		$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

		if (!empty($products)) {
			foreach ($products as $product) {
				$product_data = $this->model_catalog_product->getProduct($product['product_id']);
				if ($product_data['image'] && file_exists(DIR_IMAGE . $product_data['image'])) {
					$image = $this->MsLoader->MsFile->resizeImage($product_data['image'], $this->config->get('msconf_product_seller_products_image_width'), $this->config->get('msconf_product_seller_products_image_height'));
				} else {
					$image = $this->MsLoader->MsFile->resizeImage('no_image.png', $this->config->get('msconf_product_seller_products_image_width'), $this->config->get('msconf_product_seller_products_image_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_data['price'], $product_data['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$product_data['special']) {
					$special = $this->currency->format($this->tax->calculate($product_data['special'], $product_data['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $product_data['rating'];
				} else {
					$rating = false;
				}
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product_data['special'] ? $product_data['special'] : $product_data['price']);
				} else {
					$tax = false;
				}
							
				$this->data['seller']['products'][] = array(
					'product_id' => $product['product_id'],
					'thumb' => $image,
					'name' => $product_data['name'],
					'price' => $price,
					'tax' => $tax,
					'description' => utf8_substr(strip_tags(html_entity_decode($product['pd.description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'special' => $special,
					'rating' => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_data['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_data['product_id']),						
				);				
			}
		} else {
			$this->data['seller']['products'] = NULL;
		}
		
		
		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
						
		$this->data['sorts'] = array();
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_nickname_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('seller/catalog-seller/products', '&sort=pd.name&order=ASC&seller_id=' . $seller['seller_id'] . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_nickname_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('seller/catalog-seller/products', '&sort=pd.name&order=DESC&seller_id=' . $seller['seller_id'] . $url)
		);

		/*
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_country_asc'),
			'value' => 'ms.country_id-ASC',
			'href'  => $this->url->link('seller/catalog-seller/products', '&sort=ms.country_id&order=ASC' . $url)
		); 

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('ms_sort_country_desc'),
			'value' => 'ms.country_id-DESC',
			'href'  => $this->url->link('seller/catalog-seller/products', '&sort=ms.country_id&order=DESC' . $url)
		); 
		*/
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$this->data['limits'] = array();
		
		$this->data['limits'][] = array(
			'text'  => $this->config->get('config_product_limit'),
			'value' => $this->config->get('config_product_limit'),
			'href'  => $this->url->link('seller/catalog-seller/products', $url . '&limit=' . $this->config->get('config_product_limit') . '&seller_id=' . $seller['seller_id'])
		);
					
		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => $this->url->link('seller/catalog-seller/products', $url . '&limit=25&seller_id=' . $seller['seller_id'])
		);
		
		$this->data['limits'][] = array(
			'text'  => 50,
			'value' => 50,
			'href'  => $this->url->link('seller/catalog-seller/products', $url . '&limit=50&seller_id=' . $seller['seller_id'])
		);

		$this->data['limits'][] = array(
			'text'  => 75,
			'value' => 75,
			'href'  => $this->url->link('seller/catalog-seller/products', $url . '&limit=75&seller_id=' . $seller['seller_id'])
		);
		
		$this->data['limits'][] = array(
			'text'  => 100,
			'value' => 100,
			'href'  => $this->url->link('seller/catalog-seller/products', $url . '&limit=100&seller_id=' . $seller['seller_id'])
		);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $total_products;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('seller/catalog-seller/products', 'seller_id=' . $seller['seller_id'] .  $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->document->addLink($this->url->link('seller/catalog-seller/products', 'seller_id=' . $this->request->get['seller_id'] . $url . '&page=' . $pagination->page), 'canonical');

		if ($pagination->limit && ceil($pagination->total / $pagination->limit) > $pagination->page) {
			$this->document->addLink($this->url->link('seller/catalog-seller/products', 'seller_id=' . $this->request->get['seller_id'] . $url . '&page=' . ($pagination->page + 1)), 'next');
		}

		if ($pagination->page > 1) {
			$this->document->addLink($this->url->link('seller/catalog-seller/products', 'seller_id=' . $this->request->get['seller_id'] . $url . '&page=' . ($pagination->page - 1)), 'prev');
		}

		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($total_products) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_products - $limit)) ? $total_products : ((($page - 1) * $limit) + $limit), $total_products, ceil($total_products / $limit));

		$this->data['sort'] = $order_by;
		$this->data['order'] = $order_way;
		$this->data['limit'] = $limit;		
		
		$this->data['ms_catalog_seller_products'] = sprintf($this->language->get('ms_catalog_seller_products_heading'), $seller['ms.nickname']);
		$this->document->setTitle(sprintf($this->language->get('ms_catalog_seller_products_heading'), $seller['ms.nickname']));
		
		$this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
			array(
				'text' => $this->language->get('ms_catalog_sellers'),
				'href' => $this->url->link('seller/catalog-seller', '', 'SSL'), 
			),
			array(
				'text' => sprintf($this->language->get('ms_catalog_seller_products_breadcrumbs'), $seller['ms.nickname']),
				'href' => $this->url->link('seller/catalog-seller/profile', '&seller_id='.$seller['seller_id'], 'SSL'),
			)
		));
		
		list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('catalog-seller-products');
		$this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
	}

	public function jxSubmitContactDialog() {
		if (!$this->customer->getId() || ($this->customer->getId() == $this->request->post['seller_id'])) return;
		$seller_id = $this->request->post['seller_id'];
		$product_id = $this->request->post['product_id'];
		$seller_email = $this->MsLoader->MsSeller->getSellerEmail($seller_id);
		$seller_name = $this->MsLoader->MsSeller->getSellerName($seller_id);
		$message_text = trim($this->request->post['ms-sellercontact-text']);
		$customer_name = $this->customer->getFirstname() . ' ' . $this->customer->getLastname();
		$customer_email = $this->customer->getEmail();

		if ($product_id) {
			$product = $this->MsLoader->MsProduct->getProduct($product_id);
			$product_name = $product['languages'][$this->MsLoader->MsHelper->getLanguageId($this->config->get('config_language'))]['name'];
		}

		$title = $product_id ? sprintf($this->language->get('ms_conversation_title_product'), $product_name) : sprintf($this->language->get('ms_conversation_title'), $customer_name);

		$json = array();

		if (empty($message_text)) {
			$json['errors'][] = $this->language->get('ms_error_contact_allfields');
		}

		if (mb_strlen($message_text) > 2000) {
			$json['errors'][] = $this->language->get('ms_error_contact_text');
		}

		if (!isset($json['errors'])) {
			/* standard message email forwarding */
			if ($this->config->get('msconf_enable_private_messaging') == 2) {
				$mails[] = array(
					'type' => MsMail::SMT_SELLER_CONTACT,
					'data' => array(
						'recipients' => $seller_email,
						'customer_name' => $customer_name,
						'customer_email' => $customer_email,
						'customer_message' => $message_text,
						'product_id' => $product_id,
						'addressee' => $seller_name
					)
				);
				$this->MsLoader->MsMail->sendMails($mails);
				$json['success'] = $this->language->get('ms_sellercontact_success');
			}

			/* pm enabled */
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>
