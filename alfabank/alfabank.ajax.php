<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('alfabank', 'plug');
require_once cot_incfile('payments', 'module');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['orderId']) && !empty($_GET['orderId']))
{
	 $pinfo = $db->query("SELECT * FROM $db_payments
			WHERE pay_alfabankid='" . $_GET['orderId'] . "'
					AND pay_status='process'")->fetch();

   $status = 'fail';
   $pid=0;

   if($pinfo['pay_id'] > 0 && $pinfo['pay_alfabankid'] == $_GET['orderId']) {
     $pid = $pinfo['pay_id'];
     $data = array(
       'userName' => $cfg['plugin']['alfabank']['alfabank_username'],
       'password' => $cfg['plugin']['alfabank']['alfabank_password'],
       'orderId' => $pinfo['pay_alfabankid']
     );

     $response = alfabank_gateway('getOrderStatus.do', $data);

     if($response['OrderStatus'] == 1 || $response['OrderStatus'] == 2) {
      if (cot_payments_updatestatus($pinfo['pay_id'], 'paid')) {

        if($pinfo['pay_userid'] > 0) {
          $urr = $db->query("SELECT * FROM $db_users WHERE user_id=".$pinfo['pay_userid'])->fetch();
          if($urr['user_id'] > 0) {
            $data = array(
              'userName' => $cfg['plugin']['alfabank']['alfabank_username'],
              'password' => $cfg['plugin']['alfabank']['alfabank_password'],
              'clientId' => $urr['user_id']
            );
            $response = alfabank_gateway('getBindings.do', $data);
            if($response['errorCode'] == 0 && !empty($response['bindings']['bindingId'])) $db->update($db_users, array('user_alfabank' => $response['bindingId']), 'user_id='.$urr['user_id']);
          }
        }

        $status = 'success';
      }
     }
   }

   cot_redirect(cot_url('plug', 'e=alfabank&m='.$status.'&pid='.$pid, '', true));
}
else
{
  cot_redirect(cot_url('payments', 'm=balance', '', true));
}
?>