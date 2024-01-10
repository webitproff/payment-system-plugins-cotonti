<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payboxmoney', 'plug');

ob_end_clean();

cot_sendheaders();
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Origin: *');

$a = cot_import('a', 'G', 'TXT');

$response = array(
  'status' => 'error',
  'pg_description' => '',
  'pg_salt' => cot_unique(16),
);

/* ДОПОЛНИТЕЛЬНАЯ ПРОВЕРКА - на всякий случай
$check_data = array(
  'pg_merchant_id' => $cfg['plugin']['payboxmoney']['pg_merchant_id'],
  'pg_payment_id' => $_REQUEST['pg_payment_id'],
  'pg_order_id' => $_REQUEST['pg_order_id'],
  'pg_salt' => cot_unique(16)
);
ksort($check_data);
$check_data['pg_sig'] = md5('get_status.php;'.join(';', $check_data).';'.$cfg['plugin']['payboxmoney']['pg_merchant_pass']);

echo '<pre>'.print_r(cot_payboxmoney_send('get_status.php', $check_data), 1).'</pre>';
exit;
*/

if($data = cot_payboxmoney_checkSig()) {
  if($data['pg_order_id'] > 0) {
    $pinfo = cot_payments_payinfo($data['pg_order_id']);

    if($pinfo['pay_id'] > 0) {
      if($a == 'check') {
        if($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process') {
          $response['status'] = 'ok';
        } else {
          $response['status'] = 'rejected';
        }
      } elseif($a == 'result') {
        $response['status'] = 'ok';

        if($data['pg_result']) {
          cot_payments_updatestatus($pinfo['pay_id'], 'paid');
        }
      } elseif($a == 'refund') {
        //todo ?
      } elseif($a == 'capture') {
        //todo ?
      }
    } else {
      $response['pg_description'] = 'Платеж не найден';
    }
  }
}

if($response['status'] != 'ok' && empty($response['pg_description'])) {
  $response['pg_description'] = 'Произошла ошибка.';
} else {
  unset($response['pg_description']);
}

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = explode('/', $url);
$url = end($url);

$response['pg_sig'] = md5($url.';'.join(';', $response).';'.$cfg['plugin']['payboxmoney']['pg_merchant_pass']);

header('Content-Type: application/xml', true, 200);
$return = '<^xml version="1.0" encoding="UTF-8"^>';
$return = str_replace('^', '?', $return);
$return .= '<response>';
$return .= '<status>'.$response['status'].'</status>';
if($response['status'] != 'ok') {
  $return .= '<pg_description>'.$response['pg_description'].'</pg_description>';
}
$return .= '<pg_salt>'.$response['pg_salt'].'</pg_salt>';
$return .= '<pg_sig>'.$response['pg_sig'].'</pg_sig>';
$return .= '</response>';

echo $return;
exit;

?>