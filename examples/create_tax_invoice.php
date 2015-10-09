<?php

require_once('../lib/Sage.php');

// NOTE: you will have a different endpoint depending on your country
$apiEndpoint = 'https://accounting.sageone.com.au/api/1.1.1';
$apiKey = '{api key goes here}';
$authCode = base64_encode('username:password');
$companyId = 1;
$debug = true;
$sage = new Sage($apiEndpoint, $apiKey, $authCode, $companyId, $debug);

echo '<pre>';

$date = date('Y-m-d');
$statusId = 4; // void
$statusId = 3; // paid
$statusId = 2; // partially paid
$statusId = 1; // unpaid
$customerId = 1; // must be valid ID of Customer

$invoice_details = [
    'Date' => $date,
    'DueDate' => $date,
    "CustomerId" => $customerId,
    'StatusId' => $statusId,
    'Inclusive' => 1,
    'Total' => 3.5,
    'AmountDue' => 3.5,
    'Lines' => [
    	[
    		'SelectionId' => 1, // must be valid ID of Item
    		"Description" => "Item Price",
    		"Quantity" => "1.0",
    		"UnitPriceInclusive" => 3.5, // THREE-FIFTY?
    		"TaxPercentage" => 0.1, // GST?
    	],
    ],
    'DocumentNumber' => "INVOICE-1",
    'Reference' => 'Test Ref 1'
];

$result = $sage->saveItem('TaxInvoice', $invoice_details);

print_r($result);


?>