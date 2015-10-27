<?php
// Heading
$_['heading_title']    = 'IATAI Secure Acceptance';

// Text 
$_['text_payment']     = 'Pagos';
$_['text_iatai']       = '<a onclick="window.open(\'http://www.iatai.com/\');"><img src="view/image/payment/iatai.png" alt="IATAI Secure Acceptance" title="IATAI" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_success']     = 'Configuración actualizada';   
$_['text_pay']         = 'IATAI Secure Acceptance';
$_['text_card']        = 'Pagos en Línea';

// Entry
$_['entry_profileid']  = 'Profile ID:';
$_['help_profileid']   = 'Id de su perfil suministrado por IATAI.';
$_['entry_accesskey']  = 'Access Key:';
$_['help_accesskey']   = 'Access Key generado por IATAI.';
$_['entry_secretkey']  = 'Secret Key:';
$_['help_secretkey']   = 'Secret Key generado por IATAI.';
$_['entry_liveurl']    = 'Url de Producción:';
$_['help_liveurl']     = 'Url para realizar transacciones en producción';
$_['entry_testprofileid']  = 'Pruebas - Profile ID:';
$_['help_testprofileid']  = 'Id de su perfil de pruebas suministrado por IATAI.';
$_['entry_testsecretkey']  = 'Pruebas - Secret Key:';
$_['help_testsecretkey']  = 'Access Key generado por IATAI para pruebas.';
$_['entry_testaccesskey']  = 'Pruebas - Access Key:';
$_['help_testaccesskey']  = 'Secret Key generado por IATAI para pruebas.';
$_['entry_testurl']	= 'Url de Pruebas:';
$_['help_testurl']	= 'Url para realizar transacciones en pruebas.';

$_['default_liveurl'] = 'https://secureacceptance.allegraplatform.com/CI_Secure_Acceptance/Payment';
$_['default_testurl'] = 'https://test.secureacceptance.allegraplatform.com/CI_Secure_Acceptance/Payment';
$_['default_testprofileid'] = 'ci_gvdt';
$_['default_testaccesskey'] = 'cba48ce64b093b5fa8c65181eb5dcc7c';
$_['default_testsecretkey'] = '24d2d873eb06ab744b4986635df84c3e4d57b91aa626daf454a9cb4992a70560d721179ea24d9eb4271a799dbd9b2282181994c4c74041a4bb190a5dd11a5a10517c5086eafe193468f8461000c687110d4';

$_['entry_confirmurl']	= 'WSDL Web Service Url:';
$_['help_confirmurl']	= 'URL usada para verificar el estado de una transacción';
$_['entry_testconfirmurl']  = 'Pruebas - WSDL Web Service Url:';
$_['entry_confirmuserpass']  = 'Usuario y Contraseña para el WSDL Web Service';
$_['help_testconfirmurl']  = 'URL usada para verificar el estado de una transacción en modo de pruebas';
$_['help_confirmuserpass']  = 'Usuario y Contraseña suministrados por Iatai';
$_['default_confirmurl'] = 'https://secure.allegraplatform.com/GatewayIatai/IPPG?WSDL';
$_['default_testconfirmurl'] = 'http://pruebas.allegraplatform.com/GatewayIatai/IPPG?wsdl';
$_['default_testconfirmuser'] = 'cert';
$_['default_testconfirmpass'] = 'cert';

$_['entry_test']        = 'Ambiente:';
$_['help_test']        = 'Use esta opción para cambiar entre ambientes de pruebas y producción.';
$_['entry_test_on']     = 'Pruebas';
$_['entry_test_off']    = 'Producción';

$_['entry_order_status_accepted'] = 'Estado de la orden después de un pago aceptado.:';
$_['help_order_status_accepted'] = 'Este es el estado que la orden obtiene cuando la transacción queda: ACCEPTED.';
$_['entry_order_status_pending'] = 'Estado de la orden cuando el pago queda pendiente:';
$_['help_order_status_pending'] = 'Este es el estado que la orden obtiene cuando la transacción queda: PENDING.';
$_['entry_order_status_declined'] = 'Estado de la orden después de un pago declinado:';
$_['help_order_status_declined'] = 'Este es el estado que la orden obtiene cuando la transacción queda: DECLINED.';
$_['entry_order_status_canceled'] = 'Estado de la orden después de un pago cancelado:';
$_['help_order_status_canceled'] = 'Este es el estado que la orden obtiene cuando la transacción queda: CANCELED.';
$_['entry_order_status_failed'] = 'Estado de la orden después de un pago fallido:';
$_['help_order_status_failed'] = 'Este es el estado que la orden obtiene cuando la transacción queda: FAILED.';

$_['entry_currency']     = 'Moneda : ';
$_['entry_language']     = 'Lenguaje de la Plataforma de Pago:<br /><span class="help">default : ES </span>';

$_['entry_status']       = 'Estado del Módulo:';
$_['help_status']       = 'Use esta opción para habilitar/inhabilitar el método de pago.';
$_['entry_sort_order']   = 'Orden de Aparición:';
$_['help_sort_order']   = 'Use esta opción para ordenar este método de pago, con respecto a otros en su tienda.';

// Error
$_['error_permission']  = "Usted no tiene permiso para ejecutar esta acción!";
$_['error_profileid']   = 'Profile ID está vacío!';
$_['error_accesskey']   = 'Access key está vacío!';
$_['error_secretkey']   = 'Secret key está vacío!';
$_['error_liveurl']		= 'Url No puede estar vacía!';
$_['error_confirmurl']  = 'Url No puede estar vacía!';
$_['error_confirmuserpass']  = 'Usuario y Contraseña No pueden estar vacíos';
