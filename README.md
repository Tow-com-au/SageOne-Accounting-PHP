# SageOne-Accounting-PHP

## 1. Overview

This is a simple API wrapper library for [**SageOne**](http://www.sageone.com) Accounting platform.

Official API Documentation & API sign up can be found at: <https://accounting.sageone.co.za/Marketing/DeveloperProgram.aspx>

Courtesy of the Tow.com.au team :) - https://www.tow.com.au

## 2. Installation

With composer, add to your composer.json :

```
{
    "require": {
        "tow-com-au/sageone-php": "dev-master"
    }
}
```

If you are new to [composer](http://getcomposer.org/), here is some simple steps to take.

1. Download *composer.phar* from the above link
2. Create a json file named *composer.json* containing just exactly the above "require" block
3. Having *composer.phar* and *composer.json* side by side, you can run the command:
```
php ./composer.phar install
```
4. The composer will create a directory named *vendor* and download all required libraries into it.

5. In the *vendor* directory, there's also a file named *autoload.php*. This is the PHP autoloader for the libraries inside. You might need to register it to you existing autoloader, or include it manually.

## 3. Usage
To login to the SageOne API you will need two things. First, an 'AuthCode', which can be generated from your login details in the format base64_encode('username:password'), and second, an API key as requested from SageOne.

#### Instantiate the main api instance

```

// You will need to include this autoload script manually
// if you don't have any autoloader setup.
include "../path/to/vendor/autoload.php"; 

// Use your login to request an API key from:
// https://accounting.sageone.co.za/Marketing/DeveloperProgram.aspx
$apiKey = '{api key goes here}';
$authCode = base64_encode('username:password');
// SageOne will provide you with a localised endpoint url:
$apiEndpoint = 'https://accounting.sageone.com.au/api/1.1.1';

$companyId = false; // We don't have this yet, first you need to list your Companies

$debug = true;

$client = new Sage($apiEndpoint, $apiKey, $authCode, $companyId, $debug);


...

```
####  List your companies
```
$result = $client->listItems('Company');
if (!empty($result)) {
	// grab the first Company ID
	$companyId = $result[0]['ID'];
	// re-instantiate with $companyId
	$client = new Sage($apiEndpoint, $apiKey, $authCode, $companyId, $debug);
}

// Create a customer
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

// Save Customer entity
$result = $client->saveItem('Customer', $customer_details);

```
