<?php

/* ====================
 * [BEGIN_COT_EXT]
 * Code=roboxbilling
 * Name=Roboxbilling + Фискализация
 * Category=Payments
 * Description=Robox billing system
 * Version=1.0.6-0.0.1
 * Date=
 * Author=CMSWorks Team, Alexeev Vlad
 * Copyright=Copyright (c) CMSWorks.ru, cotontidev.ru
 * Notes=
 * Auth_guests=RW
 * Lock_guests=12345A
 * Auth_members=RW
 * Lock_members=12345A
 * Requires_modules=payments
 * [END_COT_EXT]
 *
 * [BEGIN_COT_EXT_CONFIG]
 * mrh_login=01:string::demo:Логин в Робокассе
 * mrh_pass1=02:string:::Пароль #1 в Робокассе
 * mrh_pass2=03:string:::Пароль #2 в Робокассе
 * testmode=04:radio::1:Тестовый режим
 * enablepost=05:radio::0:Разрешить post запросы
 * rate=06:string::1:Соотношение суммы к валюте сайта
 * fiscon=07:radio:::Включить режим фискализации
 * fisctax=08:custom:cot_roboxbilling_cfg_select():osn:Система налогообложения
 * fiscpayobject=09:custom:cot_roboxbilling_cfg_select():commodity:Признак предмета расчёта
 * fiscnds=10:custom:cot_roboxbilling_cfg_select():none:Налог
 * [END_COT_EXT_CONFIG]
 */