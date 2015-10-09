<?php

require_once('../lib/Sage.php');

// NOTE: you will have a different endpoint depending on your country
$apiEndpoint = 'https://accounting.sageone.com.au/api/1.1.1';
$apiKey = '{api key goes here}';
$authCode = base64_encode('username:password');
$companyId = 1;
$debug = true;
$sage = new Sage($apiEndpoint, $apiKey, $authCode, $companyId, $debug);

$result = $sage->listItems('Company');

echo '<pre>';
print_r($result);

?>