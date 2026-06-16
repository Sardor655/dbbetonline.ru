<?php
require_once __DIR__ . '/request-context.php';

$dbbetPrimaryDomain = 'dbbetonline.ru';
$dbbetCanonicalBase = 'https://dbbetonline.ru';

$host = dbbet_request_authority();
$hostWithoutPort = preg_replace('/:\d+$/', '', $host) ?: 'localhost';
$isLocalDev = dbbet_is_local_host($hostWithoutPort);
$isIndexableHost = in_array($hostWithoutPort, [$dbbetPrimaryDomain, 'www.' . $dbbetPrimaryDomain], true);
$baseUrl = $dbbetCanonicalBase;

$kechidlinki = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$promokod = 'TEZVIP';
$instagram = 'https://www.instagram.com/dbbetofficial?';
$telegram = 'https://t.me/dbbettr';
$ioslink = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$androidlink = 'https://refpa96317.com/L?tag=d_5494100m_69585c_&site=5494100&ad=69585';
$email = 'support@dbbetonline.ru';
$indirlink = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$aviator = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$sport = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$livecasino = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$GatesofOlympus = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$SweetBonanza = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';
$TheDogHouse = 'https://refpa96317.com/L?tag=d_3130181m_11213c_&site=3130181&ad=11213';

function dbbet_lang_switch(string $currentLang = 'ru'): string {
    return '';
}

function dbbet_lang_switch_mobile(string $currentLang = 'ru'): string {
    return '';
}
?>
