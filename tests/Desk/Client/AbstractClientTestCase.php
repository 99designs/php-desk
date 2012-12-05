<?php

namespace Desk\Client;

use Desk\Client;

abstract class AbstractClientTestCase extends \PHPUnit_Framework_TestCase
{

	/**
	 * Prepares the relevant type of API client with a mock transport.
	 *
	 * @return \Desk\Client
	 */
	final protected function client()
	{
		$client = Client::factory($this->getType());
		$client->transport(\Mockery::mock('\Desk\Transport'));
		return $client;
	}

	/**
	 * Gets the type of client being tested in this test case.
	 *
	 * Should be one of the class constants on the \Desk\Client class.
	 *
	 * @return int
	 */
	abstract protected function getType();

}
