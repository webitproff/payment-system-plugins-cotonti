<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=input
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

$r = cot_import('r', 'G', 'ALP');
$e = cot_import('e', 'G', 'ALP');

if(isset($_POST['r']) && $_POST['r'] == 'payboxmoney') {
  $_GET = $_POST;
  $r = 'payboxmoney';
}

if ($r == 'payboxmoney' || $e == 'payboxmoney')
{
	$cfg['referercheck'] = false;
	define('COT_NO_ANTIXSS', TRUE);
}

?>