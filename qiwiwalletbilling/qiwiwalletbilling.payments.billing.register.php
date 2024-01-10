<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

$cot_billings['qiwiwalletbilling'] = array(
	'plug' => 'qiwiwalletbilling',
	'title' => 'Пополнение Qiwi Кошелька',
	'icon' => $cfg['plugins_dir'] . '/qiwiwalletbilling/images/qiwiwalletbilling.png'
);

?>