<?php

namespace Desk\Exception;

class ApiCallFailureException extends UnexpectedValueException
{

	public function __construct($message = null, $response = null)
	{
		if (empty($message))
			$message = 'Desk.com API call failed';

		if ($response instanceof Response)
			$response = $response->getBody();
		else if (!is_string($response))
			$response = null;

		if (!empty($response))
			$message .= ' -- Full response body: ' . (string) $response;
		else
			$message .= ' -- No response body received';

		parent::__construct($message);
	}

}
