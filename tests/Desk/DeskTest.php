<?php

class DeskTest extends \UnitTestCase
{

	public function testConstruct()
	{
		$instance = $this->getInstance();
		$this->assertIsA($instance, 'Desk');
	}

	public function testGetHostname()
	{
		$hostname = Desk::getHostname('foobar');
		$this->assertIdentical('https://foobar.desk.com', $hostname);
	}

	private function getInstance()
	{
		return new Desk('v', 'w', 'x', 'y', 'z');
	}

}
