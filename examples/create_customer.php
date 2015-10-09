<?php

require_once('../lib/Sage.php');

// NOTE: you will have a different endpoint depending on your country
$apiEndpoint = 'https://accounting.sageone.com.au/api/1.1.1';
$apiKey = '{api key goes here}';
$authCode = base64_encode('username:password');
$companyId = 1;
$debug = true;
$sage = new Sage($apiEndpoint, $apiKey, $authCode, $companyId, $debug);

$customer_details = [
    'Name' => 'Test Customer',
    'Mobile' => '555-555',
    'CommunicationMethod' => 0,
    'Email' => 'test@test.com',
    'PostalAddress01' => '1 test street',
    'PostalAddress02' => 'testville',
    'PostalAddress03' => 'QLD',
    'PostalAddress04' => 4000,
    'PostalAddress05' => 'Australia',
    'TaxReference' => 'Customer 1',
];

$result = $sage->saveItem('Customer', $customer_details);

echo '<pre>';
print_r($result);


?>