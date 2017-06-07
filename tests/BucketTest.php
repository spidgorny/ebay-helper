<?php

namespace EbayHelper\Tests;

use Arrayzy\ArrayImitator;

class BucketTest extends \PHPUnit_Framework_TestCase {

	function test_intersect() {
		$title = 'Sony  Xperia Z3+, 32GB, Schwarz, Ohne Simlock, mit Restgarantie,';
		$set = str_word_count($title, 1, '0123456789+');
		print_r($set);
		$aSet = ArrayImitator::create($set);

		$has = $aSet->intersect(['32GB']);
		$this->assertCount(1, $has);

		$has = $aSet->intersect(['someshit']);
		$this->assertCount(0, $has);
		$this->assertFalse($has->count() ? true : false);

		$has = $aSet->intersect(['ohne']);
		$this->assertCount(0, $has);
		$this->assertFalse($has->count() ? true : false);

		$has = $aSet->intersect(['Sony', 'mit']);
		$this->assertCount(2, $has);
	}

}
