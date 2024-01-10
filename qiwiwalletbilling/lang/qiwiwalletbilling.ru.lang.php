<?php

defined('COT_CODE') or die('Wrong URL.');

$L['qiwiwalletbilling_title'] = 'Qiwi Wallet Billing';
$L['plu_title'] = 'Qiwi Wallet Billing';

$L['qiwiwalletbilling_pay_text'] = 'Платеж создан.<br>Пополните QIWI кошелек <b><a href="{$paylink}" target="_blank">перейдя по ссылке</a></b> или самостоятельно <b>{$summ} {$valuta}</b> на кошелек <b>{$wallet}</b>, обязательно укажите комментарий к оплате "<b>{$paykey}</b>"';
$L['qiwiwalletbilling_pay_check_text'] = 'Ваш платеж будет автоматически проверен через некоторое время, вы можете сами проверить статус оплаты.';
$L['qiwiwalletbilling_pay_check_btn'] = 'Проверить оплату.';

$L['qiwiwalletbilling_error_empty'] = 'Ошибка. Платеж не найден.';
$L['qiwiwalletbilling_error_not_qiwiwallet'] = 'Ошибка. Платеж оплачивается через другую платежную систему.';
$L['qiwiwalletbilling_error_process'] = 'Платеж находится на проверке. Пожалуйста, подождите.';
$L['qiwiwalletbilling_error_paid'] = 'Оплата прошла успешно.';