<?php

use Desk\Client as Client;

class DeskTest extends \UnitTestCase
{

	public function testConstruct()
	{
		$instance = $this->getInstance();
		$this->assertIsA($instance, 'Desk');
	}

	public function testConstructedTransport()
	{
		$instance = $this->getInstance();
		$transport = $instance->transport();

		$this->assertIsA($transport, '\Desk\Transport');
	}

	public function testGetHostname()
	{
		$hostname = Desk::getHostname('foobar');
		$this->assertIdentical('https://foobar.desk.com', $hostname);
	}

	public function testClient()
	{
		$instance = $this->getInstance();

		$client = \Mockery::mock('\Desk\Client');
		$instance->client(Client::ARTICLES, $client);
	}

	public function testInvalidClient()
	{
		$instance = $this->getInstance();
		$this->expectException('\Desk\Exception\InvalidArgumentException');
		$instance->client(Client::ARTICLES, new stdClass());
	}

	private function getInstance()
	{
		return new Desk('v', 'w', 'x', 'y', 'z');
	}

}
