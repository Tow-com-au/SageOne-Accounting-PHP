<?php

require_once('../src/Sage.php');

// NOTE: you will have a different endpoint depending on your country
$apiEndpoint = 'https://accounting.sageone.com.au/api/1.1.1';
$apiKey = '{api key goes here}';
$authCode = base64_encode('username:password');
$companyId = 1;
$debug = true;
$sage = new Sage($apiEndpoint, $apiKey, $authCode, $companyId, $debug);

echo '<pre>';

$offset = 0;
$done = false;
while ($done == false) {
    echo 'offset: '.$offset.PHP_EOL;
    if ($offset > 0) {
        $result = $sage->listItems('Customer', ['$skip' => $offset]);
    }
    else {
        $result = $sage->listItems('Customer');
    }

    if (!empty($result['Results'])) {
        echo 'checking result offset: '.$offset.PHP_EOL;
        foreach ($result['Results'] as $c) {
        	print_r($c);
        }

        $offset += 100;
    }
    else {
         if ($debug) echo 'ran out of customers to check'.PHP_EOL;
        $done = true;
    }

    sleep(1);
}


?>