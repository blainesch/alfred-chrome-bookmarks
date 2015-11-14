<?php

use alfmarks\BookmarkCollection;

class BookmarkCollectionTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$calledClass = str_replace(
			'Test', '', get_called_class()
		);
		$this->classTester = new Mock($calledClass);
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

