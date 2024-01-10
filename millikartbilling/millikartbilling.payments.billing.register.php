<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

$cot_billings['robox'] = array(
	'plug' => 'millikartbilling',
	'title' => 'Millikart',
	'icon' => $cfg['plugins_dir'] . '/millikartbilling/images/millikart.png'
);
?>