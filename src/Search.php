<?php

namespace EbayHelper;

use Arrayzy\ArrayImitator;
use DTS\eBaySDK\Finding\Services\FindingService;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;
use DTS\eBaySDK\Finding\Types\SearchItem;
use DTS\eBaySDK\Product\Types\SortOrder;
use GuzzleHttp\Exception\ServerException;
use phpFastCache\Helper\Psr16Adapter;
use Psr\SimpleCache\CacheInterface;

class Search {

	/**
	 * @var FindingService
	 */
	protected $service;

	/**
	 * @var CacheInterface
	 */
	protected $cache;

	/**
	 * @var string
	 */
	protected $cacheKey;

	function __construct(FindingService $service, CacheInterface $cache)
	{
		$this->service = $service;
		$this->cache = $cache;
		$this->cacheKey = str_replace('\\', '_', __CLASS__);
	}

	function render()
	{
		$request = $this->makeRequest();
//		if (!$this->cache->has($this->cacheKey)) {
			$items = $this->search($request);
//			$this->cache->set($this->cacheKey, $items);
//		} else {
//			$items = $this->cache->get($this->cacheKey);
//		}

		if ($items) {
			echo 'Size: ', $items->count, PHP_EOL;
			echo '(array): ', count((array)$items->item), PHP_EOL;
			echo 'Item: ', count($items->item), PHP_EOL;
			$page = [];
			foreach ($items->item as $item) {
				$page[] = $item;
			}
			echo 'Page: ', count($page), PHP_EOL;

			$b = new Bucket($page);
			echo 'Bucket: ', $b->count(), PHP_EOL;
			//$b->list();
			//print_r($item);

			$buckets = $this->sortIntoBuckets($page);
			/**
			 * @var string $name
			 * @var Bucket $list
			 */
			foreach ($buckets as $name => $list) {
				echo $name, PHP_EOL, str_repeat('=', strlen($name)), PHP_EOL;
				echo 'Length: ', sizeof($list), PHP_EOL;
				echo 'Median: ', $list->medianPrice(), PHP_EOL;

				if ($name == 'rest') {
					$list->list();
				}

				echo PHP_EOL;
			}
		}
	}

	/**
	 * @param SearchItem[] $items
	 * @return Bucket[]
	 */
	function sortIntoBuckets(array $items) {
		/**
		 * @var $buckets Bucket[]
		 */
		$buckets = [];
		$buckets['Z3Compact'] = new Bucket();
		$buckets['Z4'] = new Bucket();
		$buckets['Z3'] = new Bucket();
		$buckets['rest'] = new Bucket();
		foreach ($items as $item) {
			$tokens = str_word_count($item->title, 1, '0123456789+');
			$terms = ArrayImitator::create($tokens);
			//echo $terms->toString(), PHP_EOL;
			$compact = ['compact', 'Compact', 'COMPACT', 'D5803', 'd5803'];
			if ($terms->intersect($compact)->count()) {
				$buckets['Z3Compact']->add($item);
			} elseif ($terms->intersect(['Z3+', 'Z4', 'E6553'])->count()) {
				$buckets['Z4']->add($item);
			} elseif ($terms->intersect(['Z3', 'z3', 'd6603', 'D6603'])->count()) {
				$buckets['Z3']->add($item);
			} else {
				$buckets['rest']->add($item);
			}
		}
		return $buckets;
	}

	function search(Types\FindItemsAdvancedRequest $request) {
		/**
		 * Send the request.
		 */
		try {
			$response = $this->service->findItemsAdvanced($request);

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
				return $response->searchResult;
			}

		} catch (ServerException $e) {
			print_r($e->getRequest()->getHeaders());
			print_r($e->getRequest()->getBody()->getContents());
			echo $e->getResponse()->getBody()->getContents();
		}
	}

	/**
	 * @return Types\FindItemsAdvancedRequest
	 */
	private function makeRequest(): Types\FindItemsAdvancedRequest
	{
		/**
		 * Create the request object.
		 */
		$request = new Types\FindItemsAdvancedRequest();

		/**
		 * Assign the keywords.
		 */
		$request->keywords = 'Z3';

		$request->categoryId = ['9355'];

		$itemFilter = new Types\ItemFilter();
		$itemFilter->name = 'ListingType';
		$itemFilter->value[] = 'Auction';
		$itemFilter->value[] = 'AuctionWithBIN';
		$request->itemFilter[] = $itemFilter;

		/**
		 * Add additional filters to only include items that fall in the price range of $1 to $10.
		 *
		 * Notice that we can take advantage of the fact that the SDK allows object properties to be assigned via the class constructor.
		 */
		$request->itemFilter[] = new Types\ItemFilter([
			'name'  => 'MinPrice',
			'value' => ['80.00'],
		]);

		$request->itemFilter[] = new Types\ItemFilter([
			'name'  => 'MaxPrice',
			'value' => ['130.00'],
		]);

		$request->sortOrder = 'EndTimeSoonest';

		/**
		 * Limit the results to 10 items per page and start at page 1.
		 */
		$request->paginationInput = new Types\PaginationInput();
		$request->paginationInput->entriesPerPage = 50;
		$request->paginationInput->pageNumber = 1;

		return $request;
	}

}
