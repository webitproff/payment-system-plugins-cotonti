<?php

defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('payboxmoney', 'plug');

function cot_payboxmoney_get_send_data($url = '', $payid = 0, $summ = 0, $desc = '', $salt = '', $urrip = '', $urremail = '', $urrphone = '') {
  global $cfg;

  $desc = 'test description';

  $return = array(
    'pg_merchant_id' => $cfg['plugin']['payboxmoney']['pg_merchant_id'],
    'pg_order_id' => $payid,
    'pg_amount' => number_format($summ, 2, '.', ''),
    'pg_currency' => $cfg['plugin']['payboxmoney']['pg_currency'],
    'pg_description' => $desc,
    'pg_check_url' => COT_ABSOLUTE_URL . cot_url('plug', 'r=payboxmoney&a=check', '', true),   //URL для проверки возможности платежа
    'pg_result_url' => COT_ABSOLUTE_URL . cot_url('plug', 'r=payboxmoney&a=result', '', true), //URL для сообщения о результате платежа
    'pg_refund_url' => COT_ABSOLUTE_URL . cot_url('plug', 'r=payboxmoney&a=refund', '', true), //URL для сообщения об отмене платежа
    'pg_capture_url' => COT_ABSOLUTE_URL . cot_url('plug', 'r=payboxmoney&a=capture', '', true), //URL для сообщения о проведении клиринга платежа по банковской карте
    'pg_request_method' => $cfg['plugin']['payboxmoney']['pg_state_url_method'],
    'pg_success_url' => COT_ABSOLUTE_URL . cot_url('plug', 'e=payboxmoney&m=success', '', true),
    'pg_failure_url' => COT_ABSOLUTE_URL . cot_url('plug', 'e=payboxmoney&m=fail', '', true),
    'pg_success_url_method' => 'GET',
    'pg_failure_url_method' => 'GET',
    'pg_site_url' => COT_ABSOLUTE_URL . cot_url('index'),
    'pg_user_ip' => $urrip,
    'pg_salt' => $salt
  );
  if(!empty($urrphone)) $return['pg_user_phone'] = $urrphone;
  if(!empty($urremail)) $return['pg_user_contact_email'] = $urremail;

  if($cfg['plugin']['payboxmoney']['pg_testing_mode']) {
    $return['pg_testing_mode'] = 1;
  }

  ksort($return);

  $return['pg_sig'] = md5($url.';'.join(';', $return).';'.$cfg['plugin']['payboxmoney']['pg_merchant_pass']);

	return $return;
}

function cot_payboxmoney_checkSig() {
  global $cfg, $_GET, $_POST, $_SERVER;

  $request = isset($_POST['pg_payment_id']) ? $_POST : $_GET;

  if(!$request['pg_payment_id']) return false;

  $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $url = explode('/', $url);
  $url = end($url);

  $pg_sig_chk = $request['pg_sig'];
  unset($request['pg_sig']);

  $data = $request;

  ksort($data);
  $pg_sig = md5($url.';'.join(';', $data).';'.$cfg['plugin']['payboxmoney']['pg_merchant_pass']);

  if($pg_sig != $pg_sig_chk) {
    $data = array();
    foreach($request as $k => $v) {
      if(stristr($k, 'pg_') == $k) $data[$k] = $v; //urlencode($v);
    }

    ksort($data);

    $pg_sig = md5($url.';'.join(';', $data).';'.$cfg['plugin']['payboxmoney']['pg_merchant_pass']);

    if($pg_sig != $pg_sig_chk) {
      $data['a'] = $a;
      $data['r'] = 'payboxmoney';

      ksort($data);

      $pg_sig = md5($url.';'.join(';', $data).';'.$cfg['plugin']['payboxmoney']['pg_merchant_pass']);
    }
  }

  return ($pg_sig == $pg_sig_chk ? $data : false);
}

function cot_payboxmoney_send($method = '', $data = array()) {

  $url = 'https://api.paybox.money/';

  $response = array();

  if($curl = curl_init()) {
    $query = http_build_query($data);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url.$method);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
    $response = curl_exec($curl);
    curl_close($curl);
    $response = simplexml_load_string($response);

    $response = json_encode($response);
    $response = json_decode($response, 1);
  }

	return $response;
}