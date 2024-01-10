<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('users', 'module');

global $db, $db_payments, $db_users;

if (!$db->fieldExists($db_payments, "pay_sberbank_check"))
{
	$db->query("ALTER TABLE `$db_payments` ADD COLUMN `pay_sberbank_check` int(11) DEFAULT 0");
}

if (!$db->fieldExists($db_payments, "pay_sberbank_id"))
{
	$db->query("ALTER TABLE `$db_payments` ADD COLUMN `pay_sberbank_id` varchar(255) collate utf8_unicode_ci NOT NULL DEFAULT ''");
}

if (!$db->fieldExists($db_users, "user_sberbank_binding"))
{
	$db->query("ALTER TABLE `$db_users` ADD COLUMN `user_sberbank_binding` MEDIUMTEXT collate utf8_unicode_ci NOT NULL");
}