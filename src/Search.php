<?php

namespace EbayHelper;

use Arrayzy\ArrayImitator;
use DTS\eBaySDK\Finding\Services\FindingService;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;
use DTS\eBaySDK\Finding\Types\SearchItem;
use DTS\eBaySDK\Product\Types\SortOrder;
use GuzzleHttp\Exception\ServerException;
use Miloske85\php_cli_table\Table;
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

	public $keywords = '(Z3,Z3+,Z4)';

	public $categoryId = ['9355'];

	/**
	 * @var string
	 */
	public $minPrice = '80.00';

	/**
	 * @var string|null
	 */
	public $maxPrice = NULL;

	public $bucketWords = [
		'compact' => ['compact', 'Compact', 'COMPACT', 'D5803', 'd5803'],
		'Z4' => ['Z3+', 'Z4', 'E6553'],
		'Z3' => ['Z3', 'z3', 'd6603', 'D6603'],
		'rest' => ['thisisspecialstringwhichwillneverhappen'],
	];

	public $condition = [];

	function __construct(FindingService $service, CacheInterface $cache)
	{
		$this->service = $service;
		$this->cache = $cache;
		$this->cacheKey = str_replace('\\', '_', __CLASS__);
	}

	function render()
	{
		echo 'Sold:', PHP_EOL;
		$request = $this->makeRequestSold();
		$sold = $this->search($request);
		$soldBuckets = $this->sortIntoBuckets(iterator_to_array($sold->item));
		$this->listBucketsCompact($soldBuckets);

		echo 'Ending:', PHP_EOL;
		$request = $this->makeRequest();
		$items = $this->search($request);
		$buckets = $this->sortIntoBuckets(iterator_to_array($items->item));
		$this->listBucketsCompact($buckets, $soldBuckets);

//		echo 'Sold:', PHP_EOL;
//		$this->dumpBuckets($soldBuckets);
		echo 'Ending:', PHP_EOL;
		$this->dumpBucketsWithDiff($buckets, $soldBuckets);
	}

	/**
	 * @param Bucket[] $buckets
	 */
	function dumpBuckets(array $buckets) {
		foreach ($buckets as $name => $list) {
			echo $name, ' (', sizeof($list), ')', PHP_EOL,
			str_repeat('=', strlen($name)), PHP_EOL;
			$list->list();
		}
	}

	/**
	 * @param Bucket[] $buckets
	 */
	function dumpBucketsWithDiff(array $buckets, array $soldBuckets) {
		/**
		 * @var string $name
		 * @var Bucket $list
		 */
		foreach ($buckets as $name => $list) {
			echo $name, ' (', sizeof($list), ')', PHP_EOL,
			str_repeat('=', strlen($name)), PHP_EOL;
			$table = $list->getList();
			/** @var Bucket $soldInfo */
			$soldInfo = $soldBuckets[$name];
			$medianPrice = $soldInfo->medianPrice();
			foreach ($table as &$row) {
				$row['median'] = $medianPrice;
				$diff = $row['price'] - $medianPrice;
				$row['diff'] = $diff;
				if ($medianPrice) {
					$row['diff%'] = number_format($row['price'] * 100 / $medianPrice, 2).'%';
				}
			}

			if ($table) {
				$table = new Table($table);
				echo $table->getTable();
			}
		}
	}

	function listBuckets(array $buckets) {
		/**
		 * @var string $name
		 * @var Bucket $list
		 */
		foreach ($buckets as $name => $list) {
			echo $name, PHP_EOL, str_repeat('=', strlen($name)), PHP_EOL;
			echo 'Length: ', sizeof($list), PHP_EOL;
			echo 'Median: ', $list->medianPrice(), PHP_EOL;

			echo PHP_EOL;
		}
	}

	function listBucketsCompact(array $buckets, array $soldBuckets = NULL) {
		$table = [];
		/**
		 * @var string $name
		 * @var Bucket $list
		 */
		foreach ($buckets as $name => $list) {
//			echo $name, TAB, sizeof($list), TAB, $list->medianPrice(), PHP_EOL;
			$row = [
				'name'  => $name,
				'count' => sizeof($list),
				'price' => $list->medianPrice(),
			];
			if ($soldBuckets) {
				$soldInfo = $soldBuckets[$name];
				$diff = $list->medianPrice() - $soldInfo->medianPrice();
				$row['diff'] = ($diff > 0 ? '+' : '') . $diff;
			}
			$table[] = $row;
		}
		$table = new Table($table);
		echo $table->getTable();
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
		foreach ($this->bucketWords as $name => $words) {
			$buckets[$name] = new Bucket();
		}
		foreach ($items as $item) {
			$tokens = str_word_count($item->title, 1, '0123456789+');
			/** @var Bucket $terms */
			$terms = Bucket::create($tokens);
			//echo $terms->toString(), PHP_EOL;
			$bucketName = $terms->findBucket($this->bucketWords);
			$buckets[$bucketName]->add($item);
		}
		return $buckets;
	}

	function search($request) {
		/**
		 * Send the request.
		 */
		try {
			if ($request instanceof Types\FindCompletedItemsRequest) {
				$response = $this->service->findCompletedItems($request);
			} else {
				$response = $this->service->findItemsAdvanced($request);
			}

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
		$request->keywords = $this->keywords;

		$request->categoryId = $this->categoryId;

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
			'value' => [$this->minPrice],
		]);

		if ($this->maxPrice) {
			$request->itemFilter[] = new Types\ItemFilter([
				'name'  => 'MaxPrice',
				'value' => [$this->maxPrice],
			]);
		}

		if ($this->condition) {
			$request->itemFilter[] = new Types\ItemFilter([
				'name'  => 'Condition',
				'value' => $this->condition,
			]);
		}

		$request->sortOrder = 'EndTimeSoonest';

		/**
		 * Limit the results to 10 items per page and start at page 1.
		 */
		$request->paginationInput = new Types\PaginationInput();
		$request->paginationInput->entriesPerPage = 50;
		$request->paginationInput->pageNumber = 1;

		return $request;
	}

	/**
	 * @return Types\FindCompletedItemsRequest
	 */
	private function makeRequestSold(): Types\FindCompletedItemsRequest
	{
		/**
		 * Create the request object.
		 */
		$request = new Types\FindCompletedItemsRequest();

		/**
		 * Assign the keywords.
		 */
		$request->keywords = $this->keywords;

		$request->categoryId = $this->categoryId;

		$itemFilter = new Types\ItemFilter();
		$itemFilter->name = 'ListingType';
		$itemFilter->value[] = 'Auction';
		$itemFilter->value[] = 'AuctionWithBIN';
		$request->itemFilter[] = $itemFilter;

		$request->itemFilter[] = new Types\ItemFilter([
			'name'  => 'SoldItemsOnly',
			'value' => ['true'],
		]);

		$request->sortOrder = 'EndTimeSoonest';

		/**
		 * Limit the results to 10 items per page and start at page 1.
		 */
		$request->paginationInput = new Types\PaginationInput();
		$request->paginationInput->entriesPerPage = 100;
		$request->paginationInput->pageNumber = 1;

		return $request;
	}

}
