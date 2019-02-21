<?php

namespace Tests\StopForumSpam;

use Psr\Http\Message\ResponseInterface;
use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\StopForumSpam;
use Tests\TestCase;

class StopForumSpamTest extends TestCase
{
    /**
     * @var StopForumSpam
     */
    protected $instance;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp()
    {
        // Create a new instance from the Abstract Class
        $this->instance = new class extends StopForumSpam
        {

        };
    }

    public function testAsJSON()
    {
        $this->instance->asJSON();

        $options = $this->instance->getOptions();

        $this->assertTrue($options['query']['json'] === true);

        # configure instance to send request
        $this->instance->setOptions(['query' => ['email' => 'test@test.tld']]);

        $result = $this->instance->search();

        $this->assertTrue(mb_strpos($result->getHeaderLine('Content-Type'), 'application/json') !== false);
    }

    public function testAsJSONP()
    {
        $this->instance->asJSONP('testFunc');

        $options = $this->instance->getOptions();

        $this->assertTrue($options['query']['jsonp'] === true);
        $this->assertTrue($options['query']['callback'] === 'testFunc');

        # configure instance to send request
        $this->instance->setOptions(['query' => ['ip' => '10.11.12.13']]);

        $result = $this->instance->search();

        $this->assertTrue(mb_strpos($result->getHeaderLine('Content-Type'), 'text/javascript') !== false);
        $this->assertTrue(mb_strpos($result->getBody()->getContents(), 'testFunc') !== false);
    }

    public function testSetApiToken()
    {
        $this->instance->setApiToken('some-token');
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['api_key'], 'some-token');
    }

    public function testWithConfidence()
    {
        $this->instance->withConfidence();
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['confidence'], true);
    }

    public function testSetOptionsEmptyVar()
    {
        $this->expectException(HttpException::class);
        $this->instance->setOptions([]);
    }

    public function testSetOptionsNotArray()
    {
        $this->expectException(\TypeError::class);
        $this->instance->setOptions('test');
    }

    public function testGetOptions()
    {
        $options = $this->instance->getOptions();
        $this->assertIsArray($options);
        $this->assertNotEmpty($options);
    }

    public function testSearch()
    {
        # Added for proper API response
        $this->instance->setOptions(['query' => ['email' => 'test@test.tld']]);

        $result = $this->instance->search();

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals('OK', $result->getReasonPhrase());
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testEmptySearch()
    {
        $this->expectException(HttpException::class);
        $this->instance->search();
    }
}
