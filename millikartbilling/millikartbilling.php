<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('millikartbilling', 'plug');

$m = cot_import('m', 'G', 'ALP');
$pid = cot_import('pid', 'G', 'INT');

if (empty($m))
{
	// Получаем информацию о заказе
	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{
		cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');

    $payment = new Millikart($cfg['plugin']['millikartbilling'], $pinfo['pay_summ'], $pid, $pinfo['pay_desc']);
    $response = $payment->getURL();

    if($response['code'] == 0)
    {
		 cot_payments_updatestatus($pid, 'process'); // Изменяем статус "в процессе оплаты"
     header("Location: ".$response['redirect']);
    }
    else
    {
     echo $response['description'];
    }
    exit;
	}
	else
	{
		cot_die();
	}
}
elseif ($m == 'success')
{
  /*
  $payment = new Millikart($cfg['plugin']['millikartbilling'], $pinfo['pay_summ'], $pid, $pinfo['pay_desc']);
  $reference = $payment->signature();

	$cref = strtoupper($cref);

	$plugin_body = $L['millikartbilling_error_otkaz'];

	// проверка корректности подписи
	if ($reference != $cref)
	{
		$plugin_body = $L['millikartbilling_error_incorrect'];
	}
	else
	{
		if(!empty($pid_id))
		{
			// проверка наличия номера платежки и ее статуса
			$pinfo = cot_payments_payinfo($pid_id);
			if ($pinfo['pay_status'] == 'done')
			{
				$plugin_body = $L['millikartbilling_error_done'];
				$redirect = $pinfo['pay_redirect'];
			}
			elseif ($pinfo['pay_status'] == 'paid')
			{
				$plugin_body = $L['millikartbilling_error_paid'];
			}
		}
	}

	$t->assign(array(
		"MILLI_TITLE" => $L['millikartbilling_error_title'],
		"MILLI_ERROR" => $plugin_body
	));
	
	if($redirect){
		$t->assign(array(
			"MILLI_REDIRECT_TEXT" => sprintf($L['millikartbilling_redirect_text'], $redirect),
			"MILLI_REDIRECT_URL" => $redirect,
		));
	}
	$t->parse("MAIN.ERROR");
  */
}
elseif ($m == 'fail')
{
	$t->assign(array(
		"MILLI_TITLE" => $L['millikartbilling_error_title'],
		"MILLI_ERROR" => $L['millikartbilling_error_fail']
	));
	$t->parse("MAIN.ERROR");
}
?>