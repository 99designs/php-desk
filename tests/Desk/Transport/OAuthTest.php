<?php

namespace Desk\Transport;

class OAuthTest extends \UnitTestCase
{

	/**
	 * Gets a Desk\Transport\OAuth object with a mock adapter.
	 *
	 * @return Desk\Transport\OAuth
	 */
	private function oauth()
	{
		$oauth = new \Desk\Transport\OAuth('http://example.org', 'key', 'secret');
		$oauth->adapter(\Mockery::mock('\OAuth'));

		return $oauth;
	}

	public function testSetToken()
	{
		$oauth = $this->oauth();

		$oauth->adapter()
			->shouldReceive('setToken')
			->with('token', 'token-secret')
			->andReturn(true);

		$oauth->setToken('token', 'token-secret');
	}

	public function testGet()
	{
		$oauth = $this->oauth();

		$oauth->adapter()
			->shouldReceive('fetch')
			->with('http://example.org/hello', array(), 'GET', array())
			->andReturn(true);

		$oauth->adapter()
			->shouldReceive('getLastResponse')
			->andReturn('return value');

		$response = $oauth->get('/hello');

		$this->assertEqual($response->getBody(), 'return value');
	}

	public function testGetWithQueryParameters()
	{
		$oauth = $this->oauth();

		$oauth->adapter()
			->shouldReceive('fetch')
			->with('http://example.org/hello?getkey=getvalue', array(), 'GET', array())
			->andReturn(true);

		$oauth->adapter()
			->shouldReceive('getLastResponse')
			->andReturn('return value');

		$response = $oauth->get('/hello', array('getkey' => 'getvalue'));

		$this->assertEqual($response->getBody(), 'return value');
	}

	public function testPost()
	{
		$oauth = $this->oauth();

		$oauth->adapter()
			->shouldReceive('fetch')
			->with('http://example.org/hello', array('postkey' => 'postvalue'), 'POST', array())
			->andReturn(true);

		$oauth->adapter()
			->shouldReceive('getLastResponse')
			->andReturn('return value');

		$response = $oauth->post('/hello', array('postkey' => 'postvalue'));

		$this->assertEqual($response->getBody(), 'return value');
	}

	public function testPut()
	{
		$oauth = $this->oauth();

		$oauth->adapter()
			->shouldReceive('fetch')
			->with('http://example.org/hello', array('putkey' => 'putvalue'), 'PUT', array())
			->andReturn(true);

		$oauth->adapter()
			->shouldReceive('getLastResponse')
			->andReturn('return value');

		$response = $oauth->put('/hello', array('putkey' => 'putvalue'));

		$this->assertEqual($response->getBody(), 'return value');
	}

	public function testDelete()
	{
		$oauth = $this->oauth();

		$oauth->adapter()
			->shouldReceive('fetch')
			->with('http://example.org/hello', array(), 'DELETE', array())
			->andReturn(true);

		$oauth->adapter()
			->shouldReceive('getLastResponse')
			->andReturn('return value');

		$response = $oauth->delete('/hello');

		$this->assertEqual($response->getBody(), 'return value');
	}

}
