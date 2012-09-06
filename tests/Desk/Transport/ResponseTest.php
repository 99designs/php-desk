<?php

namespace Desk\Transport;

class ResponseTest extends \UnitTestCase
{

	public function testNormalBody()
	{
		$this->testBody('Test response body');
	}

	public function testUnicodeBody()
	{
		$this->testBody('˙uʍop əpısdn pəuɹnʇ pəddılɟ ʇoƃ əɟıl ʎɯ ʍoɥ ʇnoqɐ llɐ ʎɹoʇs ɐ sı sıɥʇ');
	}

	private function testBody($body)
	{
		$response = new Response($body);
		$this->assertEqual($body, $response->getBody());
	}

}
