<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

$L['plu_title'] = $L['qiwiwalletbilling_title'];

require_once cot_incfile('payments', 'module');
require_once cot_incfile('qiwiwalletbilling', 'plug');

$pid = cot_import('pid', 'G', 'INT');
$order = cot_import('order', 'G', 'INT');

if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
{
	cot_block($usr['id'] == $pinfo['pay_userid']);
	cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');
		
	$summ = number_format($pinfo['pay_summ']*$cfg['plugin']['qiwiwalletbilling']['qw_rate'], 2, '.', '');
		
  $paylink = 'https://qiwi.com/payment/form/99?' . http_build_query(
      array(
    		"amountInteger"    => intval($summ),
    		"amountFraction"   => substr(strrchr(number_format($summ, 2), "."), 1) ?: 00,
    		"currency"         => $cfg['plugin']['qiwiwalletbilling']['qw_currency'],
    		"extra['account']" => urlencode(trim(str_replace('+', '', $cfg['plugin']['qiwiwalletbilling']['qw_wallet']))),
    		"extra['comment']" => cot_qiwiwalletbilling_get_key($pinfo),
    		"blocked[0]"       => 'sum',
    		'blocked[1]'       => 'account',
    		'blocked[2]'       => 'comment',
      )
  );

	$t->assign(array(
    'QIWI_PID' => $pid,
		'QIWI_SUMM' => $summ,
    'QIWI_WALLET' => $cfg['plugin']['qiwiwalletbilling']['qw_wallet'],
    'QIWI_TEXT' => cot_rc($L['qiwiwalletbilling_pay_text'], array(
      'paylink' => $paylink,
      'paykey' => cot_qiwiwalletbilling_get_key($pinfo),
      'summ' => $summ,
      'wallet' => $cfg['plugin']['qiwiwalletbilling']['qw_wallet'],
      'valuta' => $cfg['payments']['valuta']
    )),
    'QIWI_PAYURL' => $paylink,
	));

  $db->update($db_payments, array('pay_isqiwiwallet' => 1), 'pay_id='.$pid);
  cot_payments_updatestatus($pid, 'process'); // Изменяем статус "в процессе оплаты"
}
else
{
	cot_die();
}

?>