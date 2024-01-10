<?php

//

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */
defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('cryptonator', 'plug');
require_once cot_incfile('payments', 'module');

$m = cot_import('m', 'G', 'ALP');
$pid = cot_import('pid', 'G', 'INT');
$pt = cot_import('pt', 'G', 'TXT');

if (empty($m)) {
    // Получаем информацию о заказе
    if (!empty($pid) && $pinfo = cot_payments_payinfo($pid)) {

        cot_block($usr['id'] == $pinfo['pay_userid']);
        cot_block($pinfo['pay_status'] == 'new' || $pinfo['pay_status'] == 'process');


        $merchant_id = $cfg['plugin']['cryptonator']['shop_id'];
        $secret = $cfg['plugin']['cryptonator']['secret'];


        $options = array(
            'merchant_id' => $merchant_id,
            'item_name' => 'Item Name',
            'order_id' => $pid,
            'item_description' => '',
            'checkout_currency' => $pt,
            'invoice_amount' => round($pinfo['pay_summ']),
            'invoice_currency' => 'rur',
            'success_url' => COT_ABSOLUTE_URL . 'cryptonator?m=success',
            'failed_url' => COT_ABSOLUTE_URL . 'cryptonator?m=fail',
            'language' => '',
        );

        $string = implode('&', $options) . '&' . $secret;
        $options["secret_hash"] = sha1($string);

       
        if (!$_SESSION["cryptonator"]["invoice"][$pt] OR $_SESSION["cryptonator"]["invoice"][$pt]["invoice_expires"] <= time() OR $pinfo["pay_cryptonator"] != $_SESSION["cryptonator"]["invoice"][$pt]["invoice_id"]) {
            $body = cryptonator_post("createinvoice", $options);
            $_SESSION["cryptonator"]["invoice"][$pt] = $body;
        } else {
            $body = $_SESSION["cryptonator"]["invoice"][$pt];
            if ($body["invoice_expires"] - $body["invoice_created"] <= 1) {
                unset($_SESSION["cryptonator"]["invoice"][$pt]);
                return cot_redirect($_SERVER["REQUEST_URI"]);
            }
        }
        $rpay['pay_cryptonator'] = $body["invoice_id"];
        $db->update($db_payments, $rpay, "pay_id=?", array($pid));

        $t->assign(array(
            'PID' => $pid,
            'ERROR' => $body["error"] ? : null,
            'INVOICE_ID' => $body["invoice_id"] ? : null,
            'CHECKOUT_ADDRESS' => $body["checkout_address"] ? : null,
            'CHECKOUT_CURRENCY' => $body["checkout_currency"] ? : null,
            'CHECKOUT_SHORT_CURRENCY' => $body["checkout_currency"] ? : null,
            'CHECKOUT_AMOUNT' => $body["checkout_amount"] ? : null,
            'INVOICE_CREATED' => $body["invoice_created"] ? : null,
            'INVOICE_EXPIRES' => $body["invoice_expires"] ? : null,
            'SECRET_HASH' => $body["secret_hash"] ? : null,
            'CHECKOUT_DATE' => $body["invoice_expires"] ? date("Y-m-dTH:i:s", $body["invoice_expires"]) : null,
            'CUR_DATE' => date("Y-m-d H:i:s", time()),
        ));


        cot_payments_updatestatus($pid, 'process'); // Изменяем статус "в процессе оплаты"
    } else {
        cot_die();
    }
}
