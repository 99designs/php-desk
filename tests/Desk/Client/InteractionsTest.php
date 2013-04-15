<?php

namespace Desk\Client;

use Desk\Client;
use Desk\Transport\Response;

class InterationsTest extends AbstractClientTestCase
{
	protected function getType()
	{
		return Client::INTERACTIONS;
	}

	public function testCreate()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('post')
			->with(
				'/api/v1/interactions.json',
				array(
					'interaction_subject' => 'Example subject',
					'customer_email'      => 'example@test.com'
				))
				->andReturn(new Response(
					json_encode(array(
						'success' => true,
						'results' => array(
							'case' => array(
								'id' => 1
							),
							'customer' => array(
								'id' => 2
							)
						)
					))
				));

		$response = $client->create(array(
			'interaction_subject' => 'Example subject',
			'customer_email' => 'example@test.com'
		));

		$this->assertEquals($response->results->case->id, 1);
		$this->assertEquals($response->results->customer->id, 2);
	}

	public function testAll()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('get')
			->with('/api/v1/interactions.json', array())
			->andReturn(new Response(
				json_encode(array(
					'success' => true,
					'results' => array(
						'interaction' => array(
							'id' => 1
						)
					),
					'total' => 1
				))
			));

		$response = $client->all();

		$this->assertEquals(1, count($response->results));
		$this->assertEquals(1, $response->total);
	}
}