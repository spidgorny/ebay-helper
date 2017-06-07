<?php

/**
 * @var \DTS\eBaySDK\Finding\Services\FindingService
 */

use EbayHelper\Config;

require_once __DIR__.'/bootstrap.php';

$config = new Config();
$service = $config->getEbayService();
$cache = $config->getCache();
$s = new \EbayHelper\Search($service, $cache);
$s->render();
