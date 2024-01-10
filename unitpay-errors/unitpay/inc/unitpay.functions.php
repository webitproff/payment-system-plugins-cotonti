<?php
defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('unitpay', 'plug');

function cot_unitpay_getSignature($method = '', $params = array(), $secretKey = '') {
    ksort($params);
    unset($params['sign']);
    unset($params['signature']);
    unset($params['secretKey']);//rsh added
    array_push($params, $secretKey);
    array_unshift($params, $method);
    return hash('sha256', join('{up}', $params));
}

?>