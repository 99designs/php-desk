<?php

namespace Desk\Client;

use Desk\Client;
use Desk\Exception;

class Articles extends Client
{

	/**
	 * Creates a new Desk.com article in a particular topic.
	 *
	 * @param int $topicId The ID of the Desk.com topic to add the new
	 * article to
	 * @param string $subject Article subject (required)
	 * @param array $options Optional parameters. An associative array
	 * with the following keys:
	 *  - main_content: Main body content of the article
	 *  - published: Whether to publish the article (default false)
	 *  - show_in_portal: Whether to display article in the Portal
	 *  - agent_content: Any private content, only visible to agents
	 *  - email: Email response template (defaults to main_content)
	 *  - chat: Chat response template (defaults to main_content)
	 *  - twitter: Twitter response template (defaults to main_content)
	 *  - question: Q/A response template (defaults to main_content)
	 *  - phone: Phone response template (defaults to main_content)
	 *  - quickcode: Quick code for the article
	 *
	 * @return int The new Desk.com article's ID
	 */
	public function create($topicId, $subject, $options = array())
	{
		// validate arguments
		$topicId = (int) $topicId;
		if ($topicId <= 0)
			throw new Exception\InvalidArgumentException('Topic ID must be specified, and must be greater than 0');

		if (empty($subject))
			throw new Exception\InvalidArgumentException('Article name must be specified');

		if (!is_array($options))
			throw new Exception\InvalidArgumentException('$options must be an array');

		// build request parameters
		$fields = array(
			'main_content',
			'published',
			'show_in_portal',
			'agent_content',
			'email',
			'chat',
			'twitter',
			'question',
			'phone',
			'quickcode',
		);

		$parameters = array('subject' => $subject);
		foreach ($fields as $field)
		{
			if (array_key_exists($field, $options))
				$parameters[$field] = $options[$field];
		}

		// send request
		$response = $this->post("/topics/$topicId/articles.json", $parameters);
		$json = $response->json();

		if (empty($json->success))
			throw new Exception\ApiCallFailureException('Desk.com article creation failed', $response);

		if (empty($json->results->article->id))
			throw new Exception\InvalidApiResponseException('Invalid response from Desk.com API (expected article to have an ID)', $response);

		return $json->results->article->id;
	}

	/**
	 * Retrieves a single article from the Desk.com API.
	 *
	 * @param int $articleId The Desk.com ID of the article to retrieve
	 *
	 * @return stdClass A stdClass object representing the article
	 */
	public function retrieve($articleId)
	{
		// validate arguments
		$articleId = (int) $articleId;
		if ($articleId <= 0)
			throw new Exception\InvalidArgumentException('Article ID must be specified, and must be greater than 0');

		// send request
		$response = $this->get("/articles/$articleId.json");
		$json = $response->json();

		if (empty($json->article))
			throw new Exception\ApiCallFailureException('Desk.com article retrieval failed', $response);

		return $json->article;
	}

	/**
	 * Retrieves articles in a particular topic from the Desk.com API.
	 *
	 * @param int $topicId The Desk.com ID of the topic to retrieve
	 * articles from
	 * @param int $count The maximum number of articles to retrieve.
	 * Must not be greater than 100
	 * @param int $page The page number (if there is more than one
	 * page of results, this allows retrieval of the rest)
	 *
	 * @return stdClass A stdClass object representing the articles
	 */
	public function retrieveAll($topicId, $count = null, $page = null)
	{
		// validate arguments
		$topicId = (int) $topicId;
		if ($topicId <= 0)
			throw new Exception\InvalidArgumentException('Topic ID must be specified, and must be greater than 0');

		// build request parameters
		$parameters = array_filter(array(
			'count' => (int) $count,
			'page'  => (int) $page,
		));

		// send request
		$response = $this->get("/topics/$topicId/articles.json", $parameters);
		$json = $response->json();

		if (!isset($json->results))
			throw new Exception\InvalidApiResponseException('Invalid response from Desk.com API (expected response to have a "results" property)', $response);

		return $json->results;
	}

	/**
	 * Updates fields on an existing Desk.com article entity.
	 *
	 * @param int $articleId the ID of the Desk.com article to update
	 * @param array $fields An associative array, mapping field names
	 * to their new values. Allowed fields are:
	 *  - subject: The subject of the article
	 *  - main_content: Main body content of the article
	 *  - published: Whether to publish the article
	 *  - show_in_portal: Whether to display article in the Portal
	 *  - agent_content: Any private content, only visible to agents
	 *  - email: Email response template
	 *  - chat: Chat response template
	 *  - twitter: Twitter response template
	 *  - question: Q/A response template
	 *  - phone: Phone response template
	 *  - quickcode: Quick code for the article
	 *  - topic_id: To move the article to a different topic
	 *  - mark_translations_outdated: whether to mark other language
	 *    translations as "out-of-date" (ignored if language
	 *    specified)
	 * @param string $language The language to update (blank for
	 * the default language)
	 *
	 * @return void
	 */
	public function update($articleId, $fields, $language = null)
	{
		// validate arguments
		$articleId = (int) $articleId;
		if ($articleId <= 0)
			throw new Exception\InvalidArgumentException('Article ID must be specified, and must be greater than 0');

		if (!is_array($fields))
			throw new Exception\InvalidArgumentException('$fields must be an array');

		// build request parameters
		$parameters = array();

		$fieldNames = array(
			'subject',
			'main_content',
			'published',
			'show_in_portal',
			'agent_content',
			'email',
			'chat',
			'twitter',
			'question',
			'phone',
			'quickcode',
			'topic_id',
			'mark_translations_outdated',
		);

		foreach ($fieldNames as $key)
		{
			if (isset($fields[$key]))
				$parameters[$key] = $fields[$key];
		}

		if ($language !== null)
			$parameters['language'] = $language;

		// send request
		$response = $this->put("/articles/$articleId.json", $parameters);
		$json = $response->json();

		if (empty($json->success))
			throw new Exception\ApiCallFailureException('Desk.com article update failed', $response);
	}

	/**
	 * Destroys an article.
	 *
	 * @param int $articleId
	 *
	 * @return void
	 */
	public function destroy($articleId)
	{
		// validate parameters
		$articleId = (int) $articleId;
		if ($articleId <= 0)
			throw new Exception\InvalidArgumentException('Article ID must be specified, and must be greater than 0');

		// send request
		$response = $this->delete("/articles/$articleId.json");
		$json = $response->json();

		if (empty($json->success))
			throw new Exception\ApiCallFailureException('Desk.com article deletion failed', $response);
	}

}
