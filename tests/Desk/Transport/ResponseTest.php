<?php

namespace Desk\Transport;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

	public function testNormalBody()
	{
		$this->assertResponseBody('Test response body');
	}

	public function testUnicodeBody()
	{
		$this->AssertResponseBody('˙uʍop əpısdn pəuɹnʇ pəddılɟ ʇoƃ əɟıl ʎɯ ʍoɥ ʇnoqɐ llɐ ʎɹoʇs ɐ sı sıɥʇ');
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

		$this->setExpectedException('\Desk\Exception\JsonDecodeException');
		$json = $response->json();
	}

	private function assertResponseBody($body)
	{
		$response = new Response($body);
		$this->assertEquals($body, $response->getBody());
	}

}
