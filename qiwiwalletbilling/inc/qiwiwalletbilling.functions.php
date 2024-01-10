<?php

defined('COT_CODE') or die('Wrong URL');

global $qiwiwalletbilling_methods, $qiwiwalletbilling_methods_auth, $qiwiwalletbilling_currency, $qiwiwalletbilling_webhookurl, $qiwiwalletbilling_token;
$qiwiwalletbilling_methods = array(
  'url' => 'https://edge.qiwi.com/',
  'payment-history' => 'payment-history/v2/persons/{$qw_wallet}/payments',
  'payment-notifier' => 'payment-notifier/v1/hooks',
  'payment-notifier-active' => 'payment-notifier/v1/hooks/active',
  'payment-notifier-delete' => 'payment-notifier/v1/hooks/{$hookId}',
  'payment-notifier-key' => 'payment-notifier/v1/hooks/{$hookId}/key',
  'payment-notifier-test' => 'payment-notifier/v1/hooks/test'
);
$qiwiwalletbilling_methods_auth = array(
  'url' => 'https://auth.qiwi.com/',
  'auth_1' => 'cas/tgts',
  'auth_2' => 'cas/sts',
  'auth_2_service' => 'http://t.qiwi.com/j_spring_cas_security_check',
);
$qiwiwalletbilling_currency = json_decode('{"036":{"char":"AUD","name":"\u0410\u0432\u0441\u0442\u0440\u0430\u043b\u0438\u0439\u0441\u043a\u0438\u0439 \u0434\u043e\u043b\u043b\u0430\u0440"},"944":{"char":"AZN","name":"\u0410\u0437\u0435\u0440\u0431\u0430\u0439\u0434\u0436\u0430\u043d\u0441\u043a\u0438\u0439 \u043c\u0430\u043d\u0430\u0442"},"826":{"char":"GBP","name":"\u0424\u0443\u043d\u0442 \u0441\u0442\u0435\u0440\u043b\u0438\u043d\u0433\u043e\u0432 \u0421\u043e\u0435\u0434\u0438\u043d\u0435\u043d\u043d\u043e\u0433\u043e \u043a\u043e\u0440\u043e\u043b\u0435\u0432\u0441\u0442\u0432\u0430"},"051":{"char":"AMD","name":"\u0410\u0440\u043c\u044f\u043d\u0441\u043a\u0438\u0445 \u0434\u0440\u0430\u043c\u043e\u0432"},"933":{"char":"BYN","name":"\u0411\u0435\u043b\u043e\u0440\u0443\u0441\u0441\u043a\u0438\u0439 \u0440\u0443\u0431\u043b\u044c"},"975":{"char":"BGN","name":"\u0411\u043e\u043b\u0433\u0430\u0440\u0441\u043a\u0438\u0439 \u043b\u0435\u0432"},"986":{"char":"BRL","name":"\u0411\u0440\u0430\u0437\u0438\u043b\u044c\u0441\u043a\u0438\u0439 \u0440\u0435\u0430\u043b"},"348":{"char":"HUF","name":"\u0412\u0435\u043d\u0433\u0435\u0440\u0441\u043a\u0438\u0445 \u0444\u043e\u0440\u0438\u043d\u0442\u043e\u0432"},"344":{"char":"HKD","name":"\u0413\u043e\u043d\u043a\u043e\u043d\u0433\u0441\u043a\u0438\u0445 \u0434\u043e\u043b\u043b\u0430\u0440\u043e\u0432"},"208":{"char":"DKK","name":"\u0414\u0430\u0442\u0441\u043a\u0430\u044f \u043a\u0440\u043e\u043d\u0430"},"840":{"char":"USD","name":"\u0414\u043e\u043b\u043b\u0430\u0440 \u0421\u0428\u0410"},"978":{"char":"EUR","name":"\u0415\u0432\u0440\u043e"},"356":{"char":"INR","name":"\u0418\u043d\u0434\u0438\u0439\u0441\u043a\u0438\u0445 \u0440\u0443\u043f\u0438\u0439"},"398":{"char":"KZT","name":"\u041a\u0430\u0437\u0430\u0445\u0441\u0442\u0430\u043d\u0441\u043a\u0438\u0445 \u0442\u0435\u043d\u0433\u0435"},"124":{"char":"CAD","name":"\u041a\u0430\u043d\u0430\u0434\u0441\u043a\u0438\u0439 \u0434\u043e\u043b\u043b\u0430\u0440"},"417":{"char":"KGS","name":"\u041a\u0438\u0440\u0433\u0438\u0437\u0441\u043a\u0438\u0445 \u0441\u043e\u043c\u043e\u0432"},"156":{"char":"CNY","name":"\u041a\u0438\u0442\u0430\u0439\u0441\u043a\u0438\u0445 \u044e\u0430\u043d\u0435\u0439"},"498":{"char":"MDL","name":"\u041c\u043e\u043b\u0434\u0430\u0432\u0441\u043a\u0438\u0445 \u043b\u0435\u0435\u0432"},"578":{"char":"NOK","name":"\u041d\u043e\u0440\u0432\u0435\u0436\u0441\u043a\u0438\u0445 \u043a\u0440\u043e\u043d"},"985":{"char":"PLN","name":"\u041f\u043e\u043b\u044c\u0441\u043a\u0438\u0439 \u0437\u043b\u043e\u0442\u044b\u0439"},"946":{"char":"RON","name":"\u0420\u0443\u043c\u044b\u043d\u0441\u043a\u0438\u0439 \u043b\u0435\u0439"},"960":{"char":"XDR","name":"\u0421\u0414\u0420 (\u0441\u043f\u0435\u0446\u0438\u0430\u043b\u044c\u043d\u044b\u0435 \u043f\u0440\u0430\u0432\u0430 \u0437\u0430\u0438\u043c\u0441\u0442\u0432\u043e\u0432\u0430\u043d\u0438\u044f)"},"702":{"char":"SGD","name":"\u0421\u0438\u043d\u0433\u0430\u043f\u0443\u0440\u0441\u043a\u0438\u0439 \u0434\u043e\u043b\u043b\u0430\u0440"},"972":{"char":"TJS","name":"\u0422\u0430\u0434\u0436\u0438\u043a\u0441\u043a\u0438\u0445 \u0441\u043e\u043c\u043e\u043d\u0438"},"949":{"char":"TRY","name":"\u0422\u0443\u0440\u0435\u0446\u043a\u0430\u044f \u043b\u0438\u0440\u0430"},"934":{"char":"TMT","name":"\u041d\u043e\u0432\u044b\u0439 \u0442\u0443\u0440\u043a\u043c\u0435\u043d\u0441\u043a\u0438\u0439 \u043c\u0430\u043d\u0430\u0442"},"860":{"char":"UZS","name":"\u0423\u0437\u0431\u0435\u043a\u0441\u043a\u0438\u0445 \u0441\u0443\u043c\u043e\u0432"},"980":{"char":"UAH","name":"\u0423\u043a\u0440\u0430\u0438\u043d\u0441\u043a\u0438\u0445 \u0433\u0440\u0438\u0432\u0435\u043d"},"203":{"char":"CZK","name":"\u0427\u0435\u0448\u0441\u043a\u0438\u0445 \u043a\u0440\u043e\u043d"},"752":{"char":"SEK","name":"\u0428\u0432\u0435\u0434\u0441\u043a\u0438\u0445 \u043a\u0440\u043e\u043d"},"756":{"char":"CHF","name":"\u0428\u0432\u0435\u0439\u0446\u0430\u0440\u0441\u043a\u0438\u0439 \u0444\u0440\u0430\u043d\u043a"},"710":{"char":"ZAR","name":"\u042e\u0436\u043d\u043e\u0430\u0444\u0440\u0438\u043a\u0430\u043d\u0441\u043a\u0438\u0445 \u0440\u044d\u043d\u0434\u043e\u0432"},"410":{"char":"KRW","name":"\u0412\u043e\u043d \u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0438 \u041a\u043e\u0440\u0435\u044f"},"392":{"char":"JPY","name":"\u042f\u043f\u043e\u043d\u0441\u043a\u0438\u0445 \u0438\u0435\u043d"},"643":{"char":"RUB","name":"\u0420\u043e\u0441\u0441\u0438\u0439\u0441\u043a\u0438\u0439 \u0440\u0443\u0431\u043b\u044c"}}', 1);
$qiwiwalletbilling_webhookurl = COT_ABSOLUTE_URL . 'index.php?r=qiwiwalletbilling';

