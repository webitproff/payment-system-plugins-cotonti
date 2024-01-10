<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.first
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

if(!empty($pid)) {
  $pay_type_split = explode('_', $billservice);

  if(!empty($pay_type_split[0]) && $pay_type_split[0] == 'unitpay') {
    require_once cot_incfile('unitpay', 'plug');
    $billservice = 'unitpay';
    if(empty($paymentType)) $paymentType = $pay_type_split[1];
  }
}