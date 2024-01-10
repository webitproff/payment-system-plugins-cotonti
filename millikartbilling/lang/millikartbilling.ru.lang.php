<?php

defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */
$L['cfg_mk_mid'] = array('Merchant ID');
$L['cfg_mk_secretkey'] = array('Секретный ключ');
$L['cfg_mk_currency'] =  array('Валюта в формате (ISO 4217)');
$L['cfg_mk_language'] = array('Язык (az/en/ru)');
$L['cfg_mk_mode'] = array('0 - тестовый режим, 1 - обычный');

$L['millikartbilling_title'] = 'Millikart';

$L['millikartbilling_error_paid'] = 'Оплата прошла успешно. В ближайшее время услуга будет активирована!';
$L['millikartbilling_error_done'] = 'Оплата прошла успешно.';
$L['millikartbilling_error_incorrect'] = 'Некорректная подпись';
$L['millikartbilling_error_otkaz'] = 'Отказ от оплаты.';
$L['millikartbilling_error_title'] = 'Результат операции оплаты';
$L['millikartbilling_error_fail'] = 'Оплата не произведена! Пожалуйста, повторите попытку. Если ошибка повторится, обратитесь к администратору сайта';

$L['millikartbilling_redirect_text'] = 'Сейчас произойдет редирект на страницу оплаченной услуги. Если этого не произошло, перейдите по <a href="%1$s">ссылке</a>.';