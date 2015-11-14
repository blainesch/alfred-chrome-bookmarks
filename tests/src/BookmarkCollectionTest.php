<?php

use alfmarks\BookmarkCollection;

class BookmarkCollectionTest extends Unit {

	public function setUp() {
		$this->classTester = new Unit(self::getCalledClass());
	}

	public function testReturnsEmptyItemArray() {
		$this->assertEquals(
			"<?xml version=\"1.0\"?>\n<items/>\n",
			$this
				->classTester
				->buildSubject()
				->to_xml()
		);
	}

}

