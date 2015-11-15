<?php

class Mock extends PHPUnit_Framework_TestCase {

  private static $namespace = "alfmarks";

	private $subjectClass;
	private $subjectParams = array();
	private $subjectOptions;

	private $baseParams = array();

	private $baseOptions = array(
		'mock' => false,
		'methods' => array()
	);

	public function __construct( $class ) {
		$this->subjectClass = self::$namespace . "\\" . $class;
	}

	public function withParams() {
		$params = func_get_args();
		// we use the pairs as `option => value` options
		$count = 1;
		$ks = array_keys($this->baseParams);
		$vs = array_values($this->baseParams);
		foreach($params as $param) {
			// Odd counts are arg keys
			if (($count % 2) == 1) { $ks[] = $param; }
			// Even counts are arg values
			if (($count % 2) == 0) { $vs[] = $param; }
			$count++;
		}
		// If two keys are the same the second prevails
		// so this accounts for override of baseParams
		$this->subjectParams = array_combine($ks, $vs);
		// Since this is a chainable method
		return $this;
	}

	public function withOptions() {
		$options = func_get_args();
		// we use the pairs as `option => value` options
		$count = 1;
		$ks = array_keys($this->baseOptions);
		$vs = array_values($this->baseOptions);
		foreach($options as $option) {
			// Odd counts are arg keys
			if (($count % 2) == 1) { $ks[] = $option; }
			// Even counts are arg values
			if (($count % 2) == 0) { $vs[] = $option; }
			$count++;
		}
		// If two keys are the same the second prevails
		// so this accounts for override of baseOptions
		$this->subjectOptions = array_combine($ks, $vs);
		// Since this is a chainable method
		return $this;
	}

	public function buildSubject() {
		// If we are mocking the class
		if ($this->subjectOptions['mock']) {
			return $this->getMock($this->subjectClass,
				$this->subjectOptions['methods'],
				$this->subjectParams
			);
		}
		// Otherwise initialize an instance w/ params
		return new $this->subjectClass( $this->subjectParams );
	}

}
