<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

$cot_billings['walletone'] = array(
	'plug' => 'walletonebilling',
	'title' => 'Walletone',
	'icon' => $cfg['plugins_dir'] . '/walletonebilling/images/walletonebilling.png'
);

?>