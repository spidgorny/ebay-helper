<?php

namespace EbayHelper;

use \DTS\eBaySDK\Constants;
use DTS\eBaySDK\Credentials\Credentials;
use DTS\eBaySDK\Credentials\CredentialsProvider;
use \DTS\eBaySDK\Finding\Services;
use phpFastCache\CacheManager;
use phpFastCache\Helper\Psr16Adapter;

class Config
{

	function getEbayService()
	{
		/**
		 * Include the configuration values.
		 *
		 * Ensure that you have edited the configuration.php file
		 * to include your application keys.
		 */
		$config = require __DIR__.'/../configuration.php';

		/**
		 * Create the service object.
		 */
//$credentials = $config['sandbox']['credentials'];
		$credentials = $config['production']['credentials'];
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
			'debug'       => false,
			//	'sandbox' => true,
			'http_errors' => true,
		]);
		//print_r($service->getConfig('credentials'));
		return $service;
	}

	function getCache()
	{
		CacheManager::setDefaultConfig([
//	"path" => sys_get_temp_dir()
"path" => '.',
		]);
		$cache = new Psr16Adapter('files');
		return $cache;
	}

}
