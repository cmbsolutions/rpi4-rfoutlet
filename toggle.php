<?php

require_once "cfg/config.php";

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

/// Path to the codesend binary (current directory is the default)
$codeSendPath = __DIR__ . '/app/codesend';

$codeSendPinName = 'GPIO_GEN0';
// This "PIN" is not the first pin on the Raspberry Pi GPIO header!
// This is actualy PIN 11 which is named GPIO17 or GPIO_GEN0
$codeSendPIN = str_replace('GPIO_GEN', '', $codeSendPinName);

// Pulse length depends on the RF outlets you are using. Use RFSniffer to see what pulse length your device uses.
$codeSendPulseLength = "333";

if (!file_exists($codeSendPath)) {
    error_log("$codeSendPath is missing, please edit the script");
    die(json_encode(array('success' => false)));
}

$outletLight = $_POST['outletId'] ?? $_GET['outletId'];
$outletStatus = $_POST['outletStatus'] ?? $_GET['outletStatus'];
$redirect = 0;
$redirect = $_GET['redirect'];

if ($outletLight == "5") {
    $codesToToggle = array_column(pi\config::$codes, $outletStatus);
} else {
    $codesToToggle = [pi\config::$codes[$outletLight][$outletStatus]];
}
$output=[];
foreach ($codesToToggle as $codeSendCode) {
    exec($codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength, $output);
    sleep(1);
}

if ($redirect == 1) {
    header('Location: https://www.google.com');
} else {
    die(json_encode(array('success' => true, 'output' => $output)));
}
?>
