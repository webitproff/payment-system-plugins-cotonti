<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

ob_end_clean();

$m = cot_import('m', 'G', 'TXT');
if($m == 'check') {
  cot_sendheaders();

  $return = $L['qiwiwalletbilling_error_empty'];
  $pid = cot_import('pid', 'G', 'INT');
  if($usr['id'] > 0 && $pid > 0) {
    require_once cot_incfile('payments', 'module');
    $pinfo = cot_payments_payinfo($pid);

    if($pinfo['pay_id'] > 0) {
      $return = $L['qiwiwalletbilling_error_not_qiwiwallet'];
      if($pinfo['pay_isqiwiwallet']) {
        $return = (in_array($pinfo['pay_status'], array('paid', 'done')) ? $L['qiwiwalletbilling_error_paid'] : $L['qiwiwalletbilling_error_process']);
      }
    }
  }

  echo $return;
  exit;
} elseif($m == 'cron') {
  $qiwipays = array();
  $qiwihist = cot_qiwiwalletbilling_rest_send('payment-history', array(
    'operation' => 'IN',
    'rows' => 50
  ));

  if($qiwihist['success'] && is_array($qiwihist['data']['data'])) {
    $hpays = $db->query("SELECT * FROM $db_payments WHERE
      pay_isqiwiwallet=1
      AND (pay_status='new' OR pay_status='process')")->fetchAll();
    $hpays_payed = array();

    foreach($qiwihist['data']['data'] as $qpay) {
      if($qpay['type'] == 'IN' && $qpay['status'] == 'SUCCESS' && strlen($qpay['comment']) == 6 && !$db->query("SELECT COUNT(*) FROM $db_payments WHERE pay_qiwiwallet_id='".$qpay['txnId']."' LIMIT 1")->fetchColumn()) {
        foreach($hpays as $pinfo) {
          if(!in_array($pinfo['pay_id'], $hpays_payed) && $qpay['sum']['amount'] == $pinfo['pay_summ'] && $qpay['comment'] == cot_qiwiwalletbilling_get_key($pinfo)) {
            $hpays_payed[] = $pinfo['pay_id'];
            $db->update($db_payments, array('pay_qiwiwallet_id' => $decoded['payment']['txnId']), 'pay_id='.$pinfo['pay_id']);
            cot_payments_updatestatus($pinfo['pay_id'], 'paid');
          }
        }
      }
    }
  }
  exit;
} else {
  $return = array();
  $return['response'] = 'error';

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);

    if(is_array($decoded)){
      $reqparams = $decoded['payment']['sum']['currency'] . '|' . $decoded['payment']['sum']['amount'] . '|'. $decoded['payment']['type'] . '|' . $decoded['payment']['account'] . '|' . $decoded['payment']['txnId'];
      foreach ($decoded as $name => $value) {
        if ($name == 'hash') {
          $SIGN_REQ = $value;
        }
      }

      $NOTIFY_ARR = (!empty($cfg['plugin']['qiwiwalletbilling']['qw_webhook']) ? explode('_', $cfg['plugin']['qiwiwalletbilling']['qw_webhook']) : array('', ''));
      $NOTIFY_PWD = $NOTIFY_ARR[1];

      $reqres = hash_hmac("sha256", $reqparams, base64_decode($NOTIFY_PWD));

      if($reqres == $SIGN_REQ) {
        $return['response'] = 'OK';

        $hpaykey = $decoded['payment']['comment'];

        if(!empty($hpaykey) && strlen($hpaykey) == 6 && $decoded['payment']['type'] == 'IN' && $decoded['payment']['status'] == 'SUCCESS') {
          require_once cot_incfile('payments', 'module');

          $hpays = $db->query("SELECT * FROM $db_payments WHERE
              pay_isqiwiwallet=1
              AND (pay_status='new' OR pay_status='process')
              AND pay_summ='".$decoded['payment']['sum']['amount']."'")->fetchAll();

          foreach($hpays as $pinfo) {
            if($hpaykey == cot_qiwiwalletbilling_get_key($pinfo)) {
              $db->update($db_payments, array('pay_qiwiwallet_id' => $decoded['payment']['txnId']), 'pay_id='.$pinfo['pay_id']);
              cot_payments_updatestatus($pinfo['pay_id'], 'paid');
            }
          }
        }
      }
    }
  }

  header('Content-Type: application/json');
  echo json_encode($return);
  exit;
}