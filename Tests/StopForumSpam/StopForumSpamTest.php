<?php

namespace Tests\StopForumSpam;

use Psr\Http\Message\ResponseInterface;
use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\StopForumSpam;
use Tests\TestCase;

/**
 * Class StopForumSpamTest
 *
 * @package Tests\StopForumSpam
 */
class StopForumSpamTest extends TestCase
{
    /**
     * @var StopForumSpam
     */
    protected $instance;

    /**
     * StopForumSpamTest constructor.
     *
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->instance = new class extends StopForumSpam
        {

        };
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::asJSON
     * @throws HttpException
     */
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

    /**
     * @covers \StopForumSpam\StopForumSpam::asJSONP
     * @throws HttpException
     */
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

    /**
     * @covers \StopForumSpam\StopForumSpam::withConfidence
     * @throws HttpException
     */
    public function testWithConfidence()
    {
        $this->instance->withConfidence();
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['confidence'], true);
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::withNoBadEmail
     * @throws HttpException
     */
    public function testWithNoBadEmail()
    {
        $this->instance->withNoBadEmail();
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['nobademail'], true);
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::withNoBadIp
     * @throws HttpException
     */
    public function testWithNoBadIp()
    {
        $this->instance->withNoBadIp();
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['nobadip'], true);
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::withNoBadUsername
     * @throws HttpException
     */
    public function testWithNoBadUsername()
    {
        $this->instance->withNoBadUsername();
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['nobadusername'], true);
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::withNoBadAll
     * @throws HttpException
     */
    public function testWithNoBadAll()
    {
        $this->instance->withNoBadAll();
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['nobadall'], true);
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::setOptions
     * @throws HttpException
     */
    public function testSetOptionsEmptyVar()
    {
        $this->expectException(HttpException::class);
        $this->instance->setOptions([]);
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::setOptions
     * @throws HttpException
     */
    public function testSetOptionsNotArray()
    {
        $this->expectException(\TypeError::class);
        $this->instance->setOptions('test');
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::getOptions
     */
    public function testGetOptions()
    {
        $options = $this->instance->getOptions();
        $this->assertIsArray($options);
        $this->assertNotEmpty($options);
    }

    /**
     * @covers \StopForumSpam\StopForumSpam::search
     * @throws HttpException
     */
    public function testSearch()
    {
        # Added for proper API response
        $this->instance->setOptions(['query' => ['email' => 'test@test.tld']]);

        $result = $this->instance->search();

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals('OK', $result->getReasonPhrase());
        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * @throws HttpException
     */
    public function testEmptySearch()
    {
        $this->expectException(HttpException::class);
        $this->instance->search();
    }
}
