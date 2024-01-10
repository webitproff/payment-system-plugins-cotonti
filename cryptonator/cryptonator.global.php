<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=global
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('cryptonator', 'plug');

$cryptonator_pays = cot_payments_getallpays("balance", "process");

if (is_array($cryptonator_pays)) {
    foreach ($cryptonator_pays as $pay) {
        if (!empty($pay["pay_cryptonator"])) {
            $merchant_id = $cfg['plugin']['cryptonator']['shop_id'];
            $secret = $cfg['plugin']['cryptonator']['secret'];

            $cryptonator_options = array(
                'merchant_id' => $merchant_id,
                'invoice_id' => $pay["pay_cryptonator"],
            );
            $string = implode('&', $cryptonator_options) . '&' . $secret;
            $cryptonator_options["secret_hash"] = sha1($string);

            $body = cryptonator_post("getinvoice", $cryptonator_options);


//            unpaid – не оплачен
//            confirming - подтверждается
//            paid – оплачен
//            cancelled - отменен
//            mispaid - сумам оплаты меньше суммы 

            if ($body["status"] == "confirming") {
                $_SESSION["cryptonator_confirming"][$pay["pay_cryptonator"]]= $body;
            }
            if ($body["status"] == "cancelled") {
                $db->delete($db_payments, "pay_id=?", array($pay["pay_id"]));
                unset($_SESSION["cryptonator_confirming"][$pay["pay_cryptonator"]]);
            }
            if ($body["status"] == "unpaid") {
                unset($_SESSION["cryptonator_confirming"][$pay["pay_cryptonator"]]);
            }
            if ($body["status"] == "paid") {
                cot_payments_updatestatus($pay["pay_id"], 'paid');
                unset($_SESSION["cryptonator_confirming"][$pay["pay_cryptonator"]]);
            }
        }
    }
}