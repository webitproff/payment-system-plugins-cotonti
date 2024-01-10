<?php
defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('alfabank', 'plug');

define('ALFABANK_GATEWAY_URL', $cfg['plugin']['alfabank']['alfabank_getaway']);
define('ALFABANK_RETURN_URL', COT_ABSOLUTE_URL . cot_url('plug', 'r=alfabank', '', true));
define('ALFABANK_FAIL_URL', COT_ABSOLUTE_URL . cot_url('plug', 'r=alfabank', '', true));

function alfabank_gateway($method, $data) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => ALFABANK_GATEWAY_URL.$method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data)
    ));
    $response = curl_exec($curl);

    $response = json_decode($response, true);
    curl_close($curl);
    return $response;
}

?>