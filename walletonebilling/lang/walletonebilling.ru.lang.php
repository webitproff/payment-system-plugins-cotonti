<?php
defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */
$L['cfg_walletonebilling_mid'] = array('Идентификатор магазина');
$L['cfg_walletonebilling_cur'] = array('Идентификатор валюты (ISO 4217)');
$L['cfg_walletonebilling_cur_hint'] = '
643 — Российские рубли<br>
710 — Южно-Африканские ранды<br>
840 — Американские доллары<br>
978 — Евро<br>
980 — Украинские гривны<br>
398 — Казахстанские тенге<br>
974 — Белорусские рубли<br>
972 — Таджикские сомони<br>
944 — Азербайджанский манат<br>
985 — Польский злотый
';

$L['cfg_walletonebilling_ecp'] = 'Метод формирования ЭЦП';
$L['cfg_walletonebilling_ecp_params'] = array(
   0 => 'Не использовать',
   1 => 'MD5',
   2 => 'SHA1'
);

$L['cfg_walletonebilling_bonus'] = 'Бонус за пополнение счета';
$L['cfg_walletonebilling_bonus_params'] = array(
   0 => 'Отключить',
   1 => 'Фикс. в валюте сайта',
   2 => '% от зачисления'
);

$L['cfg_walletonebilling_bonus_val'] = 'Сумма бонуса в валюте сайта (фикс) или % (0-100)';

/**
 * Plugin Walletone API
 */

$L['cfg_walletonebilling_api_errors'] = array(
  'WMI_SIGNATURE' => 'Отсутствует параметр WMI_SIGNATURE',
  'WMI_PAYMENT_NO' => 'Отсутствует параметр WMI_PAYMENT_NO',
  'WMI_ORDER_STATE' => 'Отсутствует параметр WMI_ORDER_STATE',
  'AJAX_WMI_SIGNATURE' => 'Неверная подпись',
  'AJAX_WMI_ORDER_STATE' => 'Неверное состояние',
  'AJAX_WMI_PAYMENT_NO' => 'Оплата не найдена',
  'AJAX_WMI_PAYMENT_AMOUNT' => 'Оплаченная сумма не равна сумме операции',
  'AJAX_WMI_PAYMENT_SOME_ERROR' => 'Произошла ошибка, свяжитесь с администрацией',
  'AJAX_WMI_ORDER_STATE_ACCEPTED' => 'Оплата "{$title}" прошла успешно, идентификатор #{$id}'
);

/**
 * Plugin Main
 */

$L['cfg_walletonebilling_key'] = 'Ключ цифровой подписи при использовании md5 или sha1';

$L['walletonebilling_title'] = 'Walletone';

$L['walletonebilling_formtext'] = 'Сейчас вы будете перенаправлены на сайт платежной системы Walletone для проведения оплаты. Если этого не произошло, нажмите кнопку "Перейти к оплате".';
$L['walletonebilling_formbuy'] = 'Перейти к оплате';
$L['walletonebilling_formbuy_btnclass'] = 'btn btn-succes';

$L['walletonebilling_result_title'] = 'Результат операции оплаты';
$L['walletonebilling_result_paid'] = 'Оплата прошла успешно. ';
$L['walletonebilling_result_fail'] = 'Оплата не произведена! Пожалуйста, повторите попытку. Если ошибка повторится, обратитесь к администратору сайта';

$L['walletonebilling_redirect_text'] = 'Сейчас произойдет редирект на страницу оплаченной услуги. Если этого не произошло, перейдите по <a href="%1$s">ссылке</a>.';

$L['walletonebilling_bonus_title'] = 'Бонус за пополнение счета';