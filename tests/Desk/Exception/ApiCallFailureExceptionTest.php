<?php

namespace Desk\Exception;

class ApiCallFailureExceptionTest extends \UnitTestCase
{

	public function testConstructWithNoArguments()
	{
		$ex = new ApiCallFailureException();
		$this->assertEqual('Desk.com API call failed -- No response body received', $ex->getMessage());
	}

	public function testConstructWithMessage()
	{
		$ex = new ApiCallFailureException('foobar');
		$this->assertEqual('foobar -- No response body received', $ex->getMessage());
	}

	public function testConstructWithMessageAndResponse()
	{
		$response = \Mockery::mock('\Desk\Transport\Response')
			->shouldReceive('getBody')
			->andReturn('example response')
			->getMock();

		$ex = new ApiCallFailureException('lorem ipsum', $response);
		$this->assertEqual('lorem ipsum -- Full response body: example response', $ex->getMessage());
	}

	public function testConstructWithInvalidResponse()
	{
		$ex = new ApiCallFailureException('barbaz', new \stdClass());
		$this->assertEqual('barbaz -- No response body received', $ex->getMessage());
	}

}
