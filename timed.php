<?php

require_once "cfg/config.php";

/** For php CLI
 * so you can do php timed.php "outlet=5" on the commandline
 */

if (isset($argv[1])) {
    parse_str($argv[1], $_GET);
}

if ( !isset($_GET['outlet']) )
{
	echo "no outlet";
	die();
}
else
{
	if ( !in_array($_GET['outlet'], ['1','2','3','4','5']) )
    {
		echo "Unknown outlet";
		die();
	}
}

// Path to the codesend binary (current directory is the default)
$codeSendPath = __DIR__ . '/app/codesend';

$codeSendPinName = 'GPIO_GEN0';
// This "PIN" is not the first pin on the Raspberry Pi GPIO header!
// This is actualy PIN 11 which is named GPIO17 or GPIO_GEN0
$codeSendPIN = str_replace('GPIO_GEN', '', $codeSendPinName);

// Pulse length depends on the RF outlets you are using. Use RFSniffer to see what pulse length your device uses.
$codeSendPulseLength = "333";

if (!file_exists($codeSendPath)) {
    error_log("$codeSendPath is missing, please edit the script", 0);
    die(json_encode(['success' => false]));
}

$outlet = $_GET['outlet'];
$outletStatus = 'off';

if ($outlet == "5") {
    $codesToToggle = array_column(pi\config::$codes, $outletStatus);
} else {
    // One
    $codesToToggle = [pi\config::$codes[$outlet][$outletStatus]];
}
$output=[];
foreach ($codesToToggle as $codeSendCode) {
    exec($codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength, $output);
    sleep(1);
}

die(json_encode(array('success' => true, 'output' => $output)));

