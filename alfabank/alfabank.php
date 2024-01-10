<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */
defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('alfabank', 'plug');
require_once cot_incfile('payments', 'module');

$m = cot_import('m', 'G', 'ALP');
$pid = cot_import('pid', 'G', 'INT');

if (empty($m))
{
	// Получаем информацию о заказе
	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{
		cot_block($usr['id'] == $pinfo['pay_userid']);
		cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');

    $data = array(
        'userName' => $cfg['plugin']['alfabank']['alfabank_username'],
        'password' => $cfg['plugin']['alfabank']['alfabank_password'],
        'orderNumber' => $pinfo['pay_id'],
        'amount' => (float)$pinfo['pay_summ']*100,
        'returnUrl' => ALFABANK_RETURN_URL,
        'failUrl' => ALFABANK_FAIL_URL
    );

    if($usr['id'] > 0) $data['clientId'] = $usr['id'];

    if($usr['id'] > 0 && !empty($usr['profile']['user_alfabank'])) {
      $data['features'] = "AUTO_PAYMENT";
      $data['bindingId'] = $usr['profile']['user_alfabank'];
      $response = alfabank_gateway('paymentOrderBinding.do', $data);
    }
    else
    {
      $response = alfabank_gateway('register.do', $data);
    }

    if (isset($response['orderId']) && !isset($response['errorCode'])) {
        cot_payments_updatestatus($pid, 'process');
		    $db->update($db_payments, array('pay_alfabankid' => $response['orderId']), "pay_id=?", array($pid));

        if(COT_AJAX) {
          echo '<script>location.href="'.$response['formUrl'].'";</script>';
          exit;
        }
        else
        {
          header('Location: ' . $response['formUrl']);
          die();
        }
    } else{
      	$t->assign(array(
      		"AB_TITLE" => $L['alfabank_error_title'],
      		"AB_ERROR" => $L['alfabank_error_fail']
      	));
      	$t->parse("MAIN.ERROR");
        cot_log('Альфа-банк ошибка #' . $response['errorCode'] . ': ' . $response['errorMessage'], 'adm');
    }
	}
	else
	{
		cot_die();
	}
}
elseif ($m == 'success')
{
	$plugin_body = $L['alfabank_error_incorrect'];

	if ($pid > 0)
	{
		$pinfo = cot_payments_payinfo($pid);
		if ($pinfo['pay_status'] == 'done')
		{
			$plugin_body = $L['alfabank_error_done'];
			$redirect = $pinfo['pay_redirect'];
		}
		elseif ($pinfo['pay_status'] == 'paid')
		{
			$plugin_body = $L['alfabank_error_paid'];
		}
	}
	$t->assign(array(
		"AB_TITLE" => $L['alfabank_error_title'],
		"AB_ERROR" => $plugin_body
	));
	
	if($redirect){
		$t->assign(array(
			"AB_REDIRECT_TEXT" => sprintf($L['alfabank_redirect_text'], $redirect),
			"AB_REDIRECT_URL" => $redirect,
		));
	}
	
	$t->parse("MAIN.ERROR");
}
elseif ($m == 'fail')
{
	$t->assign(array(
		"AB_TITLE" => $L['alfabank_error_title'],
		"AB_ERROR" => $L['alfabank_error_fail']
	));
	$t->parse("MAIN.ERROR");
}
?>