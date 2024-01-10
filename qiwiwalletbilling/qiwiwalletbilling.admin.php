<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=tools
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL.');

$a = cot_import('a', 'G', 'TXT');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('qiwiwalletbilling', 'plug');

if($a == 'paychecked')
{
  $payid = cot_import('rpayid', 'P', 'INT');
  $qiwipayid = cot_import('rqiwipayid', 'P', 'INT');

  if(!$payid || !$qiwipayid) {
    cot_check(true, 'Произошла ошибка при подтверждении оплаты');
  } else {
    $pinfo = cot_payments_payinfo($payid);
    if($pinfo['pay_id'] > 0 && ($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process') && $pinfo['pay_isqiwiwallet']) {
      $db->update($db_payments, array('pay_qiwiwallet_id' => $qiwipayid), 'pay_id='.$payid);
      cot_payments_updatestatus($payid, 'paid');
    } else {
      cot_check(true, 'Произошла ошибка при подтверждении оплаты');
    }
  }
  cot_redirect(cot_url('admin', 'm=other&p=qiwiwalletbilling', '', true));
}

$t = new XTemplate(cot_tplfile('qiwiwalletbilling.admin', 'plug', true));

/* QIWI API */
$qiwipays = array();
$qiwihist = cot_qiwiwalletbilling_rest_send('payment-history', array(
  'operation' => 'IN',
  'rows' => 50
));

if($qiwihist['success'] && is_array($qiwihist['data']['data'])) {
  foreach($qiwihist['data']['data'] as $qpay) {
    $hpayid = $db->query("SELECT pay_id FROM $db_payments WHERE pay_qiwiwallet_id='".$qpay['txnId']."' LIMIT 1")->fetchColumn();
    $hpaywaitid = 0;
    if(!$hpayid) {
      $hpaywaitid = $db->query("SELECT pay_id FROM $db_payments WHERE pay_status='process' AND pay_isqiwiwallet=1 AND pay_id=".(int)$qpay['comment'].' LIMIT 1')->fetchColumn();
      if(!$hpaywaitid) {
        $hpaywaitid = $db->query("SELECT pay_id FROM $db_payments WHERE pay_status='process' AND pay_isqiwiwallet=1 AND pay_summ=".$qpay['sum']['amount'].' LIMIT 1')->fetchColumn();
      }
    }
    $currency = (!empty($qpay['sum']['currency']) && is_array($qiwiwalletbilling_currency[$qpay['sum']['currency']])) ? $qiwiwalletbilling_currency[$qpay['sum']['currency']]['char'] : '';

    $t->assign(array(
    	'QH_ID' => $qpay['txnId'],
      'QH_ERROR' => ($qpay['errorCode'] ? $qpay['error'] : 0),
      'QH_ERROR_CODE' => $qpay['errorCode'],
      'QH_STATUS' => $qpay['status'],
      'QH_DATE' => str_replace('T', ' ', $qpay['date']),
      'QH_SUMM' => $qpay['sum']['amount'],
      'QH_SUMM_VALUTA' => $qpay['sum']['currency'],
      'QH_SUMM_VALUTA_CHAR' => $currency,
      'QH_SUMM_CURRENCY_RATE' => $qpay['currencyRate'],
      'QH_PAYER_TITLE' => $qpay['view']['title'],
      'QH_PAYER_ACCOUNT' => $qpay['view']['account'],
      'QH_COMMENT' => $qpay['comment'],
      'QH_HPAYID' => ($hpayid > 0 ? $hpayid : 0),
      'QH_HPAYWAITID' => ($hpaywaitid > 0 ? $hpaywaitid : 0),
    ));

    if(!$hpayid && $qpay['status'] == 'SUCCESS') $qiwipays[$qpay['txnId']] = '#'.$qpay['txnId'].', '.$qpay['sum']['amount'] . $currency . ', комментарий: '. (!empty($qpay['comment']) ? $qpay['comment'] : 'не указан');

    $t->parse('MAIN.QIWIHIST_ROW');
  }
}

$t->assign(array(
	'QIWIHIST_ERROR' => ($qiwihist['success'] ? 0 : 1),
  'QIWIHIST_ERROR_TEXT' => $qiwihist['error'],
  'QIWIHIST_SELECT' => (count($qiwipays) > 0 ? cot_selectbox('', 'rqiwipayid', array_keys($qiwipays), array_values($qiwipays)) : '')
));
/* QIWI API */

list($pg, $d, $durl) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

$where['area'] = "pay_area='balance'";
$where['status'] = "pay_status='process'";
$where['summ'] = 'pay_summ>0';
$where['isqiwiwallet'] = 'pay_isqiwiwallet>0';

$where = array_filter($where);
$where = ($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$pays = $db->query("SELECT * FROM $db_payments AS p
	LEFT JOIN $db_users AS u ON u.user_id=p.pay_userid
	$where
	ORDER BY pay_pdate DESC, pay_id DESC LIMIT $d, " . $cfg['maxrowsperpage'])->fetchAll();

$totalitems = $db->query("SELECT COUNT(*) FROM $db_payments AS p
	LEFT JOIN $db_users AS u ON u.user_id=p.pay_userid
	$where")->fetchColumn();

$pagenav = cot_pagenav('admin', 'm=other&p=qiwiwalletbilling', $d, $totalitems, $cfg['maxrowsperpage']);

$t->assign(array(
	'PAGENAV_PAGES' => $pagenav['main'],
	'PAGENAV_PREV' => $pagenav['prev'],
	'PAGENAV_NEXT' => $pagenav['next']
));

foreach($pays as $pay)
{
	$t->assign(cot_generate_paytags($pay, 'PAY_ROW_'));

	if($pay['pay_userid'] > 0)
	{
		$t->assign(cot_generate_usertags($pay, 'PAY_ROW_USER_'));
	}
	else
	{
		$t->assign(array(
			'PAY_ROW_USER_ID' => 0,
			'PAY_ROW_USER_NICKNAME' => $L['Guest'],
		));
	}

	$t->assign(array(
		'PAY_ROW_QIWI_HASH' => cot_qiwiwalletbilling_get_key($pay),
	));

	$t->parse('MAIN.PAYMENTS_ROW');
}

cot_display_messages($t);

$t->parse('MAIN');
$adminmain = $t->text('MAIN');