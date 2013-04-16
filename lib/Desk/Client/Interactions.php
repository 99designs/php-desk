<?php

namespace Desk\Client;

use Desk\Client;

class Interactions extends Client
{
	public function create($options = array())
	{
		if (empty($options['interaction_subject'])) {
			throw new \Desk\Exception\InvalidArgumentException('Subject required');
		}

		$fields = array(
			'interaction_subject',
			'customer_id',
			'customer_twitter',
			'customer_email',
			'customer_custom_key',
			'customer_language',
			'case_id',
			'external_id',
			'case_labels',
			'case_custom_key',
			'case_language',
			'interaction_channel',
			'interaction_body'
		);

		$parameters = $options;
		foreach ($fields as $field) {
			if (array_key_exists($field, $options)) {
				$parameters[$field] = $options[$field];
			}
		}

		$response = $this->post('/interactions.json', $parameters);
		$json = $response->json();

		if (empty($json->success)) {
			throw new \Desk\Exception\ApiCallFailureException(
				'Desk.com interaction creation failed',
				$response
			);
		}

		if (empty($json->results->case->id)) {
			throw new \Desk\Exception\InvalidApiResponseException(
				'Invalid response from Desk.com API (expected interaction to create a case)',
				$response
			);
		}

		return $json;
	}

	public function all($count = null, $page = null)
	{
		$parameters = array_filter(array(
			'count' => (int)$count,
			'page' => (int)$page
		));

		$response = $this->get('/interactions.json', $parameters);
		$json = $response->json();

		return $json;
	}
}
