<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
**/

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('payments', 'module');
require_once cot_incfile('unitpay', 'plug');

function getResponseSuccess($message) {
  cot_log('unitpay Response success='.$message);
  return json_encode(array(
      "result" => array(
        "message" => $message
      )
  ));
}

function getResponseError($message) {
  cot_log('unitpay Response error='.$message);
  return json_encode(array(
      "error" => array(
        //"code" => -32000,
        "message" => $message
      )
  ));
}

if (isset($_GET['method']) && $_GET['method'] == 'check')
{
	if (isset($_GET['params']['account']) && (preg_match('/^\d+$/', $_GET['params']['account']) == 1 || $_GET['params']['account'] == 'test'))
	{
		$pinfo = $db->query("SELECT * FROM $db_payments
			WHERE pay_id='" . $_GET['params']['account'] . "' AND pay_status='process'")->fetch();

        if($_GET['params']['account'] == 'test') {
            echo getResponseSuccess('Check is successful');
            die();
		} else if (empty($pinfo))
		{
			echo getResponseError('Payment not found');
            die();
		}
		else
		{
			$amount = number_format($pinfo['pay_summ'], 2, '.', '');
			if ($_GET['params']['account'] == $pinfo['pay_id'] && $_GET['params']['projectId'] == $cfg['plugin']['unitpay']['unitpay_purse'] && $_GET['params']['orderSum'] == $amount)
			{
				echo getResponseSuccess('Check is successful');
                die();
			}
			else
			{
				echo getResponseError('Inconsistent parameters');
      }
		}
	}
	else
	{
		echo getResponseError('Inconsistent parameters');
	}
}
elseif (isset($_GET['method']) && $_GET['method'] == 'pay')
{
	if (isset($_GET['params']['account']) && preg_match('/^\d+$/', $_GET['params']['account']) == 1)
	{
		$pinfo = $db->query("SELECT * FROM $db_payments
			WHERE pay_id=" . (int)$_GET['params']['account'])->fetch();

		if (empty($pinfo))
		{
			echo getResponseError('Payment not found');
            die();
		}
		else
		{
			$amount = number_format($pinfo['pay_summ'], 2, '.', '');

      $chkstring = cot_unitpay_getSignature($_GET['method'], $_GET['params'], $cfg['plugin']['unitpay']['unitpay_skey']);
			$hash_check = ($_GET['params']['sign'] == $md5sum);

			if ($_GET['params']['account'] == $pinfo['pay_id']
       && $_GET['params']['projectId'] == $cfg['plugin']['unitpay']['unitpay_purse']
       && $_GET['params']['orderSum'] == $amount
       && $hash_check)
			{
        if($pinfo['pay_status'] == 'paid' || $pinfo['pay_status'] == 'done')
        {
          echo getResponseSuccess('Payment already is successful');
          die();
        }
        elseif (cot_payments_updatestatus($pinfo['pay_id'], 'paid'))
				{
					echo getResponseSuccess('Payment is successful');
                    die();
				}
				else
				{
					echo getResponseError('Payment failed');
                    die();
				}
			}
			else
			{
				echo getResponseError('Inconsistent parameters');
			}
		}
	}
	else
	{
		echo getResponseError('Inconsistent parameters');
	}
}
else
{
	echo getResponseError('Inconsistent parameters');
}
?>