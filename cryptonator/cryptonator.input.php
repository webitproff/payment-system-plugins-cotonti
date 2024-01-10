<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=input
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

$r = cot_import('r', 'G', 'ALP');

if ($r == 'cryptonator')
{
	$cfg['referercheck'] = false;
	define('COT_NO_ANTIXSS', TRUE);
}
?>