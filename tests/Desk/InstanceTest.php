<?php

namespace Desk;

class InstanceTest extends \UnitTestCase
{

	public function testConstruct()
	{
		$instance = $this->getInstance();
		$this->assertIsA($instance, '\Desk\Instance');
	}

	private function getInstance()
	{
		return new \Desk\Instance('v', 'w', 'x', 'y', 'z');
	}

}
