<?php

/**
 * Webmoney billing plugin
 *
 * @package wmbilling
 * @version 1.0
 * @author CMSWorks Team
 * @copyright Copyright (c) CMSWorks.ru
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

// Requirements
require_once cot_langfile('cryptonator', 'plug');

function cryptonator_post($url, $options) {
    $query = http_build_query($options);
    $vendor = 'https://www.cryptonator.com/api/merchant/v1/';


    $curl = curl_init($vendor . $url);

    curl_setopt($curl, CURLOPT_USERAGENT, 'Merchant.SDK/PHP');

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

    $body = curl_exec($curl);
    curl_close($curl);
    return json_decode($body, 1);
}

function cryptonator_getinvoice($pid) {

    global $cfg, $db_payments, $db;

    $merchant_id = $cfg['plugin']['cryptonator']['shop_id'];
    $secret = $cfg['plugin']['cryptonator']['secret'];


    $options = array(
        'merchant_id' => $merchant_id,
        'invoice_id' => $pinfo["pay_cryptonator"],
    );
    $string = implode('&', $options) . '&' . $secret;
    $options["secret_hash"] = sha1($string);

    $body = cryptonator_post("getinvoice", $options);

    if ($body["error"] == "Invalid Token") {
        $rpay['pay_cryptonator'] = '';
        $db->update($db_payments, $rpay, "pay_id=?", array($pid));
        cot_redirect($_SERVER["REQUEST_URI"]);
    }
}

function cryptonator_confirming() {
    $t = new XTemplate(cot_tplfile("cryptonator.confirming." . $template, 'plug'));

    foreach ($_SESSION["cryptonator_confirming"] as $k => $v) {
        foreach ($v as $key => $value) {
            $t->assign(strtoupper($key), $value);
        }
        $t->parse("MAIN");
    }


    return $t->text();
}

?>