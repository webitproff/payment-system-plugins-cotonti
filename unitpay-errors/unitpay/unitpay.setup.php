<?php defined('COT_CODE') or die('Wrong URL');
/**
 * [BEGIN_COT_EXT]
 * Code=unitpay
 * Name=UnitPay
 * Category=Payments
 * Description=UnitPay прием оплат/выплаты
 * Version=1.0.1
 * Date=26.11.2018
 * Author=Alexeev Vlad
 * Copyright=(с) cotontidev.ru
 * Auth_guests=R
 * Lock_guests=12345A
 * Auth_members=RW
 * Lock_members=12345A
 * Requires_modules=payments
 * [END_COT_EXT]
 *
 * [BEGIN_COT_EXT_CONFIG]
 * unitpay_login=01:string:::Логин
 * unitpay_partnerkey=02:string:::Публичный ключ
 * unitpay_pkey=03:string:::Публичный ключ
 * unitpay_purse=04:string:::Номер проекта
 * unitpay_skey=05:string:::Секретный ключ
 * unitpay_payouts=06:radio::1:Включить автоматические выплаты
 * [END_COT_EXT_CONFIG]
**/