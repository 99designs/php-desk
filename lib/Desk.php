<?php

class Desk
{

	/**
	 * Singleton instance.
	 *
	 * @var \Desk\Instance
	 */
	private static $instance;


	/**
	 * Initialises the singleton instance.
	 *
	 * @param string $subdomain The subdomain of desk.com to connect to
	 * @param string $consumerKey The Desk.com API consumer key
	 * @param string $consumerSecret The Desk.com API consumer secret
	 * @param string $accessToken The Desk.com access token
	 * @param string $accessSecret The Desk.com access secret
	 *
	 * @return void
	 */
	public static function initialize($subdomain, $consumerKey, $consumerSecret, $accessToken, $accessSecret)
	{
		self::$instance = new \Desk\Instance($subdomain, $consumerKey, $consumerSecret, $accessToken, $accessSecret);
	}

	/**
	 * Resets (un-initialises) the singleton instance.
	 */
	public static function reset()
	{
		self::$instance = null;
	}

	/**
	 * Combined getter/setter for the singleton instance.
	 *
	 * @return \Desk\Instance
	 */
	public static function instance($instance = null)
	{
		if ($instance)
		{
			if ($instance instanceof \Desk\Instance)
				self::$instance = $instance;
			else
				throw new InvalidArgumentException('Invalid Desk API instance, not instance of \Desk\Instance');
		}

		if (!self::$instance)
			throw new BadMethodCallException('Desk.com API not initialised');

		return self::$instance;
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
	 * Applies any static method calls to the singleton instance.
	 */
	public static function __callStatic($function, $arguments)
	{
		$callback = array(self::instance(), $function);
		if (!is_callable($callback))
			throw new BadMethodCallException("Unknown Desk API \"$function\"");

		return call_user_func_array($callback, $arguments);
	}

}
