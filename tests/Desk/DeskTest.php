<?php

class DeskTest extends \UnitTestCase
{

	public function setUp()
	{
		\Desk::reset();
	}

	public function tearDown()
	{
		\Desk::reset();
	}

	public function testInitialize()
	{
		\Desk::initialize('foo', 'bar', 'baz', 'quux', 'grault');
		$this->assertIsA(\Desk::instance(), '\Desk\Instance');
	}

	public function testUninitializedException()
	{
		$this->expectException('\Desk\Exception\BadMethodCallException');
		\Desk::instance();
	}

	public function testGetHostname()
	{
		$hostname = \Desk::getHostname('foobar');
		$this->assertIdentical('https://foobar.desk.com', $hostname);
	}

	public function testStaticMethodCallDelegation()
	{
		$instance = \Mockery::mock('\Desk\Instance')
			->shouldReceive('quuux')
			->with('bazola', 'ztesch')
			->andReturn('bletch')
			->getMock();

		\Desk::instance($instance);

		$result = \Desk::quuux('bazola', 'ztesch');

		$this->assertIdentical('bletch', $result);
	}

	public function testInvalidStaticMethodCallException()
	{
		$this->expectException('\Desk\Exception\BadMethodCallException');
		$result = \Desk::nonExistantApiClient();
	}

}
