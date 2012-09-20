<?php

namespace Desk;

use Desk\Exception;
use Desk\Transport;

/**
 * Abstract base class for Desk Clients.
 */
abstract class Client
{

	const CASES        = 1;
	const CUSTOMERS    = 2;
	const INTERACTIONS = 3;
	const USERS        = 4;
	const USER_GROUPS  = 5;
	const TOPICS       = 6;
	const ARTICLES     = 7;
	const MACROS       = 8;

	/**
	 * Maps client types to classes.
	 *
	 * @var array
	 */
	private static $classMap = array(
		self::CASES        => '\Desk\Client\Cases',
		self::CUSTOMERS    => '\Desk\Client\Customers',
		self::INTERACTIONS => '\Desk\Client\Interactions',
		self::USERS        => '\Desk\Client\Users',
		self::USER_GROUPS  => '\Desk\Client\UserGroups',
		self::TOPICS       => '\Desk\Client\Topics',
		self::ARTICLES     => '\Desk\Client\Articles',
		self::MACROS       => '\Desk\Client\Macros',
	);

	/**
	 * The transport used to communicate with the Desk API.
	 *
	 * @var \Desk\Transport
	 */
	private $transport;

	/**
	 * The Desk API version to be used.
	 *
	 * Will be used for all API calls unless overridden in child classes.
	 *
	 * @var string
	 */
	private $version = 'v1';


	public static function getAllTypes()
	{
		return array_keys(self::$classMap);
	}

	/**
	 * Determines whether a client type is a valid type.
	 *
	 * @param int $type The type of API client
	 *
	 * @return boolean
	 */
	public static function isValidType($type)
	{
		return array_key_exists($type, self::$classMap);
	}

	/**
	 * Factory method to create a client of a particular type.
	 *
	 * @param int $type The type of API client
	 * @param \Desk\Transport $transport The transport to use (optional)
	 *
	 * @return \Desk\Client
	 */
	public static function factory($type, $transport = null)
	{
		if (!self::isValidType($type))
			throw new Exception\InvalidArgumentException("Unknown Desk API Client type \"$type\"");

		$class = self::$classMap[$type];
		$client = new $class();

		if ($transport)
			$client->transport($transport);

		return $client;
	}


	/**
	 * Optionally sets the transport at construct time.
	 *
	 * @param \Desk\Transport $transport
	 */
	public function __construct($transport = null)
	{
		$this->transport($transport);
	}

	/**
	 * Combined getter/setter for the transport.
	 *
	 * @param \Desk\Transport $transport The new object to use as a
	 * transport (optional)
	 *
	 * @return \Desk\Transport
	 */
	public function transport($transport = null)
	{
		if ($transport)
		{
			if ($transport instanceof Transport)
				$this->transport = $transport;
			else
				throw new Exception\InvalidArgumentException('Desk API transport is not an instance of \Desk\Transport');
		}

		return $this->transport;
	}

	/**
	 * Adds the version number and prefix to a URL path.
	 *
	 * @param string $path The original path
	 *
	 * @return string
	 */
	private function getPath($path)
	{
		$path = ltrim($path, '/');
		return "/api/{$this->version}/$path";
	}

	protected function get($path, $query = array())
	{
		return $this->transport()->get($this->getPath($path), $query);
	}

	protected function post($path, $data = array())
	{
		return $this->transport()->post($this->getPath($path), $data);
	}

	protected function put($path, $data = array())
	{
		return $this->transport()->put($this->getPath($path), $data);
	}

	protected function delete($path, $query = array())
	{
		return $this->transport()->delete($this->getPath($path), $query);
	}

}
