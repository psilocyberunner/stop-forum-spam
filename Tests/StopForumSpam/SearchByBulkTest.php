<?php

namespace Tests\StopForumSpam;

use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\SearchByBulk;
use Tests\TestCase;

/**
 * Class SearchByBulkTest
 *
 * @package Tests\StopForumSpam
 */
class SearchByBulkTest extends TestCase
{
    /**
     * @var SearchByBulk
     */
    protected $instance;

    /**
     * SearchByBulkTest constructor.
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
        $bulkData = [
            'ip'       => [
                '127.0.0.1',
                '77.111.247.62',
            ],
            'username' => [
                'Nicole',
                'some-random-username-for-test',
            ],
            'email'    => [
                'shamrykenkokatya@gmail.com',
                'some-email@test.tld',
            ],
        ];

        $this->instance = new class($bulkData) extends SearchByBulk
        {

        };
    }

    /**
     * @covers \StopForumSpam\SearchByBulk::__construct
     * @throws HttpException
     */
    public function testCreateInstance()
    {
        $this->assertIsObject($this->instance);
        $this->assertInstanceOf(SearchByBulk::class, $this->instance);
    }

    /**
     * @covers \StopForumSpam\SearchByBulk::__construct
     * @throws HttpException
     */
    public function testCreateInstanceBadBulkData()
    {
        $this->expectException(\TypeError::class);
        (new SearchByBulk(null))->search();
    }

    /**
     * @covers \StopForumSpam\SearchByBulk::search
     * @throws HttpException
     */
    public function testSearchByBulk()
    {
        $response = $this->instance->search();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));
        $this->assertTrue(isset($jsonResult->ip));
        $this->assertTrue(isset($jsonResult->username));
        $this->assertTrue(isset($jsonResult->email));

        $this->assertTrue(!empty($jsonResult->ip));
        $this->assertTrue(!empty($jsonResult->username));
        $this->assertTrue(!empty($jsonResult->email));

        $this->assertEquals(2, count($jsonResult->ip));
        $this->assertEquals(2, count($jsonResult->username));
        $this->assertEquals(2, count($jsonResult->email));

        $this->assertEquals('127.0.0.1', $jsonResult->ip[0]->value);
        $this->assertEquals('77.111.247.62', $jsonResult->ip[1]->value);

        $this->assertEquals('Nicole', $jsonResult->username[0]->value);
        $this->assertEquals('some-random-username-for-test', $jsonResult->username[1]->value);

        $this->assertEquals('shamrykenkokatya@gmail.com', $jsonResult->email[0]->value);
        $this->assertEquals('some-email@test.tld', $jsonResult->email[1]->value);

        $this->assertEquals(1, $jsonResult->success);
    }

    /**
     * @covers \StopForumSpam\SearchByBulk::search
     * @covers \StopForumSpam\SearchByBulk::checkBulk
     * @throws HttpException
     */
    public function testSearchByBadBulkData()
    {
        $this->expectException(HttpException::class);
        $this->instance = new class([
            'ip' => [
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
                '127.0.0.1',
            ],
        ]) extends SearchByBulk
        {

        };
    }

}
