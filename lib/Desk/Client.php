<?php

namespace Desk;

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

	public static function getAllTypes()
	{
		return array_keys(self::$classMap);
	}

	/**
	 * Determines whether a client type is a valid type.
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
	 * @return \Desk\Client
	 */
	public static function factory($type)
	{
		if (!self::isValidType($type))
			throw new \Desk\Exception\InvalidArgumentException("Unknown Desk API Client type \"$type\"");

		$class = self::$classMap[$type];
		return new $class();
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
			if ($transport instanceof \Desk\Transport)
				$this->transport = $transport;
			else
				throw new \Desk\Exception\InvalidArgumentException('Desk API transport is not an instance of \Desk\Transport');
		}

		return $this->transport;
	}

}
