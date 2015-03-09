<?php
/**
 * Created by PhpStorm.
 * User: Peter Du
 * Email: peterdu@gmail.com
 * Date: 1/20/2015 0020
 * Time: 8:57 PM
 * Description Create Moshe Flyer Controller 
 *				Fetch Flyer information. Save Update delete
 *				Flyer Category add Update delete
*/

/**
 * Class ControllerModuleMosheFlyer
 */
class ControllerModuleMosheFlyer extends Controller {
	private $error = array();
	/********************************************************
	 * Module Setting
	 *
	 ******************************************************/
	//Module setting.
	public function index() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->model_moshe_flyer->checkFlyers();

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('moshe_flyer', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getModule();
	}
	// fetch module (including configuration)
	private function getModule() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		//loading text from language file.
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');

		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_headline'] = $this->language->get('entry_headline');
		$this->data['entry_numchars'] = $this->language->get('entry_numchars');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_headline_chars'] = $this->language->get('entry_headline_chars');
		$this->data['entry_flyerpage_thumb'] = $this->language->get('entry_flyerpage_thumb');
		$this->data['entry_flyerpage_image'] = $this->language->get('entry_flyerpage_image');
		$this->data['entry_module_headline'] = $this->language->get('entry_module_headline');
		$this->data['entry_directory'] = $this->language->get('entry_directory');
		$this->data['entry_pdf_file'] = $this->language->get('entry_pdf_file');

		$this->data['button_category'] = $this->language->get('button_category');
		$this->data['button_flyer'] = $this->language->get('button_flyer');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

