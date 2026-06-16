<?php
require_once __DIR__ . '/request-context.php';

$dbbetPrimaryDomain = 'dbbetonline.ru';
$dbbetCanonicalBase = 'https://dbbetonline.ru';

$host = dbbet_request_authority();
$hostWithoutPort = preg_replace('/:\d+$/', '', $host) ?: 'localhost';
$isLocalDev = dbbet_is_local_host($hostWithoutPort);
$isIndexableHost = in_array($hostWithoutPort, [$dbbetPrimaryDomain, 'www.' . $dbbetPrimaryDomain], true);
$baseUrl = $dbbetCanonicalBase;

$kechidlinki = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$promokod = 'vexacdb';
$instagram = 'https://www.instagram.com/dbbetofficial?';
$telegram = 'https://t.me/dbbettr';
$ioslink = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$androidlink = 'https://refpa96317.com/L?tag=d_5494100m_69585c_&site=5494100&ad=69585';
$email = 'support@dbbetonline.ru';
$indirlink = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$aviator = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$sport = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$livecasino = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$GatesofOlympus = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$SweetBonanza = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';
$TheDogHouse = 'https://refpa96317.com/L?tag=d_5494100m_11213c_&site=5494100&ad=11213&r=registration';

function dbbet_lang_switch(string $currentLang = 'ru'): string {
    return '';
}

function dbbet_lang_switch_mobile(string $currentLang = 'ru'): string {
    return '';
}
?>
