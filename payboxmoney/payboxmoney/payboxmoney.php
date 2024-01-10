<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_langfile('payboxmoney', 'plug');
require_once cot_incfile('payments', 'module');

$a = cot_import('a', 'G', 'ALP');
$m = cot_import('m', 'G', 'ALP');
$pid = cot_import('pid', 'G', 'INT');

if (empty($m))
{
	// Получаем информацию о заказе
	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{
		cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');

    $senddata = cot_payboxmoney_get_send_data('init_payment.php', $pinfo['pay_id'], $pinfo['pay_summ'], $pinfo['pay_desc'], md5($pinfo['pay_id'].'_'.$pinfo['pay_cdate']), $usr['ip'], $usr['profile']['user_email'], $usr['profile']['user_phone']);
    $resp = cot_payboxmoney_send('init_payment.php', $senddata);

    if($resp['pg_status'] == 'ok' && !empty($resp['pg_redirect_url'])) {
      cot_payments_updatestatus($pid, 'process'); // Изменяем статус "в процессе оплаты"

      header('Location: ' . $resp['pg_redirect_url']);
      exit;
    } else {
    	$t->assign(array(
    		"BILLING_TITLE" => $L['payboxmoney_error_title'],
    		"BILLING_ERROR" => (!empty($resp['pg_error_description']) ? $resp['pg_error_description'] : $L['payboxmoney_error_fail'])
    	));
    	$t->parse("MAIN.ERROR");
    }
	}
	else
	{
		cot_die();
	}
}
elseif ($m == 'success')
{
  if(empty($pid)) $pid = cot_import('pg_order_id', 'G', 'INT');
  if(empty($pid)) $pid = cot_import('pg_order_id', 'P', 'INT');

	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{
		if(!empty($pinfo['pay_code']) && $prinfo = cot_payments_payinfo($pinfo['pay_code'])){
			$redirect = $prinfo['pay_redirect'];
		}
	}

	$t->assign(array(
		"BILLING_TITLE" => $L['payboxmoney_result_title'],
		"BILLING_ERROR" => $L['payboxmoney_result_paid']
	));
	
	if($redirect){
		$t->assign(array(
			"BILLING_REDIRECT_TEXT" => sprintf($L['payboxmoney_redirect_text'], $redirect),
			"BILLING_REDIRECT_URL" => $redirect,
		));
	}
	
	$t->parse("MAIN.ERROR");
}
elseif ($m == 'fail')
{
  if(empty($pid)) $pid = cot_import('pg_order_id', 'G', 'INT');
  if(empty($pid)) $pid = cot_import('pg_order_id', 'P', 'INT');

	$t->assign(array(
		"BILLING_TITLE" => $L['payboxmoney_result_title'],
		"BILLING_ERROR" => $L['payboxmoney_result_fail']
	));
	$t->parse("MAIN.ERROR");
}
?>