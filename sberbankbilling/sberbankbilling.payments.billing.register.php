<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

$cot_billings['sberbankbilling'] = array(
	'plug' => 'sberbankbilling',
	'title' => 'Sberbank',
	'icon' => $cfg['plugins_dir'] . '/sberbankbilling/images/sberbankbilling.png'
);

?>