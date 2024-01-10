<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('millikartbilling', 'plug');

/*
$payment = new Millikart($cfg['plugin']['millikartbilling'], $pinfo['pay_summ'], $pid, $pinfo['pay_desc']);
$reference = $payment->signature();

$cref = strtoupper($cref);

// проверка корректности подписи
if ($reference != $cref)
{
  cot_payments_updatestatus($pid_id, 'paid');
}
*/

?>