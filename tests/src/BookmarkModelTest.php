<?php

use alfmarks\BookmarkModel;

class BookmarkModelTest extends Unit {

	public function setUp() {
		$this->classTester = new Unit(self::getCalledClass());
	}

	public function testToXml() {
		$this->assertEquals(
			'<item arg="http://google.com" uid="100">'.
			'<title>Google</title>'.
			'<subtitle>http://google.com</subtitle></item>',
			$this
				->classTester
				->withParams(
					'url',  'http://google.com',
					'id',   '10',
					'name', 'Google')
				->buildSubject()
				->to_xml()
				->asXML()
		);
	}

	public function testToXmlWithAmp() {
		$this->assertEquals(
			'<item arg="http://google.com?foo&amp;bar" uid="100">'.
			'<title>Google</title>'.
			'<subtitle>http://google.com?foo&amp;bar</subtitle></item>',
			$this
				->classTester
				->withParams(
					'url',  'http://google.com?foo&bar',
					'id',   '10',
					'name', 'Google')
				->buildSubject()
				->to_xml()
				->asXML()
		);
	}

}

