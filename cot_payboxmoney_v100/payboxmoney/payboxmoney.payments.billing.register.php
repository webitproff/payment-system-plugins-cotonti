<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

$cot_billings['payboxmoney'] = array(
	'plug' => 'payboxmoney',
	'title' => 'Paybox.money',
	'icon' => $cfg['plugins_dir'] . '/payboxmoney/images/payboxmoney.png'
);

?>