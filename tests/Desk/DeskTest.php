<?php

use Desk\Client as Client;

class DeskTest extends \PHPUnit_Framework_TestCase
{

	public function testConstruct()
	{
		$instance = $this->getInstance();
		$this->assertInstanceOf('Desk', $instance);
	}

	public function testConstructedTransport()
	{
		$instance = $this->getInstance();
		$transport = $instance->transport();

		$this->assertInstanceOf('\Desk\Transport', $transport);
	}

	public function testGetHostname()
	{
		$hostname = Desk::getHostname('foobar');
		$this->assertSame('https://foobar.desk.com', $hostname);
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
		$this->setExpectedException('\Desk\Exception\InvalidArgumentException');
		$instance->client(Client::ARTICLES, new stdClass());
	}

	private function getInstance()
	{
		return new Desk('v', 'w', 'x', 'y', 'z');
	}

}
