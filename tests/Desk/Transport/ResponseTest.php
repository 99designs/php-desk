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

	public function testJson()
	{
		$response = new Response('{"success": true}');
		$json = $response->json();

		$this->assertTrue($json->success);
	}

	public function testInvalidJson()
	{
		$response = new Response('{"success": true'); // no closing bracket

		$this->expectException('\Desk\Exception\JsonDecodeException');
		$json = $response->json();
	}

	private function testBody($body)
	{
		$response = new Response($body);
		$this->assertEqual($body, $response->getBody());
	}

}
