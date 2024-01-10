<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
**/

defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('unitpay', 'plug');

$cot_billings['unitpay'] = array(
	'plug' => 'unitpay',
	'title' => 'UnitPay',
	'icon' => $cfg['plugins_dir'] . '/unitpay/unitpay.png',
  'extra' => array(
    array(
      'title' => 'Карты',
      'variants' => array(
        array(
          'active' => 1,
          'code' => 'card',
          'operator' => '',
          'title' => $L['unitpay_card'],
          'img' => 'plugins/unitpay/images/card.svg',
        ),
      )
    ),
        /* array(
      'title' => 'Скоро будет доступно',
      'variants' => array(
        array(
          'active' => 0,
          'code' => 'webmoney',
          'operator' => '',
          'title' => $L['unitpay_webmoney'],
          'img' => 'plugins/unitpay/images/webmoney.svg',
        ),
        array(
          'active' => 0,
          'code' => 'alfaClick',
          'operator' => '',
          'title' => $L['unitpay_alfaClick'],
          'img' => 'plugins/unitpay/images/alfaClick.svg',
        ),
        array(
          'active' => 0,
          'code' => 'mc',
          'operator' => 'mts',
          'title' => $L['unitpay_mts'],
          'img' => 'plugins/unitpay/images/mts.svg',
        ),
        array(
          'active' => 0,
          'code' => 'mc',
          'operator' => 'mf',
          'title' => $L['unitpay_mf'],
          'img' => 'plugins/unitpay/images/megafon.svg',
        ),
        array(
          'active' => 0,
          'code' => 'mc',
          'operator' => 'beeline',
          'title' => $L['unitpay_beeline'],
          'img' => 'plugins/unitpay/images/beeline.svg',
        ),
        array(
          'active' => 0,
          'code' => 'mc',
          'operator' => 'tele2',
          'title' => $L['unitpay_t2'],
          'img' => 'plugins/unitpay/images/tele2.svg',
        ),
        array(
          'active' => 0,
          'code' => 'yandex',
          'operator' => '',
          'title' => $L['unitpay_yandex'],
          'img' => 'plugins/unitpay/images/yandex.svg',
        ),
        array(
          'active' => 0,
          'code' => 'qiwi',
          'operator' => '',
          'title' => $L['unitpay_qiwi'],
          'img' => 'plugins/unitpay/images/qiwi.svg',
        ),

   array(
      'title' => 'Мобильный телефон',
      'variants' => array(
       */

  ),
);

?>