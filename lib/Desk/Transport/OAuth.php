<?php

namespace Desk\Transport;

use Desk\Transport as Transport;

class OAuth implements Transport
{

	/**
	 * The OAuth object being wrapped.
	 *
	 * @var \OAuth
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
	 * Getter for the hostname.
	 *
	 * @return string
	 */
	public function host()
	{
		return $this->host;
	}

	/**
	 * Combined getter/setter for the adapter.
	 *
	 * @param \OAuth $adapter The new adapter (optional)
	 *
	 * @return \OAuth
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
		$path = $this->addQueryString($path, $query);
		return $this->request('get', $path);
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
		return $this->request('post', $path, $data);
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
		return $this->request('put', $path, $data);
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
		$path = $this->addQueryString($path, $query);
		return $this->request('delete', $path);
	}

	private function request($method = 'GET', $path, $parameters = array(), $headers = array())
	{
		$url = rtrim($this->host, '/') . '/' . ltrim($path, '/');

		try {
			$result = $this->adapter->fetch($url, $parameters, strtoupper($method), $headers);
		} catch (\OAuthException $e) {

			$response = new Response($this->adapter->getLastResponse());
			$json = $response->json();

			if (empty($json->success)) {
				throw new \Desk\Exception\ApiCallFailureException(
					'Desk.com request failed',
					$response
				);
			} else {
				throw $e;
			}
		}

		$body = $this->adapter->getLastResponse();
		return new Response($body);
	}

	/**
	 * Adds a query string to a URL path.
	 *
	 * @param string $path The original path (can contain "?").
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return string
	 */
	private function addQueryString($path = '', $query = array())
	{
		if ($query)
		{
			$separator = (strpos($path, '?') === false) ? '?' : '&';
			$path .= $separator . http_build_query($query);
		}

		return $path;
	}

}
