<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('alfabank', 'plug');

$cot_billings['alfabank'] = array(
	'plug' => 'alfabank',
	'title' => $L['alfabank_title'],
	'icon' => $cfg['plugins_dir'] . '/alfabank/images/alfabank.jpg'
);