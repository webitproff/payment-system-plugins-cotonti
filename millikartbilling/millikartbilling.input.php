<?php

/** 
 * [BEGIN_COT_EXT]
 * Hooks=input
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

if(($_GET['e'] == 'millikart' || $_GET['r'] == 'millikart') && $_SERVER['REQUEST_METHOD'] == 'POST' && $cfg['plugin']['millikart']['enablepost'])
{
	define('COT_NO_ANTIXSS', 1) ;
	$cfg['referercheck'] = false;
}

?>