		//process error warning
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['module_chars'])) {
			$this->data['error_module_chars'] = $this->error['module_chars'];
		} else {
			$this->data['error_module_chars'] = array();
		}

		if (isset($this->error['flyerpage_thumb'])) {
			$this->data['error_flyerpage_thumb'] = $this->error['flyerpage_thumb'];
		} else {
			$this->data['error_flyerpage_thumb'] = '';
		}

		if (isset($this->error['flyerpage_normal_image'])) {
			$this->data['error_flyerpage_normal_image'] = $this->error['flyerpage_normal_image'];
		} else {
			$this->data['error_flyerpage_normal_image'] = '';
		}

		// get existed configuration.
		//breadcrumbs
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_module'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/moshe_flyer', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		//fetch Category Listing with Link and actions
		$this->data['listingCategory'] = $this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['flyer'] = $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action'] = $this->url->link('module/moshe_flyer', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		// Image size configuration ( default ).
		if (isset($this->request->post['flyer_headline_chars'])) {
			$this->data['flyer_headline_chars'] = $this->request->post['flyer_headline_chars'];
		} else {
			$this->data['flyer_headline_chars'] = $this->config->get('flyer_headline_chars');
		}

		if (isset($this->request->post['flyer_thumb_width'])) {
			$this->data['flyer_thumb_width'] = $this->request->post['flyer_thumb_width'];
		} else {
			$this->data['flyer_thumb_width'] = $this->config->get('flyer_thumb_width');
		}

		if (isset($this->request->post['flyer_thumb_height'])) {
			$this->data['flyer_thumb_height'] = $this->request->post['flyer_thumb_height'];
		} else {
			$this->data['flyer_thumb_height'] = $this->config->get('flyer_thumb_height');
		}

		if (isset($this->request->post['flyer_normal_image_width'])) {
			$this->data['flyer_normal_image_width'] = $this->request->post['flyer_normal_image_width'];
		} else {
			$this->data['flyer_normal_image_width'] = $this->config->get('flyer_normal_image_width');
		}

		if (isset($this->request->post['flyer_normal_image_height'])) {
			$this->data['flyer_normal_image_height'] = $this->request->post['flyer_normal_image_height'];
		} else {
			$this->data['flyer_normal_image_height'] = $this->config->get('flyer_normal_image_height');
		}

		if (isset($this->request->post['moshe_flyer_file_directory'])) {
			$this->data['moshe_flyer_file_directory'] = $this->request->post['moshe_flyer_file_directory'];
		} else if($this->config->get('moshe_flyer_file_directory')){
			$this->data['moshe_flyer_file_directory'] = $this->config->get('moshe_flyer_file_directory');
		} else{
			$this->data['moshe_flyer_file_directory'] = 'moshe_flyer';
		}

		if (isset($this->request->post['flyer_headline_module'])) {
			$this->data['flyer_headline_module'] = $this->request->post['flyer_headline_module'];
		} else {
			$this->data['flyer_headline_module'] = $this->config->get('flyer_headline_module');
		}


		//fetch Module configuration
		$this->data['modules'] = array();

		if (isset($this->request->post['moshe_flyer_module'])) {
			$this->data['modules'] = $this->request->post['moshe_flyer_module'];
		} elseif ($this->config->get('moshe_flyer_module')) {
			$this->data['modules'] = $this->config->get('moshe_flyer_module');
		}

		//loading Layout file.
		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/moshe_flyer/flyer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	/********************************************************
	 * Flyer Methods
	 *
	 ******************************************************/

	//add flyer
	public function insert() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {

			$this->model_moshe_flyer->addFlyer($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	// update the flyer by ID
	public function update() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_moshe_flyer->editFlyer($this->request->get['flyer_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	//delete flyer
	public function delete() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $flyer_id) {
				$this->model_moshe_flyer->deleteFlyer($flyer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function listing() {
		$this->load->language('module/moshe_flyer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	//fetch listing for all of flyers.
	private function getList() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_category'] = $this->language->get('column_category');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['button_category'] = $this->language->get('button_category');
		$this->data['button_module'] = $this->language->get('button_module');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}


		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'] .$url, 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'].$url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);


		$this->data['module'] = $this->url->link('module/moshe_flyer', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['category'] = $this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('module/moshe_flyer/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('module/moshe_flyer/delete', 'token=' . $this->session->data['token'], 'SSL');

		$filterData = array(
			'order'			=> $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		$flyer_total = $this->model_moshe_flyer->getTotalFlyers($filterData);
		$results = $this->model_moshe_flyer->getAllFlyersList($filterData);

		$this->data['moshe_flyers'] = array();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/moshe_flyer/update', 'token=' . $this->session->data['token'] . '&flyer_id=' . $result['flyer_id'], 'SSL')
			);
			$this->data['moshe_flyers'][] = array(
				'flyer_id'     => $result['flyer_id'],
				'title'       => $result['title'],
				'category'    => $result['category_title'],
				'flyer_pdf_file' => $result['flyer_pdf_file'],
				'date_start'	=> $result['flyer_start_time'],
				'date_end'	=> $result['flyer_end_time'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    => isset($this->request->post['selected']) && in_array($result['flyer_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		$pagination = new Pagination();
		$pagination->total = $flyer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'module/moshe_flyer/list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	/********************************************************
	 * Flyer Category Methods
	 *
	 ******************************************************/

	public function insertCategory() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->document->setTitle($this->language->get('heading_title_category'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateFormCategory())) {
			$this->model_moshe_flyer->addFlyerCategory($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getCategoryForm();
	}

	public function updateCategory() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->document->setTitle($this->language->get('heading_title_category'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateFormCategory())) {
			$this->model_moshe_flyer->editFlyerCategory($this->request->get['category_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_category');

			$this->redirect($this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getCategoryForm();
	}

	public function deleteCategory() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->document->setTitle($this->language->get('heading_title_category'));

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_moshe_flyer->deleteFlyerCategory($category_id);
			}

			$this->session->data['success'] = $this->language->get('text_success_category');

			$this->redirect($this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getCategoryList();
	}

	public function listingCategory() {
		$this->load->language('module/moshe_flyer');

		$this->document->setTitle($this->language->get('heading_title_category'));

		$this->getListCategory();
	}

	// get all of the category listing. this worked with private it will be used by
	// listing Category.
	private function getListCategory() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_title_category'] = $this->language->get('heading_title_category');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['button_flyer'] = $this->language->get('button_flyer');
		$this->data['button_module'] = $this->language->get('button_module');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title_category'),
			'separator' => ' :: '
		);

		$this->data['flyers_total'] = $this->model_moshe_flyer->getTotalFlyerCategory();;

		$filterData = array(
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->data['moshe_flyers'] = array();
		$category_total = $this->model_moshe_flyer->getTotalFlyerCategory($filterData);
		$results = $this->model_moshe_flyer->getFlyerAllCategoriesbyDefaultLanguage($filterData);

		foreach ($results as $result){
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/moshe_flyer/updateCategory', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL')
			);

			$this->data['moshe_flyers'][] = array(
				'category_id' => $result['category_id'],
				'title'       => $result['title'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    => isset($this->request->post['selected']) && in_array($result['flyer_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['flyerlist']   = $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['module'] = $this->url->link('module/moshe_flyer', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['category'] = $this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('module/moshe_flyer/insertCategory', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('module/moshe_flyer/deleteCategory', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'module/moshe_flyer/list-category.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	//create form for category add, update.
	private function getCategoryForm() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->data['heading_title_category'] = $this->language->get('heading_title_category');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_image'] = $this->language->get('entry_image');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title_category'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->link('module/moshe_flyer/insertCategory', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/moshe_flyer/updateCategory', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('module/moshe_flyer/listingCategory', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($this->request->get['category_id'])) {
			$category_info = $this->model_moshe_flyer->getFlyerCategoryById($this->request->get['category_id']);
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = '';
		}
		if (isset($this->request->post['category_description'])) {
			$this->data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_description'] = $this->model_moshe_flyer->getFlyerCategoryDescriptions($this->request->get['category_id']);
		} else {
			$this->data['category_description'] = array();
		}
		$this->template = 'module/moshe_flyer/form-category.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	//create form for flyer create.
	private function getForm() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');

		$this->data['entry_start_time'] = $this->language->get('entry_start_time');
		$this->data['entry_end_time'] = $this->language->get('entry_end_time');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_image_thumbnail'] = $this->language->get('entry_image_thumbnail');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_mask'] = $this->language->get('entry_mask');
		$this->data['entry_pdf_filename_label'] = $this->language->get('entry_pdf_filename_label');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['entry_top_show'] = $this->language->get('entry_top_show');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['flyer_id'])) {
			$this->data['action'] = $this->url->link('module/moshe_flyer/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/moshe_flyer/update', 'token=' . $this->session->data['token'] . '&flyer_id=' . $this->request->get['flyer_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('module/moshe_flyer/listing', 'token=' . $this->session->data['token'], 'SSL');

		$data_filter = array(
			'status' => 1
		);

		$this->data['flyer_category_data'] = $this->model_moshe_flyer->getFlyerAllCategories($data_filter);

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['flyer_description'])) {
			$this->data['flyer_description'] = $this->request->post['flyer_description'];
		} elseif (isset($this->request->get['flyer_id'])) {
			$this->data['flyer_description'] = $this->model_moshe_flyer->getFlyerDescriptions($this->request->get['flyer_id']);
		} else {
			$this->data['flyer_description'] = array();
		}
		$this->load->model('tool/image');

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['flyer_store'])) {
			$this->data['flyer_store'] = $this->request->post['flyer_store'];
		} elseif (isset($this->request->get['flyer_id'])) {
			$this->data['flyer_store'] = $this->model_moshe_flyer->getStoreByFlyer($this->request->get['flyer_id']);
		} else {
			$this->data['flyer_store'] = array(0);
		}

		if (isset($this->request->get['flyer_id'])){// && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$flyer_info = $this->model_moshe_flyer->getFlyer($this->request->get['flyer_id']);
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($flyer_info)) {
			$this->data['keyword'] = $flyer_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->error['pdf_filename'])) {
			$this->data['error_pdf_filename'] = $this->error['error_pdf_filename'];
		} else {
			$this->data['error_pdf_filename'] = '';
		}

		if (isset($this->error['error_flyer_start_time'])) {
			$this->data['error_flyer_start_time'] = $this->error['error_flyer_start_time'];
		} else {
			$this->data['error_flyer_start_time'] = '';
		}
		if (isset($this->error['error_flyer_end_time'])) {
			$this->data['error_flyer_end_time'] = $this->error['error_flyer_end_time'];
		} else {
			$this->data['error_flyer_end_time'] = '';
		}

		if (isset($this->error['mask'])) {
			$this->data['error_mask'] = $this->error['mask'];
		} else {
			$this->data['error_mask'] = '';
		}
		if (isset($this->request->post['flyer_start_time'])) {
			$this->data['flyer_start_time'] = $this->request->post['flyer_start_time'];
		} elseif (isset($flyer_info)) {
			$this->data['flyer_start_time'] = $flyer_info['flyer_start_time'];
		} else {
			$this->data['flyer_start_time'] = date("Y-m-d");
		}
		if (isset($this->request->post['flyer_end_time'])) {
			$this->data['flyer_end_time'] = $this->request->post['flyer_end_time'];
		} elseif (isset($flyer_info)) {
			$this->data['flyer_end_time'] = $flyer_info['flyer_end_time'];
		} else {
			$this->data['flyer_end_time'] = date("Y-m-d");
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($flyer_info)) {
			$this->data['status'] = $flyer_info['status'];
		} else {
			$this->data['status'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($flyer_info)) {
			$this->data['status'] = $flyer_info['status'];
		} else {
			$this->data['status'] = '';
		}

		//top show value
		if (isset($this->request->post['top_show'])) {
			$this->data['top_show'] = $this->request->post['top_show'];
		} elseif (isset($flyer_info['top_show'])) {
			$this->data['top_show'] = $flyer_info['top_show'];
		} else {
			$this->data['top_show'] = '0';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($flyer_info)) {
			$this->data['image'] = $flyer_info['image'];
		} else {
			$this->data['image'] = '';
		}
		if (isset($this->request->post['image_thumbnail'])) {
			$this->data['image_thumbnail'] = $this->request->post['image_thumbnail'];
		} elseif (isset($flyer_info)) {
			$this->data['image_thumbnail'] = $flyer_info['image_thumbnail'];
		} else {
			$this->data['image_thumbnail'] = '';
		}

		$this->load->model('tool/image');

		if (isset($flyer_info) && $flyer_info['image'] && file_exists(DIR_IMAGE . $flyer_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($flyer_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (isset($flyer_info) && $flyer_info['image_thumbnail'] && file_exists(DIR_IMAGE . $flyer_info['image_thumbnail'])) {
			$this->data['image_thumbnail_preview'] = $this->model_tool_image->resize($flyer_info['image_thumbnail'], 100, 100);
		} else {
			$this->data['image_thumbnail_preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		//flyer_pdf_file
		if (isset($this->request->post['flyer_pdf_file'])) {
			$this->data['flyer_pdf_file'] = $this->request->post['flyer_pdf_file'];
		} elseif (isset($flyer_info)) {
			$this->data['flyer_pdf_file'] = $flyer_info['flyer_pdf_file'];
		} else {
			$this->data['flyer_pdf_file'] = '';
		}

		if (isset($this->request->post['mask'])) {
			$this->data['mask'] = $this->request->post['mask'];
		} elseif (!empty($flyer_info)) {
			$this->data['mask'] = $flyer_info['mask'];
		} else {
			$this->data['mask'] = '';
		}

		// Images
		if (isset($this->request->post['flyer_image'])) {
			$flyer_images = $this->request->post['flyer_image'];
		} elseif (isset($this->request->get['flyer_id'])) {
			$flyer_images = $this->model_moshe_flyer->getFlyerImages($this->request->get['flyer_id']);
		} else {
			$flyer_images = array();
		}

		$this->data['flyer_images'] = array();

		foreach ($flyer_images as $flyer_image) {
			if ($flyer_image['image'] && file_exists(DIR_IMAGE . $flyer_image['image'])) {
				$image = $flyer_image['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$this->data['flyer_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $flyer_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);



		$this->template = 'module/moshe_flyer/form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	//validate flyer module setup information.
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/moshe_flyer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['moshe_flyer_module'])) {
			foreach ($this->request->post['moshe_flyer_module'] as $key => $value) {
				if (!$value['numchars']) {
					$this->error['module_chars'][$key] = $this->language->get('error_num_chars');
				}
			}
		}

		if (!$this->request->post['flyer_thumb_width'] || !$this->request->post['flyer_thumb_height']) {
			$this->error['flyerpage_thumb'] = $this->language->get('error_flyerpage_thumb');
		}

		if (!$this->request->post['flyer_normal_image_width'] || !$this->request->post['flyer_normal_image_height']) {
			$this->error['flyerpage_normal_image'] = $this->language->get('error_flyerpage_normal_image');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//validate submit flyer form field information.
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/moshe_flyer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['flyer_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			// No neccesory to check the description or Start time. but make sure the end time is great than
			//start time.
/*			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}*/
		}

		// check out the start time and end time. also compare the start time and endtime, make
		// sure end time great than start time.
/*		if (!isset($this->request->post['flyer_start_time'])
			|| strtotime($this->request->post['flyer_start_time']) < mktime(0,0,0,date('n'),date('j'),date('Y'))) {
			$this->error['error_flyer_start_time'] = $this->language->get('error_flyer_start_time');
		}*/

/*		$forcheckEndTime = time();
		if(!isset($this->error['error_flyer_start_time']) ) {
			$forcheckEndTime = $forcheckEndTime > strtotime($this->request->post['flyer_end_time']) ? $forcheckEndTime : strtotime($this->request->post['flyer_end_time']);
		}*/
		if ((utf8_strlen($this->request->post['flyer_pdf_file']) < 3) || (utf8_strlen($this->request->post['flyer_pdf_file']) > 128)) {
			$this->error['error_pdf_filename'] = $this->language->get('error_filename');
		}

		if (isset($this->request->post['moshe_flyer_file_directory'])) {
			$flyer_upload_directory = $this->request->post['moshe_flyer_file_directory'];
		} else if($this->config->get('moshe_flyer_file_directory')){
			$flyer_upload_directory = $this->config->get('moshe_flyer_file_directory');
		} else{
			$flyer_upload_directory = 'moshe_flyer';
		}
		$flyer_upload_directory .="/";

		$flyerDownload = DIR_DOWNLOAD .$flyer_upload_directory ."/";

		if (!file_exists($flyerDownload . $this->request->post['flyer_pdf_file']) && !is_file($flyerDownload . $this->request->post['flyer_pdf_file'])) {
			$this->error['error_pdf_filename'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['mask']) < 3) || (utf8_strlen($this->request->post['mask']) > 128)) {
			$this->error['mask'] = $this->language->get('error_mask');
		}

		if (isset($this->request->post['flyer_end_time']))	{
			if(strtotime($this->request->post['flyer_end_time']) < strtotime($this->request->post['flyer_start_time']) ){
				$this->error['error_flyer_end_time'] = sprintf($this->language->get('error_flyer_gt_now'),$this->request->post['flyer_start_time']);
			}
		}
		else{
			$this->error['error_flyer_end_time'] = $this->language->get('error_flyer_end_time');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//validate Category form.
	private function validateFormCategory() {
		if (!$this->user->hasPermission('modify', 'module/moshe_flyer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['flyer_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/moshe_flyer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	// UPload the file to system, this will be combine with Ajax uploadAjax.js on View page.
	public function upload() {
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');
		$json = array();

		if (!$this->user->hasPermission('modify', 'module/moshe_flyer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (!empty($this->request->files['file']['name'])) {
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}
				// Allowed file extension types
				$allowed = array();

				$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Allowed file mime types
				$allowed = array();

				$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload') . $this->request->files['file']['name'];
			}
		}
		if (isset($this->request->post['moshe_flyer_file_directory'])) {
			$this->data['moshe_flyer_file_directory'] = $this->request->post['moshe_flyer_file_directory'];
		} else if($this->config->get('moshe_flyer_file_directory')){
			$this->data['moshe_flyer_file_directory'] = $this->config->get('moshe_flyer_file_directory');
		} else{
			$this->data['moshe_flyer_file_directory'] = 'moshe_flyer';
		}


		if (!isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$ext = md5(mt_rand());

				$json['filename'] = $filename . '.' . $ext;
				$json['mask'] = $filename;

				$pdfFile = DIR_DOWNLOAD . $this->data['moshe_flyer_file_directory'] ."/" .$filename. '.' . $ext;

				move_uploaded_file($this->request->files['file']['tmp_name'],   $pdfFile );
			}

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->setOutput(json_encode($json));
	}

	// delete the upload files.
	function removeFile(){
		$this->load->language('module/moshe_flyer');
		$this->load->model('moshe/flyer');
		$json = array();

		if (!$this->user->hasPermission('modify', 'module/moshe_flyer')) {
			$json['error'] = $this->language->get('error_permission');
		}
		if (!isset($json['error'])) {
			if($this->request->post['filename']){
				if (isset($this->request->post['moshe_flyer_file_directory'])) {
					$this->data['moshe_flyer_file_directory'] = $this->request->post['moshe_flyer_file_directory'];
				} else if($this->config->get('moshe_flyer_file_directory')){
					$this->data['moshe_flyer_file_directory'] = $this->config->get('moshe_flyer_file_directory');
				} else{
					$this->data['moshe_flyer_file_directory'] = 'moshe_flyer';
				}

				$flyer_upload_directory = $this->data['moshe_flyer_file_directory'] ."/";

				$file = DIR_DOWNLOAD .$flyer_upload_directory. $this->request->post['filename'];

				if(file_exists( $file)){
					$tmp = dirname(__FILE__);
					if (strpos($tmp, '/', 0)!==false) {
						define('WINDOWS_SERVER', false);
					} else {
						define('WINDOWS_SERVER', true);
					}
					$deleteError = 0;
					if (!WINDOWS_SERVER) {
						if(!unlink($file)){
							$deleteError = 1;
						}
					} else {
						//chage / directory separator to normal separator of windows
						$file = preg_replace('/\//','\\',$file);

						system("DEL /F/Q \"$file\"", $deleteError);
					}
					if($deleteError){
						$json['error'] = $this->language->get('error_delete') .$file;
					}else{
						$json['success'] = $this->language->get('text_delete');
					}
				}
				else{
					$json['error'] = $this->language->get('error_nofile') ;
				}
			}
			else{
				$json['error'] = $this->language->get('error_nofilepick');
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>