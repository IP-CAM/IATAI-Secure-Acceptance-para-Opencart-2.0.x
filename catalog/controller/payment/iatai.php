<?php
	
class ControllerPaymentIatai extends Controller {
	
    /**
    * Función que inicializa el controlador y ajusta el formulario
    * para el submit hacia el gateway de pagos
    */
    
    public function index() {
        $logger = new Log('iatai_index.log');
		$t=time();
        $logger->write('----------------------IATAI_OPENCART_V2_INDEX-----------------------');
		$logger->write('Solicitud recibida:' . $t );
        $logger->write('Se inicia la confirmación del pago en IATAI .');
		$this->language->load('payment/iatai');
		$data['text_test'] = $this->language->get('text_testmode');		
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['testmode'] = $this->config->get('iatai_test');
        $logger->write('Modo prueba: ' . $data['testmode'] );
		if (!$this->config->get('iatai_test')) {
    		$data['action'] = $this->config->get('iatai_liveurl');
  		} else {
			$data['action'] = $this->config->get('iatai_testurl');
		}
		$this->load->model('checkout/order');
        
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        // si está en modo pruebas, carga los valores de configuración de pruebas
		if ($order_info) {
			if ($this->config->get('iatai_test')) {
				$data['profile_id'] = $this->config->get('iatai_testprofileid');
				$data['access_key'] = $this->config->get('iatai_testaccesskey');
				$data['secret_key'] = $this->config->get('iatai_testsecretkey');
			} else {
				$data['profile_id'] = $this->config->get('iatai_profileid');
				$data['access_key'] = $this->config->get('iatai_accesskey');
				$data['secret_key'] = $this->config->get('iatai_secretkey');
			}

			$data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
			$data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
					'quantity' => $product['quantity'],
					'option'   => $option_data,
					'weight'   => $product['weight']
				);
			}

