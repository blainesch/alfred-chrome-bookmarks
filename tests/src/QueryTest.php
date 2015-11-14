<?php

use alfmarks\Query;

class QueryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$calledClass = str_replace(
			'Test', '', get_called_class()
		);
		$this->classTester = new Mock($calledClass);
	}

	public function testSetsModel() {
		// A `Query` with a model assigned to `foo` call model should return `foo`.
		$this->assertEquals(
			'foo',
			$this
				->classTester
				->withParams('model', 'foo')
				->buildSubject()
				->model
		);
	}

	public function testSetsTerm() {
		// A `Query` with a term assigned to `foo` call term should return `foo`.
		$this->assertEquals(
			'foo',
			$this
				->classTester
				->withParams('term', 'foo')
				->buildSubject()
				->term
		);
	}

	public function testTerm() {
		// A `Query` with a term assigned to `foo` call regex should return `pattern`.
		$this->assertEquals(
			'/.*?((f).*?(o).*?(o)).*?|.*?((f).*?(o)).*?|.*?((o).*?(o)).*?/i',
			$this
				->classTester
				->withParams('term', 'foo')
				->buildSubject()
				->regex()
		);
	}

	public function testTermWithCharacters() {
		// A `Query` with a term assigned to `][^` call regex should return...
		// a corrected pattern with escape backslash `\` before caret `^` symbols.
		$this->assertEquals(
			'/.*?((\]).*?(\[).*?(\^)).*?|.*?((\]).*?(\[)).*?|.*?((\[).*?(\^)).*?/i',
			$this
				->classTester
				->withParams('term', '][^')
				->buildSubject()
				->regex()
		);
	}

}

