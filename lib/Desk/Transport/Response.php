<?php

namespace Desk\Transport;

class Response
{

	private $body = '';

	public function __construct($body)
	{
		$this->body = $body;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function json()
	{
		$json = $this->getBody();
		$result = json_decode($json);

		// handle error
		$error = json_last_error();
		json_decode(true); // reset error state. see PHP bug #54484

		if ($error != JSON_ERROR_NONE)
			throw new \Desk\Exception\JsonDecodeException($error);

		return $result;
	}

}
