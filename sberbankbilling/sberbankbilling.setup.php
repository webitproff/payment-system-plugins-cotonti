<?php
/** ====================
 * [BEGIN_COT_EXT]
 * Code=sberbankbilling
 * Name=Сбербанк эквайринг
 * Version=1.0.0
 * Date=03.08.2018
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
 * sbrlogin=01:string:::Логин
 * sbrpass=02:string:::Пароль
 * sbrstage=03:select:1,2:1:Стадийность платежа (1-Одностадийный, 2-Двухстадийный)
 * sbrmode=04:radio::1:Включить тестовый режим
 * sbrbindings=06:radio::1:Использовать связки
 * sbrform=07:radio::1:Использовать форму на сайте
 * [END_COT_EXT_CONFIG]
 * ==================== */


defined('COT_CODE') or die('Wrong URL');
