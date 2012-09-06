<?php

require_once('RecursiveTestSuite.php');

class DeskTester extends RecursiveTestSuite
{

	public function __construct()
	{
		$this->collectRecursive(dirname(__FILE__) . '/Desk');
	}

}
