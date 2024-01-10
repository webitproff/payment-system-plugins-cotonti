<?php

defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */
$L['cfg_mk_mid'] = array('Merchant ID');
$L['cfg_mk_secretkey'] = array('Secret key');
$L['cfg_mk_currency'] =  array('Transaction currency code (ISO 4217)');
$L['cfg_mk_language'] = array('Language (az/en/ru)');
$L['cfg_mk_mode'] = array('0 - test mod, 1 - real payments');

$L['millikartbilling_title'] = 'Millikart';

$L['millikartbilling_error_paid'] = 'Payment was successful. In the near future the service will be activated!';
$L['millikartbilling_error_done'] = 'Payment was successful.';
$L['millikartbilling_error_incorrect'] = 'The signature is incorrect!';
$L['millikartbilling_error_otkaz'] = 'Failure to pay.';
$L['millikartbilling_error_title'] = 'Result of the operation of payment';
$L['millikartbilling_error_fail'] = 'Payment is not made! Please try again. If the problem persists, contact your site administrator';

$L['millikartbilling_redirect_text'] = 'Now will redirect to the page of the paid services. If it does not, click <a href="%1$s">here</a>.';