require_once cot_langfile('qiwiwalletbilling', 'plug');

function cot_qiwiwalletbilling_get_key($pinfo = array()) {
  $key = (int)((is_array($pinfo) && $pinfo['pay_id'] > 0) ? substr(($pinfo['pay_cdate'] + ($pinfo['pay_id'] * 1000)), -6) : '');
  if($key < 100000) $key += 100000;
  return (string)$key;
}

function cot_qiwiwalletbilling_rest_send($method = '', $parameters = array(), $request = 'GET', $headers = array("Accept: application/json", "Content-Type: application/json"), $initauth = 0) {
  global $cfg, $qiwiwalletbilling_methods, $qiwiwalletbilling_methods_auth, $qiwiwalletbilling_token;

  $result = array(
    'success' => 0,
    'errorCode' => '',
    'error' => 'Укажите '.($cfg['plugin']['qiwiwalletbilling']['qw_api'] == 'token' ? 'токен' : 'пароль').' в конфигурации плагина',
    'data' => array()
  );

  if($cfg['plugin']['qiwiwalletbilling']['qw_api'] == 'password') {
    //метод аунтефикации
    if($initauth) {
      $url = (!empty($qiwiwalletbilling_methods_auth['url_'.$initauth]) ? $qiwiwalletbilling_methods_auth['url_'.$initauth] : $qiwiwalletbilling_methods_auth['url']);

      $url .= $qiwiwalletbilling_methods_auth[$method];
      $url .= (count($parameters) > 0 ? '?'.http_build_query($parameters) : '');

      $headers = array(
        'Accept: application/vnd.qiwi.sso-v1+json',
        'Content-Type: application/json; charset=UTF-8',
        'Host: auth.qiwi.com',
        'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36'
      );

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

      $result['data'] = curl_exec($ch);
      curl_close($ch);

      if(!empty($result['data'])) {
        $result['data'] = json_decode($result['data'], 1);

        if(is_array($result['data'])) {
          if(in_array($initauth, array(1, 2)) && is_array($result['data']['entity']) && !empty($result['data']['entity']['ticket'])) {
            $result['data'] = $result['data']['entity'];
            $result['error'] = '';
            $result['success'] = 1;
          } else {
            $result['data']['ticket'] = '';
            $result['error'] = (isset($result['data']['error']) ? $result['data']['error'] : '');
          }
        }
      }

      return $result;
    } //запуск метода аунтефикации
    elseif(empty($qiwiwalletbilling_token)) {
      $qiwiwalletbilling_token = 'auth_error';
      $url = $qiwiwalletbilling_methods_auth['url'];

      if(!empty($cfg['plugin']['qiwiwalletbilling']['qw_wallet'])) {
        $result['error'] = 'Укажите номер кошелька (с международным префиксом без +)';

        if(!empty($cfg['plugin']['qiwiwalletbilling']['qw_pass'])) {
          $result['error'] = 'Указан не верный пароль/номер кошелька, или произошла ошибка в запросе.';

          $qiwiauth_1 = cot_qiwiwalletbilling_rest_send('auth_1', array(
              'login' => $cfg['plugin']['qiwiwalletbilling']['qw_wallet'],
              'password' => $cfg['plugin']['qiwiwalletbilling']['qw_pass']
          ), 'POST', array(), 1);

          if($qiwiauth_1['success'] && !empty($qiwiauth_1['data']['ticket'])) {
            $qiwiauth_2 = cot_qiwiwalletbilling_rest_send('auth_2', array(
              'service' => $qiwiwalletbilling_methods_auth['auth_2_service'],
              'ticket' => $qiwiauth_1['data']['ticket']
            ), 'POST', array(), 2);

            if($qiwiauth_2['success'] && !empty($qiwiauth_2['data']['ticket'])) {
              $qiwiwalletbilling_token = $qiwiauth_2['data']['ticket'];
              $cfg['plugin']['qiwiwalletbilling']['qw_token'] = $qiwiwalletbilling_token;
            }
          }

          $initauth = 0;
        }
      }
    }
  } else {
    $initauth = 0;
    $qiwiwalletbilling_token = 'work_with_token';
  }

  if(!$initauth && !empty($qiwiwalletbilling_token) && $qiwiwalletbilling_token != 'auth_error') {
    $url = $qiwiwalletbilling_methods['url'];

    if(!empty($cfg['plugin']['qiwiwalletbilling']['qw_wallet'])) {
      $result['error'] = 'Укажите номер кошелька, для которого получен токен доступа (с международным префиксом без +)';

      if(!empty($cfg['plugin']['qiwiwalletbilling']['qw_token'])) {
        $result['error'] = 'Указан не верный токен/номер кошелька, или произошла ошибка в запросе.';

        $for_rc = $parameters;
        $for_rc['qw_wallet'] = $cfg['plugin']['qiwiwalletbilling']['qw_wallet'];
        $url .= cot_rc($qiwiwalletbilling_methods[$method], $for_rc);

        if($method == 'payment-notifier-delete' || $method == 'payment-notifier-key') unset($parameters['hookId']);

        $url .= (count($parameters) > 0 ? '?'.http_build_query($parameters) : '');

        $headers[] = "authorization: ".($cfg['plugin']['qiwiwalletbilling']['qw_api'] == 'password' ? 'Token ' : 'Bearer')." ".$cfg['plugin']['qiwiwalletbilling']['qw_token'];
        if($cfg['plugin']['qiwiwalletbilling']['qw_api'] == 'password') {
          $headers[] = 'Host: edge.qiwi.com';
        }

      	$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      	$result['data'] = curl_exec($ch);
      	curl_close($ch);

        if(!empty($result['data'])) {
          $result['data'] = json_decode($result['data'], 1);
          if(is_array($result['data'])) {
            if(isset($result['data']['errorCode'])) {
              $result['errorCode'] = $result['data']['errorCode'];
              $result['error'] = $result['data']['description'];
            } else {
              $result['error'] = '';
              $result['success'] = 1;
            }
          }
        }
      }
    }
  }

	return $result;
}

