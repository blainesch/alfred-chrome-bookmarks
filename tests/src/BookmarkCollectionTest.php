<?php

use alfmarks\BookmarkCollection;
use alfmarks\BookmarkModel;

class BookmarkCollectionTest extends PHPUnit_Framework_TestCase {

	public $subject;

	public function setUp() {
		$this->subject = new BookmarkCollection(array(
			new BookmarkModel([
				'name' => 'blaine',
				'url'  => 'http://gogal.com',
				'id'   => '1'
			], 1),
			new BookmarkModel([
				'name' => 'adam',
				'url'  => 'http://gogal.com',
				'id'   => '1'
			], 2)
		));
	}

	public function testSort() {
		$this->subject->sort();

		$this->assertEquals('adam', $this->bookmarks(0)->name);
		$this->assertEquals('blaine', $this->bookmarks(1)->name);
	}

	public function testToXml() {
		$this->assertXmlStringEqualsXmlString(
			'<?xml version="1.0"?>'.
			'<items>'.
				'<item arg="http://gogal.com" uid="11">'.
					'<title>blaine</title>'.
					'<subtitle>http://gogal.com</subtitle>'.
				'</item>'.
				'<item arg="http://gogal.com" uid="12">'.
					'<title>adam</title>'.
					'<subtitle>http://gogal.com</subtitle>'.
				'</item>'.
			'</items>',
			$this->subject->to_xml()
		);
	}

	protected function bookmarks($id) {
		return $this->subject->bookmarks[$id];
	}

}
