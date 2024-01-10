<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('users', 'module');

global $db_users, $db_payments;

global $db_users;

if ($db->fieldExists($db_users, "user_alfabank"))
{
	$db->query("ALTER TABLE `$db_users` DROP COLUMN `user_alfabank`");
}

if ($db->fieldExists($db_payments, "pay_alfabankid"))
{
	$db->query("ALTER TABLE `$db_payments` DROP COLUMN `pay_alfabankid`");
}
?>