function cot_qiwiwalletbilling_cfg_webhook($name, $value) {
  global $cfg, $qiwiwalletbilling_webhookurl;

  if(is_array($name)) {
    $value = $name['config_value'];
    $name = $name['config_name'];
  }

  if($cfg['plugin']['qiwiwalletbilling']['qw_api'] == 'token') {
    $newvalue = array(
      0 => '',
      1 => ''
    );
    $oldvalue = (!empty($value) ? explode('_', $value) : $newvalue);

    $needaddhook = false;
    $error = '';
    $return = '';

    if(!empty($cfg['plugin']['qiwiwalletbilling']['qw_wallet']) && !empty($cfg['plugin']['qiwiwalletbilling']['qw_token'])) {
      $error = 'Возникла ошибка при создании веб-хука. Обновите страницу.';

      $data = cot_qiwiwalletbilling_rest_send('payment-notifier-active', array(), 'GET', array());

      if($data['errorCode'] == 'hook.not.found' || !is_array($data['data'])) {
        $needaddhook = true;
      } else {
        if($data['data']['hookParameters']['url'] != $qiwiwalletbilling_webhookurl) {
          $data = cot_qiwiwalletbilling_rest_send('payment-notifier-delete', array(
            'hookId' => $data['data']['hookId'],
          ), 'DELETE', array("accept: */*"));
          $needaddhook = true;
        } else {
          $newvalue[0] = $data['data']['hookId'];
        }
      }

      if($needaddhook) {
        $data = cot_qiwiwalletbilling_rest_send('payment-notifier', array(
          'hookType' => 1,
          'param' => $qiwiwalletbilling_webhookurl,
          'txnType' => 0
        ), 'PUT', array("accept: */*"));

        if($data['success'] && isset($data['data']['hookId'])) {
          $newvalue[0] = $data['data']['hookId'];
        }
      }

      if(!empty($newvalue[0]) && $newvalue[0] != $oldvalue[0]) {
        $data = cot_qiwiwalletbilling_rest_send('payment-notifier-key', array(
          'hookId' => $newvalue[0],
        ), 'GET', array("accept: */*"));
        if($data['success'] && isset($data['data']['key'])) {
          $newvalue[1] = $data['data']['key'];
        }
      }
    } else {
      $oldvalue = array(
        0 => '',
        1 => ''
      );
      $error = 'Укажите номер кошелька и токен';
    }

    $return .= ((!empty($oldvalue[1]) || !empty($newvalue[1])) ? 'Веб-хук успешно зарегистрирован.'.(!empty($newvalue[1]) ? '<br><br><div class="alert alert-danger">Нажмите <b>обновить</b> для сохранения текущей связки с веб-хуком</div>' : '') : 'Произошла ошибка. '.$error);
    $return .= '<input type="hidden" name="'.$name.'" value="'.(!empty($newvalue[1]) ? implode('_', $newvalue) : $value).'" />';
  } else {
    $return .= 'Конфигурация Web-hook доступна только при работе с токеном ';
    $return .= '<input type="hidden" name="'.$name.'" value="" />';
  }
  return $return;
}