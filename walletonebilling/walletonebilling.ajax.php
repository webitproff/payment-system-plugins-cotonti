<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('walletonebilling', 'plug');

cot_sendheaders();

if ($cfg['plugin']['walletonebilling']['walletonebilling_ecp'] != 0 && !empty($cfg['plugin']['walletonebilling']['walletonebilling_key']) && !isset($_POST["WMI_SIGNATURE"]))
  cot_walletonebilling_print_answer("Retry", $L['cfg_walletonebilling_api_errors']['WMI_SIGNATURE']);

if (!isset($_POST["WMI_PAYMENT_NO"]))
  cot_walletonebilling_print_answer("Retry", $L['cfg_walletonebilling_api_errors']['WMI_PAYMENT_NO']);

if (!isset($_POST["WMI_ORDER_STATE"]))
  cot_walletonebilling_print_answer("Retry", $L['cfg_walletonebilling_api_errors']['WMI_ORDER_STATE']);

foreach($_POST as $name => $value)
{
  if ($name !== "WMI_SIGNATURE") $params[$name] = $value;
}

uksort($params, "strcasecmp");
$values = "";

foreach($params as $name => $value)
{
  $values .= $value;
}

$susseccpayment = true;

if($cfg['plugin']['walletonebilling']['walletonebilling_ecp'] != 0 && !empty($cfg['plugin']['walletonebilling']['walletonebilling_key'])) {
  $signature = $values . $cfg['plugin']['walletonebilling']['walletonebilling_key'];
  if($cfg['plugin']['walletonebilling']['walletonebilling_ecp'] == 1) {
    $signature = md5($signature);
  } elseif($cfg['plugin']['walletonebilling']['walletonebilling_ecp'] == 2) {
    $signature = sha1($signature);
  }
  $signature = base64_encode(pack("H*", $signature));
  if($signature != $_POST["WMI_SIGNATURE"]) $susseccpayment = false;
}

if ($susseccpayment)
{
  $susseccpayment = false;
  $errormsg = $L['cfg_walletonebilling_api_errors']['AJAX_WMI_ORDER_STATE'].' '.$_POST["WMI_ORDER_STATE"];
  if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED")
  {
		$pinfo = $db->query("SELECT * FROM $db_payments
			WHERE pay_id='" . $_POST['WMI_PAYMENT_NO'] . "'
					AND pay_status='process' LIMIT 1")->fetch();

    if ($pinfo['pay_id'] > 0 && $_POST['WMI_PAYMENT_NO'] == $pinfo['pay_id'])
		{
      if($_POST['WMI_PAYMENT_AMOUNT'] == $pinfo['pay_summ']) {
  			if (cot_payments_updatestatus($pinfo['pay_id'], 'paid'))
  			{
          if($cfg['plugin']['walletonebilling']['walletonebilling_bonus'] != 0 && $cfg['plugin']['walletonebilling']['walletonebilling_bonus_val'] > 0) {
            $bonus = 0;
            if($cfg['plugin']['walletonebilling']['walletonebilling_bonus'] == 1) {
              $bonus = $cfg['plugin']['walletonebilling']['walletonebilling_bonus_val'];
            } elseif($cfg['plugin']['walletonebilling']['walletonebilling_bonus'] == 2) {
              $bonus = $pinfo['pay_id']*($cfg['plugin']['walletonebilling']['walletonebilling_bonus_val']/100);
            }

            if($bonus > 0) {
            	$pdata['pay_userid'] = $pinfo['pay_userid'];
            	$pdata['pay_summ'] = $bonus;
            	$pdata['pay_area'] = 'balance';
            	$pdata['pay_status'] = 'done';
            	$pdata['pay_code'] = '';
            	$pdata['pay_cdate'] = $sys['now'];
            	$pdata['pay_pdate'] = $sys['now'];
            	$pdata['pay_adate'] = $sys['now'];
              $pdata['pay_desc'] = $L['walletonebilling_bonus_title'];

            	$db->insert($db_payments, $pdata);
            }
          }
          $susseccpayment = true;
  			} else {
          $errormsg = $L['cfg_walletonebilling_api_errors']['AJAX_WMI_PAYMENT_SOME_ERROR'];
        }
      } else {
        $errormsg = $L['cfg_walletonebilling_api_errors']['AJAX_WMI_PAYMENT_AMOUNT'];
      }
		} else {
      $errormsg = $L['cfg_walletonebilling_api_errors']['AJAX_WMI_PAYMENT_NO'];
    }
  }

  if($susseccpayment)
  {
    cot_walletonebilling_print_answer("Ok", cot_rc($L['cfg_walletonebilling_api_errors']['AJAX_WMI_ORDER_STATE_ACCEPTED'], array('id' => $_POST["WMI_PAYMENT_NO"], 'title' => $_POST['WMI_DESCRIPTION'])));
  }
  else
  {
    cot_walletonebilling_print_answer("Retry", $errormsg);
  }
}
else
{
  cot_walletonebilling_print_answer("Retry", $L['cfg_walletonebilling_api_errors']['AJAX_WMI_SIGNATURE'].' '.$_POST["WMI_SIGNATURE"]);
}

?>