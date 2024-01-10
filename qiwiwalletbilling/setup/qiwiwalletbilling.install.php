<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');

global $db, $db_payments;

if (!$db->fieldExists($db_payments, "pay_isqiwiwallet"))
{
	$db->query("ALTER TABLE `$db_payments` ADD COLUMN `pay_isqiwiwallet` int(1) DEFAULT 0");
}

if (!$db->fieldExists($db_payments, "pay_qiwiwallet_id"))
{
	$db->query("ALTER TABLE `$db_payments` ADD COLUMN `pay_qiwiwallet_id` varchar(24) collate utf8_unicode_ci default NULL DEFAULT ''");
}
