<?php

/**
 * Installation handler
 *
 * @package wmbilling
 * @version 1.0
 * @author CMSWorks Team
 * @copyright Copyright (c) CMSWorks.ru
 * @license BSD License
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');

global $db_payments;

if (!$db->fieldExists($db_payments, "pay_cryptonator"))
{
	$db->query("ALTER TABLE `$db_payments` ADD COLUMN `pay_cryptonator` varchar(255) NOT NULL");
}
?>