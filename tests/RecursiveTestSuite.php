<?php

abstract class RecursiveTestSuite extends TestSuite
{

	public function collectRecursive($path, $pattern = '/\.php$/i')
	{
		// add all Desk tests
		$collector = new RecursiveSimplePatternCollector($pattern);
		$this->collect($path, $collector);
	}

}

class RecursiveSimplePatternCollector extends SimplePatternCollector
{

	/**
	 * Recurses into directories to load tests within.
	 */
	protected function handle(&$test, $filename)
	{
		if (is_dir($filename))
			$test->collect($filename, clone $this);
		else
			parent::handle($test, $filename);
	}

}
