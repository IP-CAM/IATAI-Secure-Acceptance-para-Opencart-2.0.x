<?php

class ControllerPaymentIatai extends Controller {
	private $error = array(); 

    /**
    * Función que inicializa la configuración del módulo
    * del gateway de IATAI
    */
	public function index() {
		$this->load->language('payment/iatai');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		$this->document->addStyle('view/stylesheet/iatai.css');
        $token = 'token=' . $this->session->data['token'];
        
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('iatai', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/payment', $token, 'SSL'));
		}
		$data['default_liveurl'] = 'https://secureacceptance.allegraplatform.com/CI_Secure_Acceptance/Payment';
		$data['default_testurl'] = 'https://test.secureacceptance.allegraplatform.com/CI_Secure_Acceptance/Payment';
		$data['default_testprofileid'] = 'testprofileid';
		$data['default_testaccesskey'] = 'testaccesskey';
		$data['default_testsecretkey'] = 'testsecretkey';

		$arr = array( 
				"heading_title", "text_payment", "text_success",  "text_pay", 
				"text_card", "entry_profileid", "help_profileid", "entry_accesskey", 
				"help_accesskey", "entry_secretkey", "help_secretkey", "entry_liveurl",
				"help_liveurl", "default_liveurl", "entry_confirmurl", "help_confirmurl",
				"entry_confirmuserpass", "help_confirmuserpass", "default_confirmurl", 
				"entry_testprofileid", "help_testprofileid", "entry_testaccesskey", 
				"help_testaccesskey", "entry_testsecretkey", "help_testsecretkey", 
				"entry_testurl", "help_testurl", "default_testurl", "entry_testconfirmurl",
				"entry_testconfirmuserpass", "help_testconfirmurl", "default_testconfirmurl", 
				"default_testconfirmuser", "default_testconfirmpass", "text_test", 
				"help_test", "text_debug", "entry_test", "entry_order_status_accepted",
				"help_order_status_accepted", "entry_order_status_pending", "help_order_status_pending",
				"entry_order_status_declined", "help_order_status_declined", "entry_order_status_canceled",
				"help_order_status_canceled", "entry_order_status_failed", "help_order_status_failed",
				"entry_order_status_expired", "help_order_status_expired", "entry_language", 
				"entry_status", "help_status", "entry_sort_order", "help_sort_order", 
				"error_permission", "error_profileid", "error_accesskey", "error_secretkey",
				"error_liveurl", "error_confirmurl", "entry_test_on", "entry_test_off",
                "error_confirmuserpass");

		foreach ($arr as $v) $data[$v] = $this->language->get($v);
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

        $arr = array("warning", "profile", "secretkey", "type");
        foreach ( $arr as $v ) $data['error_'.$v] = ( isset($this->error[$v]) ) ? $this->error[$v] : "";

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/iatai', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('payment/iatai', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/order_status');
		
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['currencys'] = array( 'ARS', 'BRL', 'COP', 'MXN', 'PEN', 'USD' );
		$data['languages'] = array('es-CO', 'en-US');
		
		$arr = array( 
			"iatai_profileid", "iatai_accesskey", "iatai_secretkey", 
			"iatai_liveurl", "iatai_confirmurl", "iatai_confirmuser", 
			"iatai_confirmpass", "iatai_testprofileid", "iatai_testaccesskey", 
			"iatai_testsecretkey", "iatai_testurl", "iatai_testconfirmurl", 
			"iatai_testconfirmuser", "iatai_testconfirmpass", "iatai_test", 
			"iatai_language", "iatai_status", "iatai_sort_order", 
			"iatai_order_status_accepted", "iatai_order_status_pending",
			"iatai_order_status_declined", "iatai_order_status_canceled",
			"iatai_order_status_failed", "iatai_order_status_expired" );

		foreach ( $arr as $v )
		{
			$data[$v] = ( isset($this->request->post[$v]) ) ? $this->request->post[$v] : $this->config->get($v);
		}
        $data['status_list'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->template = 'payment/iatai.tpl';
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $data['column_left'] = $this->load->controller('common/column_left');
				
        $this->response->setOutput($this->load->view($this->template, $data));
	}


    /**
    * Valida y carga los errores de acuerdo a los parámetros recibidos
    */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/iatai')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ($this->request->post['iatai_test']=='Off') {
			if (!$this->request->post['iatai_profileid']) {
				$this->error['profileid'] = $this->language->get('error_profileid');
			}
			if (!$this->request->post['iatai_secretkey']) {
				$this->error['secretkey'] = $this->language->get('error_secretkey');
			}
			if (!$this->request->post['iatai_liveurl']||!$this->request->post['iatai_testurl']) {
				$this->error['liveurl'] = $this->language->get('error_liveurl');
			}
		
		}

		return (!$this->error) ? true : false ;
	}
}
?>