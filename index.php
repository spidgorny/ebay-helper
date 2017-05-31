<?php
/**
 * Copyright 2016 David T. Sadler
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


error_reporting(E_ALL);
ini_set('display_errors', true);

/**
 * Include the SDK by using the autoloader from Composer.
 */
require __DIR__.'/vendor/autoload.php';

/**
 * Include the configuration values.
 *
 * Ensure that you have edited the configuration.php file
 * to include your application keys.
 */
$config = require __DIR__.'/configuration.php';

/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use DTS\eBaySDK\Credentials\Credentials;
use DTS\eBaySDK\Credentials\CredentialsProvider;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

/**
 * Create the service object.
 */
$credentials = $config['sandbox']['credentials'];
//print_r($credentials);
$credentialsProvider = new Credentials(
	$credentials[CredentialsProvider::ENV_APP_ID],
	$credentials[CredentialsProvider::ENV_CERT_ID],
	$credentials[CredentialsProvider::ENV_DEV_ID]
);
//echo 'AppID: ', $credentialsProvider->getAppId(), PHP_EOL;
$credentialsProviderFunc = function () use ($credentialsProvider) {
	return $credentialsProvider;
};
$service = new Services\FindingService([
	'credentials' => $credentialsProviderFunc,
	'globalId'    => Constants\GlobalIds::DE,
	'debug' => false,
	'sandbox' => true,
	'http_errors' => true,
]);
//print_r($service->getConfig('credentials'));

/**
 * Create the request object.
 */
$request = new Types\FindItemsByKeywordsRequest();

/**
 * Assign the keywords.
 */
$request->keywords = 'Z3';

/**
 * Send the request.
 */
try {
	$response = $service->findItemsByKeywords($request);

	/**
	 * Output the result of the search.
	 */
	if (isset($response->errorMessage)) {
		foreach ($response->errorMessage->error as $error) {
			printf(
				"%s: %s\n\n",
				$error->severity=== Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
				$error->message
			);
		}
	}

	if ($response->ack !== 'Failure') {
		foreach ($response->searchResult->item as $item) {
			printf(
				"(%s) %s: %.2f\t%s\n",
				$item->itemId,
				$item->sellingStatus->currentPrice->currencyId,
				$item->sellingStatus->currentPrice->value,
				$item->title
			);
		}
	}

} catch (GuzzleHttp\Exception\ServerException $e) {
	print_r($e->getRequest()->getHeaders());
	print_r($e->getRequest()->getBody()->getContents());
	echo $e->getResponse()->getBody()->getContents();
}

