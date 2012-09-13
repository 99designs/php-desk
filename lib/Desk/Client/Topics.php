<?php

namespace Desk\Client;

use Desk\Client;
use Desk\Exception;

class Topics extends Client
{

	/**
	 * Creates a new Desk.com topic entity, and returns the new ID.
	 *
	 * @param string $name The name for the new topic
	 * @param string $description The (optional) description for the
	 * new topic
	 * @param boolean $showInPortal Whether or not to show the topic
	 * in the Desk.com portal
	 *
	 * @return int The new Desk.com topic's ID
	 */
	public function create($name, $description = null, $showInPortal = false)
	{
		// validate arguments
		if (empty($name))
			throw new Exception\InvalidArgumentException('Topic name must be specified');

		// build request parameters
		$parameters = array(
			'name'           => $name,
			'show_in_portal' => (boolean) $showInPortal,
		);

		if (!empty($description))
			$parameters['description'] = $description;

		// send request
		$response = $this->post('/topics.json', $parameters);
		$json = $response->json();

		if (empty($json->success))
			throw new Exception\ApiCallFailureException('Desk.com topic creation failed', $response);

		if (empty($json->results->topic->id))
			throw new Exception\InvalidApiResponseException('Invalid response from Desk.com API (expected topic to have an ID)', $response);

		return $json->results->topic->id;
	}

	/**
	 * Retrieves a single topic entity from the Desk.com API.
	 *
	 * @param int $topicId The ID of the Desk.com topic to retrieve
	 *
	 * @return stdClass A stdClass object representing the topic entity
	 */
	public function retrieve($topicId)
	{
		// validate arguments
		$topicId = (int) $topicId;
		if ($topicId <= 0)
			throw new Exception\InvalidArgumentException('Topic ID must be specified, and must be greater than 0');

		// send request
		$response = $this->get("/topics/$topicId.json");
		$json = $response->json();

		if (empty($json->topic))
			throw new Exception\ApiCallFailureException('Desk.com topic retrieval failed', $response);

		return $json->topic;
	}

	/**
	 * Retrieves topics from the Desk.com API with pagination.
	 *
	 * @param int $count The maximum number of topics to retrieve.
	 * Must not be greater than 100
	 * @param int $page The page number (if there is more than one
	 * page of results, this allows retrieval of the rest)
	 *
	 * @return array
	 */
	public function retrieveAll($count = null, $page = null)
	{
		// build request parameters
		$parameters = array_filter(array(
			'count' => (int) $count,
			'page'  => (int) $page,
		));

		// send request
		$response = $this->get('/topics.json', $parameters);
		$json = $response->json();

		if (!isset($json->results))
			throw new Exception\InvalidApiResponseException('Invalid response from Desk.com API (expected response to have a "results" property)', $response);

		return $json->results;
	}

	/**
	 * Updates fields on an existing Desk.com topic entity.
	 *
	 * @param int $topicId The ID of the Desk.com topic to update
	 * @param array $fields An associative array, mapping field names
	 * to their new values. Allowed fields are name, description, and
	 * show_in_portal
	 *
	 * @return void
	 */
	public function update($topicId, $fields)
	{
		// validate arguments
		$topicId = (int) $topicId;
		if ($topicId <= 0)
			throw new Exception\InvalidArgumentException('Topic ID must be specified, and must be greater than 0');

		if (!is_array($fields))
			throw new Exception\InvalidArgumentException('$fields must be an array');

		// build request parameters
		$parameters = array();
		foreach (array('name', 'description', 'show_in_portal') as $key)
		{
			if (isset($fields[$key]))
				$parameters[$key] = $fields[$key];
		}

		// send request
		$response = $this->put("/topics/$topicId.json", $parameters);
		$json = $response->json();

		if (empty($json->success))
			throw new Exception\ApiCallFailureException('Desk.com topic update failed', $response);
	}

	/**
	 * Sets the translated name for a particular Desk.com topic.
	 *
	 * Note: multi-language support must be turned on for the
	 * Desk.com site (subdomain), and the particular language must be
	 * enabled in the site's admin panel.
	 *
	 * This will enable the language for the topic if it wasn't
	 * already enabled.
	 *
	 * @param int $topicId The topic ID to add the translation to
	 * @param string $language The language to add ("de" for German)
	 * @param string $name The translated name to use for the topic in
	 * this language
	 *
	 * @return void
	 */
	public function updateTranslation($topicId, $language, $name)
	{
		// validate arguments
		$topicId = (int) $topicId;
		if ($topicId <= 0)
			throw new Exception\InvalidArgumentException('Topic ID must be specified, and must be greater than 0');

		// build request parameters
		$parameters = array(
			'language' => $language,
			'name'     => $name,
		);

		// send request
		$response = $this->put("/topics/$topicId.json", $parameters);
		$json = $response->json();

		if (empty($json->success))
			throw new Exception\ApiCallFailureException('Desk.com topic translation update failed', $response);
	}

	/**
	 * Destroys a topic.
	 *
	 * @param int $topicId The topic ID to destroy
	 *
	 * @return void
	 */
	public function destroy($topicId)
	{
		// validate arguments
		$topicId = (int) $topicId;
		if ($topicId <= 0)
			throw new Exception\InvalidArgumentException('Topic ID must be specified, and must be greater than 0');

		// send request
		$response = $this->delete("/topics/$topicId.json");
		$json = $response->json();

		if (empty($json->success))
			throw new Exception\ApiCallFailureException('Desk.com topic deletion failed', $response);
	}

}
