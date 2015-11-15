<?php

use org\bovigo\vfs\vfsStream;
use alfmarks\Source;
use alfmarks\Query;
use alfmarks\BookmarkModel;
use alfmarks\Helper;

class SourceTest extends PHPUnit_Framework_TestCase {

	public function createProfile($arr, $name = 'profile') {
		$file = vfsStream::url('home/' . $name . '.json');
		file_put_contents($file, json_encode($arr));
		return $file;
	}

	public function setUp() {
		$this->root = vfsStream::setup('home');
		$calledClass = str_replace(
			'Test', '', get_called_class()
		);
		$this->classTester = new Mock($calledClass);
	}

	public function tearDown() {
		Helper::filter('glob', function($pattern) {
			return array($pattern);
		});
	}

	public function testSkippedRead() {
		$_SERVER['PROFILE'] = $this->createProfile(array(
			array(
				'id' => 1,
				'name' => 'Google',
				'url' => 'http://google.com',
			),
			array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			),
			array(
				'id' => 3,
				'name' => 'Yahoo!',
				'url' => 'http://yahoo.com',
			),
		));
		$subject = $this->classTester
			->withOptions(
				'mock',    true,
				'methods', array('normalizeFile')
			)
			->buildSubject();
		$subject->expects($this->any())
			->method('normalizeFile')
			->will($this->returnValue($_SERVER['PROFILE']));
		$nodes = $subject->read(new Query(array(
			'model' => 'alfmarks\BookmarkModel',
			'term' => 'Gil',
		)));
		$expected = array(
			new BookmarkModel(array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			)),
		);
		$this->assertEquals($expected[0]->data['name'], $nodes[0]->data['name']);
	}

	public function testRead() {
		$_SERVER['PROFILE'] = $this->createProfile(array(
			array(
				'id' => 1,
				'name' => 'Google',
				'url' => 'http://google.com',
			),
			array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			),
			array(
				'id' => 3,
				'name' => 'Yahoo!',
				'url' => 'http://yahoo.com',
			),
		));
		$subject = $this->classTester
			->withOptions(
				'mock',    true,
				'methods', array('normalizeFile')
			)
			->buildSubject();
		$subject->expects($this->any())
			->method('normalizeFile')
			->will($this->returnValue($_SERVER['PROFILE']));
		$nodes = $subject->read(new Query(array(
			'model' => 'alfmarks\BookmarkModel',
			'term' => 'Goo',
		)));
		$expected = array(
			new BookmarkModel(array(
				'id' => 1,
				'name' => 'Google',
				'url' => 'http://google.com',
			)),
			new BookmarkModel(array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			)),
		);
		$this->assertEquals($expected[0]->data['name'], $nodes[0]->data['name']);
		$this->assertEquals($expected[1]->data['name'], $nodes[1]->data['name']);
	}

	public function testMultipleProfileRead() {
		$files = array();
		$files[] = $this->createProfile(array(
			array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			),
		), 'profile1');
		$_SERVER['PROFILE'] = $files[] = $this->createProfile(array(
			array(
				'id' => 3,
				'name' => 'Ymail',
				'url' => 'http://mail.yahoo.com',
			),
		), 'profile2');
		Helper::filter('glob', function($pattern) use ($files) {
				return $files;
		});
		$subject = $this->classTester
			->withOptions(
				'mock',    true,
				'methods', array('normalizeFile')
			)
			->buildSubject();
		$subject->expects($this->any())
			->method('normalizeFile')
			->will($this->returnValue($_SERVER['PROFILE']));
		$nodes = $subject->read(new Query(array(
			'model' => 'alfmarks\BookmarkModel',
			'term' => 'mail',
		)));
		$expected = array(
			new BookmarkModel(array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			)),
			new BookmarkModel(array(
				'id' => 3,
				'name' => 'Ymail',
				'url' => 'http://mail.yahoo.com',
			)),
		);
		$this->assertEquals($expected[0]->data['name'], $nodes[0]->data['name']);
		$this->assertEquals($expected[1]->data['name'], $nodes[1]->data['name']);
	}

	public function testNormalizeGivesBackNodes() {
		$subject = $this->classTester
			->buildSubject();
		$result = $subject->normalizeData(range(1,2), function() {
			return 'yes';
		});
		$expected = array('yes');
		$this->assertEquals($expected, $result);
	}

}

