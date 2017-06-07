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
//$s->keywords = '(Z3,Z3+,Z4)';
$s->keywords = '(Z3+,Z4)';
$s->categoryId = ['9355'];	// Mobile phone without contract
$s->minPrice = '10.00';
$s->bucketWords = [
	'compact' => ['compact', 'Compact', 'COMPACT', 'D5803', 'd5803'],
	'Z4' => ['Z3+', 'Z4', 'E6553'],
	'Z3' => ['Z3', 'z3', 'd6603', 'D6603'],
	'rest' => ['thisisspecialstringwhichwillneverhappen'],
];

/** @see https://developer.ebay.com/devzone/finding/CallRef/types/ItemFilterType.html */
$s->condition = [
	'1000', //New
	'1500', //New other (see details)
//	'1750', //New with defects
	'2000', //Manufacturer refurbished
//	'2500', //Seller refurbished
	'3000', //Used
	'4000', //Very Good
	'5000', //Good
//	'6000', //Acceptable
//	'7000', //For parts or not working
];
$s->render();
