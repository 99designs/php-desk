<?php

namespace Desk;

class ClientTest extends \UnitTestCase
{

	public function testIsValidTypeWithInvalidTypes()
	{
		$types = array(
			'foo',
			'',
			0,
			-1,
		);

		foreach ($types as $type)
		{
			$this->assertFalse(Client::isValidType($type));
		}
	}

	public function testAllClassConstantsAreValidTypes()
	{
		$reflection = new \ReflectionClass('\Desk\Client');

		foreach ($reflection->getConstants() as $constant => $constantValue)
		{
			$this->assertTrue(
				Client::isValidType($constantValue),
				"Desk\Client::isValidType() returns FALSE for class " .
				"constant \"$constant\", all Desk\Client class " .
				"constants should be valid client types"
			);
		}
	}

}
