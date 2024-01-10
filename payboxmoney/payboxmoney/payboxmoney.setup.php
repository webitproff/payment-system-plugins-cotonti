<?php
/** ====================
 * [BEGIN_COT_EXT]
 * Code=payboxmoney
 * Name=Paybox.money
 * Version=1.0.0
 * Date=19.01.2019
 * Author=Alexeev Vlad
 * Copyright=Copyright (c) cotontidev.ru
 * Auth_guests=RW
 * Lock_guests=12345A
 * Auth_members=RW1
 * Lock_members=2345A
 * Requires_modules=payments
 * [END_COT_EXT]
 *
 * [BEGIN_COT_EXT_CONFIG]
 * pg_merchant_id=01:string:::Идентификатор проекта
 * pg_merchant_pass=02:string:::Платежный пароль
 * pg_currency=03:select:KZT,RUR,USD,EUR:KZT:Валюта, в которой указана сумма
 * pg_state_url_method=04:select:GET,POST:POST:Метод передачи данных между магазином и PayBox
 * pg_testing_mode=05:radio::1:Включить тестовый режим
 * [END_COT_EXT_CONFIG]
 * ==================== */


defined('COT_CODE') or die('Wrong URL');
