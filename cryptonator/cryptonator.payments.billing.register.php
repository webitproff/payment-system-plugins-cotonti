<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=payments.billing.register
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('wmbilling', 'plug');

if($cfg['plugin']['cryptonator']['bitcoin']) {
    $cot_billings['bitcoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_bitcoin'] ? : "Bitcoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/bitcoin.png',
        'params' => "bitcoin",
    );
}
if($cfg['plugin']['cryptonator']['bcash']) {
    $cot_billings['bcash'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_bcash'] ? : "BitcoinCash",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/bcash.png',
        'params' => "bitcoincash",
    );
}
if($cfg['plugin']['cryptonator']['blackcoin']) {
    $cot_billings['blackcoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_blackcoin'] ? : "Blackcoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/blackcoin.png',
        'params' => "blackcoin",
    );
}
if($cfg['plugin']['cryptonator']['bytecoin']) {
    $cot_billings['bytecoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_bytecoin'] ? : "Bytecoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/bytecoin.png',
        'params' => "bytecoin",
    );
}
if($cfg['plugin']['cryptonator']['dash']) {
    $cot_billings['dash'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_dash'] ? : "Dashcoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/dash.png',
        'params' => "dash",
    );
}
if($cfg['plugin']['cryptonator']['dogecoin']) {
    $cot_billings['dogecoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_dogecoin'] ? : "Dogecoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/dogecoin.png',
        'params' => "dogecoin",
    );
}
if($cfg['plugin']['cryptonator']['emercoin']) {
    $cot_billings['emercoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_emercoin'] ? : "Emercoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/emercoin.png',
        'params' => "emercoin",
    );
}
if($cfg['plugin']['cryptonator']['ethereum']) {
    $cot_billings['ethereum'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_ethereum'] ? : "Ethereum",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/ethereum.png',
        'params' => "ethereum",
    );
}
if($cfg['plugin']['cryptonator']['litecoin']) {
    $cot_billings['litecoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_litecoin'] ? : "Litecoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/litecoin.png',
        'params' => "litecoin",
    );
}
if($cfg['plugin']['cryptonator']['monero']) {
    $cot_billings['monero'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_monero'] ? : "Monero",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/monero.png',
        'params' => "monero",
    );
}
if($cfg['plugin']['cryptonator']['peercoin']) {
    $cot_billings['peercoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_peercoin'] ? : "Peercoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/peercoin.png',
        'params' => "peercoin",
    );
}
if($cfg['plugin']['cryptonator']['primecoin']) {
    $cot_billings['primecoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_primecoin'] ? : "Primecoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/primecoin.png',
        'params' => "primecoin",
    );
}
if($cfg['plugin']['cryptonator']['reddcoin']) {
    $cot_billings['reddcoin'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_reddcoin'] ? : "Reddcoin",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/reddcoin.png',
        'params' => "reddcoin",
    );
}
if($cfg['plugin']['cryptonator']['ripple']) {
    $cot_billings['ripple'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_ripple'] ? : "Ripple",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/ripple.png',
        'params' => "ripple",
    );
}
if($cfg['plugin']['cryptonator']['zcash']) {
    $cot_billings['zcash'] = array(
        'plug' => 'cryptonator',
        'title' => $L['cryptonator_zcash'] ? : "Zcash",
        'icon' => $cfg['plugins_dir'] . '/cryptonator/images/zcash.png',
        'params' => "zcash",
    );
}