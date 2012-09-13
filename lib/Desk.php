<?php

use Desk\Client;
use Desk\Exception;
use Desk\Transport;

class Desk
{

	/**
	 * The API clients that do the actual communication.
	 *
	 * @var array
	 */
	private $clients = array();

	/**
	 * The default transport for newly initialised clients.
	 *
	 * This will be populated with the credentials passed to the
	 * constructor.
	 *
	 * @var \Desk\Transport
	 */
	private $tranport = array();


	/**
	 * Creates a new Desk.com instance, with a default transport.
	 *
	 * @param string $subdomain The subdomain of desk.com to connect to
	 * @param string $consumerKey The Desk.com API consumer key
	 * @param string $consumerSecret The Desk.com API consumer secret
	 * @param string $accessToken The Desk.com access token
	 * @param string $accessSecret The Desk.com access secret
	 */
	public function __construct($subdomain, $consumerKey, $consumerSecret, $accessToken, $accessSecret)
	{
		$hostname = self::getHostname($subdomain);
		$transport = new Transport\OAuth($hostname, $consumerKey, $consumerSecret);
		$transport->setToken($accessToken, $accessSecret);
	}

	/**
	 * Converts a subdomain to a full hostname.
	 *
	 * @param string $subdomain The Desk.com subdomain
	 *
	 * @return string
	 */
	public static function getHostname($subdomain)
	{
		return sprintf("https://%s.desk.com", $subdomain);
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
		if (!Client::isValidType($type))
			throw new Exception\InvalidArgumentException("Invalid Desk API client type \"$type\"");

		if ($client)
		{
			if ($client instanceof Client)
				$this->clients[$type] = $client;
			else
				throw new Exception\InvalidArgumentException('Desk API client is not an instance of \Desk\Client');
		}

		// initialise client if it doesn't exist
		if (empty($this->clients[$type]))
			$this->clients[$type] = Client::factory($type, $transport);

		return $this->clients[$type];
	}

	public function cases()
	{
		return $this->client(Client::CASES);
	}

	public function customers()
	{
		return $this->client(Client::CUSTOMERS);
	}

	public function interactions()
	{
		return $this->client(Client::INTERACTIONS);
	}

	public function users()
	{
		return $this->client(Client::USERS);
	}

	public function userGroups()
	{
		return $this->client(Client::USER_GROUPS);
	}

	public function topics()
	{
		return $this->client(Client::TOPICS);
	}

	public function articles()
	{
		return $this->client(Client::ARTICLES);
	}

	public function macros()
	{
		return $this->client(Client::MACROS);
	}

}
