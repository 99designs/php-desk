<?php

namespace Desk;

interface Transport
{

	/**
	 * Submits a GET request through the transport.
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return Desk\Transport\Response
	 */
	public function get($path, $query = array());

	/**
	 * Submits a POST request through the transport.
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $data Any POST data to submit with the request.
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return Desk\Transport\Response
	 */
	public function post($path, $data = array());

	/**
	 * Submits a PUT request through the transport.
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $data An optional associative array of POST data to
	 * submit with the request.
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return Desk\Transport\Response
	 */
	public function put($path, $data = array());

	/**
	 * Submits a DELETE request through the transport.
	 *
	 * @param string $path The path to submit the request to.
	 * @param array $query An optional associative array of query
	 * string parameters.
	 *
	 * @return Desk\Transport\Response
	 */
	public function delete($path, $query = array());

}