			$data['discount'] = 0;
			$total = $this->currency->format($order_info['total'] , $order_info['currency_code'], $order_info['currency_value'], false);
			$data['amount'] = $total;
            $logger->write('Total: ' . $data['amount'] );

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/iatai.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/iatai.tpl';
			} else {
				$this->template = 'default/template/payment/iatai.tpl';
			}
            $logger->write('Plantilla cargada: ' . $this->template);

			$data['currency'] = $order_info['currency_code'];
			$data['firstname'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$data['lastname'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
			$data['billingCity'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
			$data['zipCode'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
			$data['billingCountry'] = $order_info['payment_iso_code_2'];
			$data['buyerEmail'] = $order_info['email'];
			$data['phone'] = $order_info['telephone'];
			$data['locale'] = $this->session->data['language'];
			$data['transaction_type'] = 'authorization';
			$data['reference_number'] = 'Order '.$this->session->data['order_id'].' '.strtotime($order_info['date_added']);
			$data['transaction_uuid'] = base64_encode('Order '.$this->session->data['order_id'].' '.strtotime($order_info['date_added']));
			$data['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z",strtotime($order_info['date_added']));
			$data['signed_field_names'] = 'access_key,profile_id,reference_number,amount,currency,locale,transaction_type,transaction_uuid,signed_date_time,unsigned_field_names';
			$data['unsigned_field_names'] = 'phone,order_id,_opencart,order_desc';
			$data['description'] = $this->config->get('config_name').' - '.$this->session->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['order_id'] = $this->session->data['order_id'];
			$data['_opencart'] = md5($this->sign($data,$data['secret_key']).$this->session->data['order_id']);
			$data['order_desc'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');	

			$data['signature'] = $this->sign($data,$data['secret_key']);

			if ($this->config->get('iatai_test')) {
				$logger->write('iatai :: Transaction test data OK! '.$data['signature'].' ' . ($this->session->data['order_id']));
			}
			$logger->write('Renderizando datos del formulario');
            $print = array('action','testmode','profile_id',
                               'item_name','amount','currency',
                               'buyerEmail','phone','transaction_type',
                               'reference_number','transaction_uuid','signed_date_time',
                               'signed_field_names','description','order_id','signature');
            foreach ($data as $clave => $valor) {
                if (in_array($clave,$print)) {
                    $logger->write('['. $clave .  ']:' . $valor);
                }
            }
            return $this->load->view($this->template, $data);
		}
	}
    

    /**
    * Función que realiza la actualización del estado de la orden en opencart
    *
    */
	public function confirmation() {
        $logger = new Log('iatai_confirmation.log');
		$t=time();
        $logger->write('----------------------IATAI_OPENCART_V2_CONF-----------------------');
		$logger->write('Solicitud recibida:' . $t );
        $logger->write('Se inicia la confirmación del pago en IATAI .');
        
		if ($this->config->get('iatai_test')) {
			$this->data['profile_id'] = $this->config->get('iatai_testprofileid');
			$this->data['access_key'] = $this->config->get('iatai_testaccesskey');
			$this->data['secret_key'] = $this->config->get('iatai_testsecretkey');
			$this->data['verification_url'] = 'http://pruebas.allegraplatform.com/GatewayIatai/IPPG?wsdl';
		} else {				
			$this->data['profile_id'] = $this->config->get('iatai_profileid');
			$this->data['access_key'] = $this->config->get('iatai_accesskey');
			$this->data['secret_key'] = $this->config->get('iatai_secretkey');
			$this->data['verification_url'] = 'https://secure.allegraplatform.com/GatewayIatai/IPPG?WSDL';
		}

		$order_status_id='';
		if (isset($this->request->post['req_reference_number'])) {
			if (isset($this->request->post['req_reference_number'])) {
				$order_number = explode(' ', $this->request->post['req_reference_number']);
				$order_id = $order_number[1];
			} else {
				$order_id = 0;
			}

            // se cargar el modelo de la orden
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			if ($order_info){
				$this->data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
				$this->data['products'] = array();

				foreach ($this->cart->getProducts() as $product) {
					$option_data = array();

					foreach ($product['option'] as $option) {
						if ($option['type'] != 'file') {
							$value = $option['option_value'];
						} else {
							$filename = $this->encryption->decrypt($option['option_value']);
							$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}

					$this->data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
						'quantity' => $product['quantity'],
						'option'   => $option_data,
						'weight'   => $product['weight']
					);
				}	
				
				$this->data['discount'] = 0;
				$total = $this->currency->format($order_info['total'] , $order_info['currency_code'], $order_info['currency_value'], false);
				$this->data['amount'] = $total;
				$this->data['currency'] = $order_info['currency_code'];
				$this->data['firstname'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');	
				$this->data['lastname'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');	
				$this->data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
				$this->data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
				$this->data['billingCity'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');	
				$this->data['zipCode'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');	
				$this->data['billingCountry'] = $order_info['payment_iso_code_2'];
				$this->data['buyerEmail'] = $order_info['email'];
				$this->data['phone'] = $order_info['telephone'];
				$this->data['locale'] = $this->session->data['language'];
				$this->data['transaction_type'] = 'authorization';
				$this->data['reference_number'] = 'Order '.$order_id.' '.strtotime($order_info['date_added']);
				$this->data['transaction_uuid'] = base64_encode('Order '.$order_id.' '.strtotime($order_info['date_added']));
				$this->data['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z",strtotime($order_info['date_added']));
				$this->data['signed_field_names'] = 'access_key,profile_id,reference_number,amount,currency,locale,transaction_type,transaction_uuid,signed_date_time,unsigned_field_names';
				$this->data['unsigned_field_names'] = 'phone,order_id,_opencart,order_desc';
				$this->data['description'] = $this->config->get('config_name').' - '.$order_id . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
				$this->data['order_id'] = $order_id;
				$this->data['_opencart'] = md5($this->sign($this->data,$this->data['secret_key']).$order_id);
				$this->data['order_desc'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');	
				$signature = $this->sign($this->data,$this->data['secret_key']);
				$hashstr=$signature;
			}
			$logger->write('Número de orden: '. $order_id . ' Firma: ' . $hashstr);			
			if(isset($this->request->post['transaction_id'])){
				$checkPayment = $this->checkPayment($this->request->post['transaction_id'],$this->request->post['req_reference_number']);
				$verify=$checkPayment->respuestaConsultarTransaccion;
			}
			if ($order_info) {
				$state=$this->request->post['reason_code'];
				if (isset($this->request->post['decision'])) {
					$order_status_id = $this->config->get('iatai_order_status_pending');
					switch($this->request->post['decision']) {
						case 'ACCEPT' :
                            //la transacción es en modo prueba
							if ($this->config->get('iatai_test')) {
                                $logger->write('Transacción en modo prueba: ' . $this->config->get('iatai_order_status_accepted'));
								$order_status_id = $this->config->get('iatai_order_status_accepted');
								$logger->write('Datos de la transacción: autorización->'.$this->request->post['auth_code'].' estado->'.$state.' ' . ($order_id));
							}
                            // la transacción es en ambiente productivo
                            else {
                                //verificación del estado, firma, valor y moneda
								if (in_array($state, array('100','110'))&&($signature==$this->request->post['req_merchant_secure_data3']) && ((float)$this->request->post['req_amount'] == $this->currency->format($order_info['total'] , $order_info['currency_code'], $order_info['currency_value'], false))) {
									$verify->mensaje=='ACCEPT'&&($verify->codigoRespuesta==110||$verify->codigoRespuesta==100);
									$order_status_id = $this->config->get('iatai_order_status_accepted');
									$logger->write('Datos de la transacción:  order_id ->'.$order_id.' estado->'.$state.' ' . $hashstr);
									$status_message = 'Datos de la transacción: estado->'.$state.' WSDL Web Service: Message ' . $verify->mensaje . ', Código Respuesta: '.$verify->codigoRespuesta.' , Código Autorización: '.$verify->codigoAutorizacion;
								} else {
									$logger->write('Error en la validación de los parámetros recibidos y la consulta WS! '.' Estado: '.$state . ($hashstr));
									//$status_message = 'iatai :: Transaction data MISMATCH! '.' Estado: '.$state . ($hashstr);
									$order_status_id = $this->config->get('iatai_order_status_pending');
								}
							}
							break;
						case 'REVIEW' :
							$order_status_id = $this->config->get('iatai_order_status_pending');
							$logger->write('Datos de la transacción:'.$hashstr.' Estado: '.$state.' ' . ($order_id));
							$status_message = 'Datos de la transacción:'.$hashstr.' Estado: '.$state.' ' .' WSDL Web Service: Message ' . $verify->mensaje . ' Código Autorización: '.$verify->codigoAutorizacion;
							break;
						case 'DECLINE' :
							$order_status_id = $this->config->get('iatai_order_status_declined');
							$logger->write('Datos de la transacción:'.$hashstr.' Estado: '.$state.' ' . ($order_id));
							$status_message = 'Datos de la transacción:'.$hashstr.' Estado: '.$state.' ' .' WSDL Web Service: Message ' . $verify->mensaje . ' Código Autorización: '.$verify->codigoAutorizacion;
							break;
						case 'CANCEL' :
							$order_status_id = $this->config->get('iatai_order_status_canceled');
							$logger->write('Datos de la transacción:'.$hashstr.' Estado: '.$state.' ' . ($order_id));
							$status_message = 'Datos de la transacción:'.$hashstr.' Estado: '.$state.' '.' WSDL Web Service: Message ' . $verify->mensaje . ' Código Autorización: '.$verify->codigoAutorizacion;
							break;
						case 'ERROR' :
							$order_status_id = $this->config->get('iatai_order_status_failed');
							$logger->write('Datos de la transacción:'.$hashstr.' Estado: '.$state.' ' . ($order_id));
							$status_message = 'Datos de la transacción:'.$hashstr.' Estado: '.$state.' ' .' WSDL Web Service: Message ' . $verify->mensaje . ' Código Autorización: '.$verify->codigoAutorizacion;
							break;
						default :
							$order_status_id = $this->config->get('iatai_order_status_failed');
							$logger->write('Tomando estado por defecto '.' Estado: '.$state . ($order_id));
							$status_message = 'Tomando estado por defecto '.' Estado: '.$state . ($order_id);
							break;
					}
					
					if (!$order_info['order_status_id']) {
						if(!in_array($order_status_id, array('7','10'))){
                            $logger->write('Actualizando estados de la orden: $order_id='.$order_id.' order_status_id='.$order_status_id.' status_message='.$status_message);
						$this->model_checkout_order->confirm($order_id, $order_status_id,$status_message);
						}
					} else {
						if(!in_array($order_status_id, array('7','10'))){
							$logger->write('Actualizando estado de la orden: $order_id='.$order_id.' order_status_id='.$order_status_id.' status_message='.$status_message);
							$this->model_checkout_order->update($order_id, $order_status_id,$status_message);
						}
					}

				} else {
					$this->model_checkout_order->confirm($order_id, $this->config->get('iatai_order_status_failed'));
				}

			}
		}
	}

    /**
    * Función para firmar los datos de la transacción
    */
	private function sign ($params,$secret) {
	  return $this->signData($this->buildDataToSign($params), $secret);
	}

    /**
    * Función auxiliar para firmar los datos de la transacción
    */
	private function signData($data, $secretKey) {
	    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
	}

    /**
    * Función que permite separar los campos firmados en un arreglo
    */
	private function buildDataToSign($params) { 
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as &$field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return $this->commaSeparate($dataToSign);
	}

    /**
    * Función para firmar los datos de la transacción
    */
	private function commaSeparate($dataToSign) {
	    return implode(",",$dataToSign);
	}

    /** 
    * Realiza la consulta a través del WS de IATAI
    */
	private function checkPayment($payment_id,$reference){
		$logger = new Log('iatai_confirmation_ws.log');
                $t=time();
                $logger->write('Request at:' . $t );
		if ($this->config->get('iatai_test')) {
			$this->data['profile_id'] = $this->config->get('iatai_testprofileid');
			$this->data['access_key'] = $this->config->get('iatai_testaccesskey');
			$this->data['secret_key'] = $this->config->get('iatai_testsecretkey');
			$this->data['verification_url'] = 'http://pruebas.allegraplatform.com/GatewayIatai/IPPG?wsdl';
			$user = $this->config->get('iatai_testconfirmuser');
			$pass = $this->config->get('iatai_testconfirmpass');
		} else {
			$this->data['profile_id'] = $this->config->get('iatai_profileid');
			$this->data['access_key'] = $this->config->get('iatai_accesskey');
			$this->data['secret_key'] = $this->config->get('iatai_secretkey');
			$this->data['verification_url'] = 'https://secure.allegraplatform.com/GatewayIatai/IPPG?WSDL';
			$user = $this->config->get('iatai_confirmuser');
			$pass = $this->config->get('iatai_confirmpass');
		}
		$logger->write('Haciendo petición WS');
		$soap_client = new soapclient("http://pruebas.allegraplatform.com/GatewayIatai/IPPG?wsdl",array('trace'=> true));
		$header_part = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	        <wsse:UsernameToken>
	            <wsse:Username>'.$user.'</wsse:Username>
	            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$pass.'</wsse:Password>
	        </wsse:UsernameToken>
	    </wsse:Security>';

		$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
		$soap_header = new SoapHeader( 'http://pruebas.allegraplatform.com/GatewayIatai/IPPG?wsdl', 'Security', $soap_var_header );
		$soap_client->__setSoapHeaders($soap_header);

		$envio = array(
		   	'informacionConsulta' =>array(
		   		'idTransaccion' => $payment_id,
		   		'referencia'=> $reference
		   	)
	   	);
		try{
			$result = $soap_client->consultarTransaccion($envio);
			return $result;
		}catch (Exception $e){
            $logger->write("Error al consultar el estdo de la transacción: " .print_r($e));
		}
	}
	
    /**
    * Función que muestra la página de respuesta y carga el template de la web de respuesta
    */
	public function response()
	{
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/iatai_response.tpl')) 
		{
			$this->template = $this->config->get('config_template') . '/template/payment/iatai_response.tpl';
    		} 
		else 
		{
			$this->template = 'template/payment/iatai_response.tpl';
		}
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $data['column_left'] = $this->load->controller('common/column_left');
        
        //población de campos
        $fields = 
        array( 
            array('nombre_campo'=>'Mensaje',
                  'valor_campo'=>(isset($_REQUEST['message']) ? $_REQUEST['message'] : '')),
            array('nombre_campo'=>'Referencia',
                  'valor_campo'=>(isset($_REQUEST['req_reference_number']) ? $_REQUEST['req_reference_number'] : '' )),
            array('nombre_campo'=>'Código de respuesta',
                  'valor_campo'=>(isset($_REQUEST['reason_code']) ? $_REQUEST['reason_code'] : '')),
            array('nombre_campo'=>'Decisión',
                  'valor_campo'=>(isset($_REQUEST['decision']) ? $_REQUEST['decision'] : '')),
            array('nombre_campo'=>'Código de autorización',
                  'valor_campo'=>(isset($_REQUEST['auth_code']) ? $_REQUEST['auth_code'] : '')),
            array('nombre_campo'=>'Método de pago',
                  'valor_campo'=>(isset($_REQUEST['req_payment_method']) ? $_REQUEST['req_payment_method'] : '')),
            array('nombre_campo'=>'Moneda',
                  'valor_campo'=>(isset($_REQUEST['req_currency']) ? $_REQUEST['req_currency'] : '')),
            array('nombre_campo'=>'Valor de la transacción',
                  'valor_campo'=>(isset($_REQUEST['req_amount']) ? $_REQUEST['req_amount'] : '')),
            array('nombre_campo'=>'Id de transacción',
                  'valor_campo'=>(isset($_REQUEST['transaction_id']) ? $_REQUEST['transaction_id'] : '')),
            array('nombre_campo'=>'Email pagador',
                  'valor_campo'=>(isset($_REQUEST['req_bill_to_email']) ? $_REQUEST['req_bill_to_email'] : ''))                         );
        $data['fields'] = $fields;
		$logger = new Log('iatai_response.log');
        $t=time();
        $logger->write('-------------------------------------------------------');
        $logger->write('Solicitud recibida:' . $t );
        $logger->write('Se inicia la URL de respuesta del pago en IATAI .');
        $this->response->setOutput($this->load->view($this->template, $data));
	}
}
?>
