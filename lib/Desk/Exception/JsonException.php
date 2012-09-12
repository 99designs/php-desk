<?php

namespace Desk\Exception;

class JsonException extends UnexpectedValueException
{

	public function __construct($code)
	{
		$message = $this->_getMessage($code);
		parent::__construct($message, $code);
	}

	private function _getMessage($code)
	{
		$messages = array(
			JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
			JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX => 'Syntax error',
			5 => 'Malformed UTF-8 characters, possibly incorrectly encoded', // JSON_ERROR_UTF8, added in PHP 5.3.3
		);

		if (array_key_exists($code, $messages))
			return $messages[$code];

		return 'Unknown error code';
	}

}
