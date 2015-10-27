<?php 
class ModelPaymentIatai extends Model {
    
    /**
    * Construye los datos del método de pago IATAI
    *
    */
  	public function getMethod($address, $total) {
		$this->load->language('payment/iatai');
		
		$currencies = array(
			'ARS', 'BRL', 'COP', 'MXN', 'PEN', 'USD'
		);
		
		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}else{
			$status = true;
		}		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'iatai',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('iatai_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>