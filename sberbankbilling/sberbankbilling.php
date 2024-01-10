<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_langfile('sberbankbilling', 'plug');
require_once cot_incfile('payments', 'module');

$a = cot_import('a', 'G', 'ALP');
$m = cot_import('m', 'G', 'ALP');
$pid = cot_import('pid', 'G', 'INT');

$db->query("ALTER TABLE `$db_users` CHANGE COLUMN `user_sberbank_binding` `user_sberbank_binding` MEDIUMTEXT collate utf8_unicode_ci NOT NULL");

if (empty($m))
{
	// Получаем информацию о заказе
	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{
		cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');

    $urrsbrbindings = $usr['profile']['user_sberbank_binding'];
    $urrsbrbindings = (!empty($urrsbrbindings) ? json_decode($urrsbrbindings, 1) : array());
    if(!is_array($urrsbrbindings)) $urrsbrbindings = array();

    if($cfg['plugin']['sberbankbilling']['sbrbindings'] && $a == 'nextstep') {
      $sberbankresponse = cot_sberbankbilling_payment_bindings($_GET['mdOrder'], array(
        'bindingId' => $_POST['bindingId'],
        'cvc' => $_POST['cvc'],
        'email' => $usr['profile']['user_email'],
        'ip' => $usr['ip']
      ));

      if(!empty($sberbankresponse['redirect'])) {
        $db->update($db_payments, array('pay_sberbank_check' => 1, 'pay_sberbank_id' => $sberbankresponse['orderId']), "pay_id=?", array($pid));
        cot_payments_updatestatus($pid, 'process'); // Изменяем статус "в процессе оплаты"

  		  header('Location: ' . $sberbankresponse['redirect']);
        exit;
      } else {
      	$t->assign(array(
      		"BILLING_TITLE" => $L['sberbankbilling_error_title'],
      		"BILLING_ERROR" => $sberbankresponse['errorMessage']
      	));
      	$t->parse("MAIN.ERROR");
      }
    } else {
      $sberbankresponse = cot_sberbankbilling_payment($pid, $pinfo);

      if(!empty($sberbankresponse['formUrl']) && !empty($sberbankresponse['orderId'])) {

        if($cfg['plugin']['sberbankbilling']['sbrbindings'] && $cfg['plugin']['sberbankbilling']['sbrform']) {
          $t->assign(array(
          	"BILLING_FORM_URL" => cot_url('plug', 'e=sberbankbilling&pid='.$pid.'&a=nextstep&mdOrder='.$sberbankresponse['orderId'], '', true),
            "BILLING_BINDINGS" => $urrsbrbindings
          ));

          $t->parse("MAIN.FORM");
        } else {
          $db->update($db_payments, array('pay_sberbank_check' => 1, 'pay_sberbank_id' => $sberbankresponse['orderId']), "pay_id=?", array($pid));
          cot_payments_updatestatus($pid, 'process'); // Изменяем статус "в процессе оплаты"

    	    header('Location: ' . $sberbankresponse['formUrl']);
          exit;
        }
      } else {
      	$t->assign(array(
      		"BILLING_TITLE" => $L['sberbankbilling_error_title'],
      		"BILLING_ERROR" => $sberbankresponse['errorMessage']
      	));
      	$t->parse("MAIN.ERROR");
      }
    }
	}
	else
	{
		cot_die();
	}
}
elseif ($m == 'success')
{
	if (!empty($pid) && $pinfo = cot_payments_payinfo($pid))
	{
		if(!empty($pinfo['pay_code']) && $prinfo = cot_payments_payinfo($pinfo['pay_code'])){
			$redirect = $prinfo['pay_redirect'];
		}
	}

	$t->assign(array(
		"BILLING_TITLE" => $L['sberbankbilling_error_title'],
		"BILLING_ERROR" => $L['sberbankbilling_error_done']
	));
	
	if($redirect){
		$t->assign(array(
			"BILLING_REDIRECT_TEXT" => sprintf($L['sberbankbilling_redirect_text'], $redirect),
			"BILLING_REDIRECT_URL" => $redirect,
		));
	}
	
	$t->parse("MAIN.ERROR");
}
elseif ($m == 'fail')
{
	$t->assign(array(
		"BILLING_TITLE" => $L['sberbankbilling_error_title'],
		"BILLING_ERROR" => $L['sberbankbilling_error_fail']
	));
	$t->parse("MAIN.ERROR");
}
?>