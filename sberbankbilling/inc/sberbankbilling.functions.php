<?php defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('sberbankbilling', 'plug');

/*
 * КОНФИГУРАЦИЯ ПЛАТЕЖНОГО ПЛАГИНА
 */
define('SBERBANK_PROD_URL', 'https://securepayments.sberbank.ru/payment/rest/'); // URL Боя
define('SBERBANK_TEST_URL', 'https://3dsec.sberbank.ru/payment/rest/'); // URL Теста

function cot_sberbankbilling_send($method = '', $data = array(), $url = '') {
	$curl = curl_init();
	curl_setopt_array($curl, array(
	    CURLOPT_URL => $url.$method,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => http_build_query($data)
	));
	$response = curl_exec($curl);
	$response = json_decode($response, true);

	curl_close($curl);
	return $response;
}

function cot_sberbankbilling_payment($orderId = 0, $pinfo = array()) {
  global $cfg;

	$data = array(
		'userName' => $cfg['plugin']['sberbankbilling']['sbrlogin'],
		'password' => $cfg['plugin']['sberbankbilling']['sbrpass'],
		'orderNumber' => $orderId,
		'amount' => round($pinfo['pay_summ'] * 100),
		'returnUrl' => COT_ABSOLUTE_URL . cot_url('plug', 'e=sberbankbilling&m=success&pid='.$orderId, '', true),
    'failUrl' => COT_ABSOLUTE_URL . cot_url('plug', 'e=sberbankbilling&m=fail&pid='.$orderId, '', true),
    'pageView' => (cot_sberbankbilling_pageView_mobile() ? 'MOBILE' : 'DESKTOP')
	);

  if($cfg['plugin']['sberbankbilling']['sbrbindings']) {
    $data['clientId'] = $pinfo['pay_userid'];
  }

	if ($cfg['plugin']['sberbankbilling']['sbrmode'] == 1) {
    $url = SBERBANK_TEST_URL;
  } else {
    $url = SBERBANK_PROD_URL;
  }

	if ($cfg['plugin']['sberbankbilling']['sbrstage'] == 1) {
    $method = 'register.do';
  } else {
    $method = 'registerPreAuth.do';
  }
	$response = cot_sberbankbilling_send($method, $data, $url);

  return $response;
}

function cot_sberbankbilling_payment_bindings($orderId = '', $eData = array()) {
  global $cfg;

	$data = array(
		'userName' => $cfg['plugin']['sberbankbilling']['sbrlogin'],
		'password' => $cfg['plugin']['sberbankbilling']['sbrpass'],
		'mdOrder' => $orderId,
    'bindingId' => $eData['bindingId'],
    'cvc' => $eData['cvc'],
    'email' => $eData['email'],
    'ip' => $eData['ip'],
    'pageView' => (cot_sberbankbilling_pageView_mobile() ? 'MOBILE' : 'DESKTOP')
	);

	if ($cfg['plugin']['sberbankbilling']['sbrmode'] == 1) {
    $url = SBERBANK_TEST_URL;
  } else {
    $url = SBERBANK_PROD_URL;
  }

	$method = 'paymentOrderBinding.do';

	$response = cot_sberbankbilling_send($method, $data, $url);

  return $response;
}

function cot_sberbankbilling_check($orderNumber = 0, $orderId = '', $clientId = 0) {
  global $cfg, $db, $db_users;

	$data = array(
		'userName' => $cfg['plugin']['sberbankbilling']['sbrlogin'],
		'password' => $cfg['plugin']['sberbankbilling']['sbrpass'],
    'orderId' => $orderId,
    'orderNumber' => $orderNumber
	);

	if ($cfg['plugin']['sberbankbilling']['sbrmode'] == 1) {
    $url = SBERBANK_TEST_URL;
  } else {
    $url = SBERBANK_PROD_URL;
  }

  $return = false;

	$response = cot_sberbankbilling_send('getOrderStatusExtended.do', $data, $url);

	if (($response['orderStatus'] == 1 || $response['orderStatus'] == 2) && $response['errorCode'] == 0) {
    $return = true;

    if($clientId > 0 && is_array($response['bindingInfo']) && !empty($response['bindingInfo']['bindingId'])) {
      $urrsbrbindings = $db->query("SELECT user_sberbank_binding FROM $db_users WHERE user_id=".$clientId)->fetchColumn();
      $urrsbrbindings = (!empty($urrsbrbindings) ? json_decode($urrsbrbindings, 1) : array());
      if(!is_array($urrsbrbindings)) $urrsbrbindings = array();

      $urrsbrbindings[$response['bindingInfo']['bindingId']] = (!empty($response['cardAuthInfo']['pan']) ? $response['cardAuthInfo']['pan'] : $response['cardAuthInfo']['maskedPan']);

      $db->update($db_users, array('user_sberbank_binding' => json_encode($urrsbrbindings)), 'user_id='.$clientId);
    }
  }

  return $return;
}

function cot_sberbankbilling_pageView_mobile(){
	if(stristr($_SERVER['HTTP_USER_AGENT'],'windows')&&!stristr($_SERVER['HTTP_USER_AGENT'],'windows ce')){
		return false;
	}

	if(preg_match('/(up.browser|up.link|windows ce|iemobile|mini|mmp|symbian|midp|wap|phone|pocket|mobile|pda|psp)/i',$_SERVER['HTTP_USER_AGENT'])){
		return true;
	}

	if(stristr($_SERVER['HTTP_ACCEPT'],'text/vnd.wap.wml')||stristr($_SERVER['HTTP_ACCEPT'],'application/vnd.wap.xhtml+xml')){
		return true;
	}

	if(isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])||isset($_SERVER['X-OperaMini-Features'])||isset($_SERVER['UA-pixels'])){
		return true;
	}

	$a = array('acs-','alav','alca','amoi','audi','aste','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki','opwv','palm','pana','pant','pdxg','phil','play','pluc','port','prox','qtek','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','w3c ','wap-','wapa','wapi','wapp','wapr','webc','winw','winw','xda','xda-');

	if(isset($a[substr($_SERVER['HTTP_USER_AGENT'],0,4)])){
		return true;
	}
}