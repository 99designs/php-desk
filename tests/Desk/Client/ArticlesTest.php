<?php

namespace Desk\Client;

require_once(dirname(__FILE__) . '/AbstractClientTestCase.php');

class ArticlesTest extends \Desk\Client\AbstractClientTestCase
{

	protected function getType()
	{
		return \Desk\Client::ARTICLES;
	}

	public function testCreate()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('post')
			->with(
				'/api/v1/topics/111/articles.json',
				array(
					'subject'      => 'Example Article',
					'main_content' => 'Lorem ipsum',
					'quickcode'    => 'EXAMPLE_QUICK',
				)
			)
			->andReturn(new \Desk\Transport\Response('{
				"success": true,
				"results": {
					"article": {
						"id": 234,
						"subject": "Example Article",
						"quickcode": "EXAMPLE_QUICK"
					}
				}
			}'));

		$articleId = $client->create(111, 'Example Article', array(
			'main_content' => 'Lorem ipsum',
			'quickcode' => 'EXAMPLE_QUICK',
		));

		$this->assertEqual(234, $articleId, "Created article ID \"$articleId\" differs from the expected ID 234");
	}

	public function testRetrieve()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('get')
			->with('/api/v1/articles/222.json', array())
			->andReturn(new \Desk\Transport\Response('{
				"article": {
					"id": 222,
					"subject": "Example Article"
				}
			}'));

		$article = $client->retrieve(222);

		$this->assertEqual('Example Article', $article->subject, "Retrieved article subject \"{$article->subject}\" differs from the expected subject \"Example Article\"");
	}

	public function testRetrieveInvalidId()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('get', 'post', 'put', 'delete')->never();

		$this->expectException('InvalidArgumentException');
		$client->retrieve('bongo');
	}

	public function testRetrieveAll()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('get')
			->with('/api/v1/topics/454/articles.json', array('count' => 1, 'page' => 3))
			->andReturn(new \Desk\Transport\Response('{
				"results": [{
					"article": {
						"id": 654,
						"subject": "An article"
					}
				}],
				"page": 3,
				"count": 1,
				"total": 20
			}'));

		$client->retrieveAll(454, 1, 3);
	}

	public function testUpdate()
	{
		$client = $this->client();

		$fields = array(
			'subject' => 'Test Article',
		);

		$client->transport()
			->shouldReceive('put')
			->with('/api/v1/articles/789.json', $fields)
			->andReturn(new \Desk\Transport\Response('{
				"success": true,
				"results": {
					"article": {
						"id": 789,
						"subject": "Test Article"
					}
				}
			}'));

		$article = $client->update(789, $fields);
	}

	public function testDestroy()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('delete')
			->with('/api/v1/articles/412.json', array())
			->andReturn(new \Desk\Transport\Response('{
				"success": true
			}'));

		$client->destroy(412);
	}

	public function testDestroyInvalidId()
	{
		$client = $this->client();

		$client->transport()
			->shouldReceive('get', 'post', 'put', 'delete')->never();

		$this->expectException('InvalidArgumentException');
		$client->destroy('bazbar');
	}

}
