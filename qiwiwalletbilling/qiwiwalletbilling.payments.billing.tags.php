<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.tags
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

if($pinfo['pay_id'] > 0) {
  $db->update($db_payments, array('pay_isqiwiwallet' => 0), 'pay_id='.$pinfo['pay_id']);
}

?>