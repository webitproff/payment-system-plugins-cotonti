<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('walletonebilling', 'plug');

$m = cot_import('m', 'G', 'ALP');
$pid = cot_import('pid', 'G', 'INT');

if (empty($m))
{
	// Получаем информацию о заказе
	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{

		cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');

    $fields = array();

    $expireddate = $sys['now']-1+(86400*(($cfg['payments']['clearpaymentsdays'] > 0 && $cfg['payments']['clearpaymentsdays'] < 30) ? $cfg['payments']['clearpaymentsdays'] : 30));

    // Добавление полей формы в ассоциативный массив
    $fields["WMI_MERCHANT_ID"]    = $cfg['plugin']['walletonebilling']['walletonebilling_mid'];
    $fields["WMI_PAYMENT_AMOUNT"] = $pinfo['pay_summ'];
    $fields["WMI_CURRENCY_ID"]    = $cfg['plugin']['walletonebilling']['walletonebilling_cur'];
    $fields["WMI_PAYMENT_NO"]     = $pinfo['pay_id'];
    $fields["WMI_DESCRIPTION"]    = "BASE64:".base64_encode($pinfo['pay_desc']);
    $fields["WMI_EXPIRED_DATE"]   = cot_date("Y-m-d", $expireddate).'T'.cot_date("H:i:s", $expireddate);
    $fields["WMI_SUCCESS_URL"]    = COT_ABSOLUTE_URL.cot_url('plug', 'e=walletonebilling&m=success&id='.$pinfo['pay_id']);
    $fields["WMI_FAIL_URL"]       = COT_ABSOLUTE_URL.cot_url('plug', 'e=walletonebilling&m=fail');

    foreach($fields as $name => $val)
    {
      if(is_array($val))
      {
        usort($val, "strcasecmp");
        $fields[$name] = $val;
      }
    }

    uksort($fields, "strcasecmp");
    $fieldValues = "";

    foreach($fields as $value)
    {
      if(is_array($value))
        foreach($value as $v)
        {
          //Конвертация из текущей кодировки (UTF-8)
          //необходима только если кодировка магазина отлична от Windows-1251
          $v = iconv("utf-8", "windows-1251", $v);
          $fieldValues .= $v;
      }
      else
      {
        //Конвертация из текущей кодировки (UTF-8)
        //необходима только если кодировка магазина отлична от Windows-1251
        $value = iconv("utf-8", "windows-1251", $value);
        $fieldValues .= $value;
      }
    }

    if($cfg['plugin']['walletonebilling']['walletonebilling_ecp'] != 0 && !empty($cfg['plugin']['walletonebilling']['walletonebilling_key'])) {
      $signature = $fieldValues . $cfg['plugin']['walletonebilling']['walletonebilling_key'];
      if($cfg['plugin']['walletonebilling']['walletonebilling_ecp'] == 1) {
        $signature = md5($signature);
      } elseif($cfg['plugin']['walletonebilling']['walletonebilling_ecp'] == 2) {
        $signature = sha1($signature);
      }
      $signature = base64_encode(pack("H*", $signature));
      $fields["WMI_SIGNATURE"] = $signature;
    }


		$wo_form = '<form id="woform" action="https://wl.walletone.com/checkout/checkout/Index" method="POST">';
    foreach($fields as $field => $val) {
      $wo_form .= '<input type="hidden" name="'.$field.'" value="'.$val.'"/>';
    }
    $wo_form .= '<input type="submit" value="'.$L['walletonebilling_formbuy'].'" class="'.$L['walletonebilling_formbuy_btnclass'].'"/></form>';

		$t->assign(array(
			'WALLETONE_FORM' => $wo_form,
		));
		$t->parse("MAIN.WOFORM");

		cot_payments_updatestatus($pid, 'process'); // Изменяем статус "в процессе оплаты"
	}
	else
	{
		cot_die();
	}
}
elseif ($m == 'success')
{
	$plugin_body = $L['walletonebilling_result_fail'];

	if (isset($_GET['id']) && $_GET['id'] > 0)
	{
		$pinfo = cot_payments_payinfo($_GET['id']);
		if ($pinfo['pay_status'] == 'done')
		{
			$plugin_body = $L['walletonebilling_result_paid'];
			$redirect = $pinfo['pay_redirect'];
		}
	}
	$t->assign(array(
		"WALLETONE_TITLE" => $L['walletonebilling_result_title'],
		"WALLETONE_ERROR" => $plugin_body
	));

	if($redirect){
		$t->assign(array(
			"WALLETONE_REDIRECT_TEXT" => sprintf($L['walletonebilling_redirect_text'], $redirect),
			"WALLETONE_REDIRECT_URL" => $redirect,
		));
	}

	$t->parse("MAIN.ERROR");
}
elseif ($m == 'fail')
{
	$t->assign(array(
		"WALLETONE_TITLE" => $L['walletonebilling_result_title'],
		"WALLETONE_ERROR" => $L['walletonebilling_result_fail']
	));
	$t->parse("MAIN.ERROR");
}
?>