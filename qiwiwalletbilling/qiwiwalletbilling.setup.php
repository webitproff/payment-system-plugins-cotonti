<?php

/**
 * [BEGIN_COT_EXT]
 * Code=qiwiwalletbilling
 * Name=Qiwi Wallet billing
 * Category=Payments
 * Description=Qiwi wallet billing system
 * Version=1.0.1
 * Date=24.01.2019
 * Author=Alexeev Vlad
 * Copyright=&copy; cotontidev.ru
 * Notes=
 * Auth_guests=R
 * Lock_guests=12345A
 * Auth_members=RW
 * Lock_members=12345A
 * Requires_modules=payments
 * [END_COT_EXT]
 *
 * [BEGIN_COT_EXT_CONFIG]
 * qw_wallet=01:string:::Номер кошелька, для которого получен токен доступа (с международным префиксом без +)
 * qw_api=02:select:token,password:token:Тип авторизации
 * qw_pass=03:string:::Пароль кошелька
 * qw_token=04:string:::Токен кошелька
 * qw_rate=05:string::1:Соотношение суммы к валюте сайта
 * qw_currency=06:string::643:Код валюты (643 - RUB)
 * qw_webhook=07:custom:cot_qiwiwalletbilling_cfg_webhook()::Проверка Web-hook
 * [END_COT_EXT_CONFIG]
 */

?>