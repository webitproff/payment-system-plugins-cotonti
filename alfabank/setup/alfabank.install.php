<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('users', 'module');

global $db_users, $db_payments;

if (!$db->fieldExists($db_users, "user_alfabank"))
{
	$db->query("ALTER TABLE `$db_users` ADD COLUMN `user_alfabank` varchar(255) NOT NULL DEFAULT ''");
}

if (!$db->fieldExists($db_payments, "pay_alfabankid"))
{
	$db->query("ALTER TABLE `$db_payments` ADD COLUMN `pay_alfabankid` varchar(255) NOT NULL DEFAULT ''");
}
?>