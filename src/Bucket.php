<?php

namespace EbayHelper;

use Arrayzy\ArrayImitator;
use DTS\eBaySDK\Finding\Types\SearchItem;

/**
 * Class Bucket
 * @package EbayHelper
 * @method SearchItem current()
 */
class Bucket extends ArrayImitator
{

	protected $elements;

	public function list()
	{
		foreach ($this->elements as $key => $item) {
//			print_r($item);
			$remaining = $item->sellingStatus->timeLeft;
			try {
				$remaining = new \DateInterval($remaining);
				$remaining = $remaining->format('%R%Dd %Hh');
			} catch (\Exception $e) {
				// remaining remain a string
			}
			printf(
				"(%s) %s: %.2f\t%d bids\t%s\t%s\n",
				$item->itemId,
				$item->sellingStatus->currentPrice->currencyId,
				$item->sellingStatus->currentPrice->value,
				$item->sellingStatus->bidCount,
				$remaining,
				$item->title
			);
		}
	}

	public function getList()
	{
		$table = [];
		foreach ($this->elements as $key => $item) {
			$remaining = $item->sellingStatus->timeLeft;
			try {
				$remaining = new \DateInterval($remaining);
				$remaining = $remaining->format('%R%Dd %Hh');
			} catch (\Exception $e) {
				// remaining remain a string
			}
			$row = [
				'itemId' => $item->itemId,
				'currencyId' => $item->sellingStatus->currentPrice->currencyId,
				'median' => '',
				'price' => $item->sellingStatus->currentPrice->value,
				'diff' => '',
				'diff%' => '',
				'bids' => $item->sellingStatus->bidCount,
				'remaining' => $remaining,
				'title' => $item->title,
			];
			$table[] = $row;
		}
		return $table;
	}

	function median()
	{
		if ($this->elements) {
			$arr = $this->elements;    // because of sort
			$count = count($arr);
			sort($arr);
			$mid = (int)floor(($count - 1) / 2);
			return ($arr[$mid] + $arr[$mid + 1 - $count % 2]) / 2;
		}
		return 0;
	}

	public function medianPrice()
	{
		$prices = $this->map(function ($el) {
			return $el->sellingStatus->currentPrice->value;
		});
		$priceBucket = new Bucket($prices->elements);
		return $priceBucket->median();
	}

	public function findBucket(array $bucketWords, $restName = 'rest')
	{
		foreach ($bucketWords as $bucket => $wordList) {
			if ($this->intersect($wordList)->count()) {
				return $bucket;
			}
		}
		return $restName;
	}

}
