<?php

namespace Desk\Transport;

use Desk\Transport as Transport;

class OAuth implements Transport
{

	/**
	 * The OAuth object being wrapped.
	 *
	 * @var OAuth
	 */
	private $adapter;

	/**
	 * The host being communicated with via this OAuth transport.
	 *
	 * @var string
	 */
	private $host;


	/**
	 * Creates a new OAuth transport.
	 *
	 * @param string $host The host to communicate with.
	 * @param string $consumerKey The consumer key for the OAuth user.
	 * @param string $consumerSecret The consumer secret for the OAuth user.
	 */
	public function __construct($host, $consumerKey, $consumerSecret)
	{
		$this->host = rtrim($host, '/');
		$this->adapter = new \OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
	}

	/**
	 * Combined getter/setter for the adapter.
	 *
	 * @param OAuth $adapter The new adapter (optional)
	 *
	 * @return OAuth
	 */
	public function adapter($adapter = null)
	{
		if ($adapter)
			$this->adapter = $adapter;

		return $this->adapter;
	}

	/**
	 * Sets the token and secret for subsequent requests.
	 *
	 * @param string $token The OAuth token.
	 * @param string $tokenSecret The OAuth token secret.
	 *
	 * @return void
	 */
	public function setToken($token, $tokenSecret)
	{
		$this->adapter()->setToken($token, $tokenSecret);
	}

	/**
	 * Submits a GET request through the transport.
	 *
	 * @see Desk\Transport::get()
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return Desk\Transport\Response
	 */
	public function get($path, $query = array())
	{
		return $this->request($path, $query, array(), 'get');
	}

	/**
	 * Submits a POST request through the transport.
	 *
	 * @see Desk\Transport::post()
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $data Any POST data to submit with the request.
	 *
	 * @return Desk\Transport\Response
	 */
	public function post($path, $data = array())
	{
		return $this->request($path, array(), $data, 'post');
	}

	/**
	 * Submits a PUT request through the transport.
	 *
	 * @see Desk\Transport::put()
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $data An optional associative array of POST data to
	 * submit with the request.
	 *
	 * @return Desk\Transport\Response
	 */
	public function put($path, $data = array())
	{
		return $this->request($path, array(), $data, 'put');
	}

	/**
	 * Submits a DELETE request through the transport.
	 *
	 * @see Desk\Transport::delete()
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return Desk\Transport\Response
	 */
	public function delete($path, $query = array())
	{
		return $this->request($path, $query, array(), 'delete');
	}

	private function request($path, $query = array(), $parameters = array(), $method = 'GET', $headers = array())
	{
		$url = $this->getUrl($this->host, $path, $query);

		$result = $this->adapter->fetch($url, $parameters, strtoupper($method), $headers);

		$body = $this->adapter->getLastResponse();

		return new Response($body);
	}

	/**
	 * Builds a URL from hostname, path and query string.
	 *
	 * @param string $host The hostname.
	 * @param string $path The original path (can contain "?").
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return string
	 */
	private function getUrl($host, $path = '', $query = array())
	{
		$url = rtrim($host, '/') . '/' . ltrim($path, '/');

		if ($query)
		{
			$separator = (strpos($path, '?') === false) ? '?' : '&';
			$url .= $separator . http_build_query($query);
		}

		return $url;
	}

}
