<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
**/

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('unitpay', 'plug');
require_once cot_incfile('payments', 'module');

if(isset($_GET['method']) && $_GET['method'] != 'initPayment') {
    include_once($cfg['plugins_dir'].'/unitpay/unitpay.ajax.php');
}

$m = cot_import('m', 'G', 'ALP');
$pid = cot_import('pid', 'G', 'INT');

$paymentType = cot_import('paymentType', 'G', 'TXT');

if (empty($m))
{
	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{
    cot_payments_updatestatus($pid, 'process');

    $unitpay_selected_type_found = '';

		cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');

		$unitpay_amount = number_format($pinfo['pay_summ'], 2, '.', '');
		$unitpay_desc = $pinfo['pay_desc'] .' '. $usr['name'];
		$unitpay_no = $pid;
		$unitpay_public = $cfg['plugin']['unitpay']['unitpay_pkey'];

    if(!empty($paymentType)) {
      foreach($cot_billings['unitpay']['extra'] as $upaybilltype) {
        foreach($upaybilltype['variants'] as $upayvariant) {
          if($upayvariant['active'] && $paymentType == $upayvariant['code']) {
            $unitpay_selected_type_found = $upayvariant['code'];

            if(1 == 1) {

              $url = 'https://unitpay.money/api?method=initPayment';
              //$url = 'https://unitpay.money/pay/'.$cfg['plugin']['unitpay']['unitpay_partnerkey']."?";
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS,
                        http_build_query(array(
                          'params' => array(
                            'projectId' => $cfg['plugin']['unitpay']['unitpay_purse'],
                            'secretKey' => $cfg['plugin']['unitpay']['unitpay_skey'],
                            'ip' => $usr['ip'],

                            //'resultUrl' => urlencode($pinfo['pay_redirect']),

                            //'preauth' => 1,
                            'signature' => hash("sha256", $unitpay_no."{up}"."RUB"."{up}".$unitpay_desc."{up}".$unitpay_amount."{up}".$cfg['plugin']['unitpay']['unitpay_skey']),
                            'currency' => 'RUB',
                            
                            'paymentType' => $upayvariant['code'],
                            'sum' => $unitpay_amount,
                            'desc' => $unitpay_desc,
                            'account' => $unitpay_no,
                            'phone' => $usr['profile']['user_phone'],
                            'operator' => $upayvariant['operator'],
                            //'finalCurrency' => 'RUB',
                            //'hideOrderCost' => 'true',
                            //'hideMenu' => 'true',
                            //'hideOtherMethods' => 'true',
                            //'hideOtherPSMethods' => 'false',
                            'customerEmail' => $usr['profile']['user_email']
                          )
                        )));
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $response = curl_exec($ch);
              curl_close($ch);

              $response = (!empty($response) ? json_decode($response, 1) : array());

              if(is_array($response) && is_array($response['result']) && $response['result']['type'] == 'redirect' && !empty($response['result']['redirectUrl'])) {
                header('Location: '.$response['result']['redirectUrl']);
                exit;
              }
            }
            break;
          }
        }
      }
    }

    foreach($cot_billings['unitpay']['extra'] as $upaybilltype) {
  		$t->assign(array(
  			'UNITPAY_TYPE' => $upaybilltype['title'],
  		));

      foreach($upaybilltype['variants'] as $upayvariant) {
        if($upayvariant['active']) {
          $t_variant = "<form id=\"unitpay_".$upayvariant['code']."_".$upayvariant['operator']."\" name=\"pay\" method=\"POST\" action=\"https://unitpay.ru/pay/".$unitpay_public."/".$upayvariant['code']."\">
        			<input type=\"hidden\" name=\"sum\" value=\"" . $unitpay_amount . "\">
        			<input type=\"hidden\" name=\"desc\" value=\"" . $unitpay_desc . "\">
        			<input type=\"hidden\" name=\"account\" value=\"" . $unitpay_no . "\">
              <input type=\"hidden\" name=\"phone\" value=\"".$usr['profile']['user_phone']."\">
        			<input type=\"hidden\" name=\"operator\" value=\"".$upayvariant['operator']."\">
        			<input type=\"hidden\" name=\"finalCurrency\" value=\"RUB\">
        			<input type=\"hidden\" name=\"hideOrderCost\" value=\"true\">
        			<input type=\"hidden\" name=\"hideMenu\" value=\"true\">
        			<input type=\"hidden\" name=\"hideOtherMethods\" value=\"true\">
              <input type=\"hidden\" name=\"hideOtherPSMethods\" value=\"false\">
              <input type=\"hidden\" name=\"customerEmail\" value=\"".$usr['profile']['user_email']."\">
      			  ".(empty($unitpay_selected_type_found) ? "<button role=\"button\" type=\"submit\" class=\"thumbnail\"><img src=\"".$upayvariant['img']."\" alt=\"".$upayvariant['title']."\" title=\"".$upayvariant['title']."\"></button>" : '')."
      			</form>";

          if(!empty($unitpay_selected_type_found) && $unitpay_selected_type_found == $upayvariant['code']) {
            $t_variant .= "<script>$('#unitpay_".$upayvariant['code']."_".$upayvariant['operator']."').submit();</script>";
          }

      		$t->assign(array(
      			'UNITPAY_VARIANT' => $t_variant,
      		));
      		$t->parse("MAIN.PAY.TYPES.VARIANTS");
        }
      }
      $t->parse("MAIN.PAY.TYPES");
    }

		$t->assign(array(
			'PSUMM' => $unitpay_amount,
			'PNO' => $unitpay_no,
			'PDESC' => $pinfo['pay_desc'] .' <b>'. $usr['name'].'</b>',
      'UPAY_SELECTED_TYPE_FOUND' => (!empty($unitpay_selected_type_found) ? 1 : 0)
		));
		$t->parse("MAIN.PAY.PINFO");
		
		$t->parse("MAIN.PAY");
	}
	else
	{
		cot_die();
	}
}
elseif ($m == 'success')
{
	$plugin_body = $L['unitpay_error'];

	if (isset($_GET['account']) && preg_match('/^\d+$/', $_GET['account']) == 1)
	{
		$pinfo = cot_payments_payinfo($_GET['account']);
		if ($pinfo['pay_status'] == 'done')
		{
			$plugin_body = $L['unitpay_done'];
			$redirect = $pinfo['pay_redirect'];
		}
		elseif ($pinfo['pay_status'] == 'paid')
		{
			$plugin_body = $L['unitpay_paid'];
		}
	}
	$t->assign(array(
		"UNITPAY_TITLE" => $L['unitpay_err_title'],
		"UNITPAY_ERROR" => $plugin_body
	));
	
	if($redirect){
		$t->assign(array(
			"UNITPAY_REDIRECT_TEXT" => sprintf($L['unitpay_redirect_text'], $redirect),
			"UNITPAY_REDIRECT" => $redirect,
		));
	}
	
	$t->parse("MAIN.ERROR");
}
elseif ($m == 'fail')
{
	$t->assign(array(
		"UNITPAY_TITLE" => $L['unitpay_err_title'],
		"UNITPAY_ERROR" => $L['unitpay_fail']
	));
	$t->parse("MAIN.ERROR");
}
elseif ($m == 'query') {
    echo json_encode(["result" => ["message" => "Запрос успешно обработан"]]);
}
?>