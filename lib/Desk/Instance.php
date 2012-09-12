<?php

namespace Desk;

class Instance
{

	/**
	 * The API clients that do the actual communication.
	 *
	 * @var array
	 */
	private $clients = array();


	/**
	 * Initialise the clients, each with a default transport.
	 */
	public function __construct($subdomain, $consumerKey, $consumerSecret, $accessToken, $accessSecret)
	{
		$hostname = \Desk::getHostname($subdomain);
		$transport = new \Desk\Transport\OAuth($hostname, $consumerKey, $consumerSecret);
		$transport->setToken($accessToken, $accessSecret);

		foreach (\Desk\Client::getAllTypes() as $type)
		{
			$this->client($type, \Desk\Client::factory($type, $transport));
		}
	}

	/**
	 * Combined getter/setter for API clients.
	 *
	 * @param int $type The "type" of API client to get or set
	 * @param \Desk\Client The new object to use as a client (optional)
	 *
	 * @return \Desk\Client
	 */
	public function client($type, $client = null)
	{
		if (!\Desk\Client::isValidType($type))
			throw new \Desk\Exception\InvalidArgumentException("Invalid Desk API client type \"$type\"");

		if ($client)
		{
			if ($client instanceof \Desk\Client)
				$this->clients[$type] = $client;
			else
				throw new \Desk\Exception\InvalidArgumentException('Desk API client is not an instance of \Desk\Client');
		}

		return isset($this->clients[$type]) ? $this->clients[$type] : null;
	}

	public function cases()
	{
		return $this->client(\Desk\Client::CASES);
	}

	public function customers()
	{
		return $this->client(\Desk\Client::CUSTOMERS);
	}

	public function interactions()
	{
		return $this->client(\Desk\Client::INTERACTIONS);
	}

	public function users()
	{
		return $this->client(\Desk\Client::USERS);
	}

	public function userGroups()
	{
		return $this->client(\Desk\Client::USER_GROUPS);
	}

	public function topics()
	{
		return $this->client(\Desk\Client::TOPICS);
	}

	public function articles()
	{
		return $this->client(\Desk\Client::ARTICLES);
	}

	public function macros()
	{
		return $this->client(\Desk\Client::MACROS);
	}

}
