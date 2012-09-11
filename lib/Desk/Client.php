<?php

namespace Desk;

/**
 * Abstract base class for Desk Clients.
 */
abstract class Client
{

	/**
	 * Maps client types to classes.
	 *
	 * @var array
	 */
	private static $classMap = array(
		// TODO
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
			throw new InvalidArgumentException("Unknown Desk API Client type \"$type\"");

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
				throw new \InvalidArgumentException('Desk API transport is not an instance of \Desk\Transport');
		}

		return $this->transport;
	}

}
