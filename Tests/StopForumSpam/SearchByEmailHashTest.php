<?php

namespace Tests\StopForumSpam;

use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\SearchByEmailHash;
use Tests\TestCase;

/**
 * Class SearchByEmailHashTest
 *
 * @package Tests\StopForumSpam
 */
class SearchByEmailHashTest extends TestCase
{
    /**
     * @var SearchByEmailHash
     */
    protected $instance;

    /**
     * SearchByEmailHashTest constructor.
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
        $this->instance = new class(md5('test@test.ru')) extends SearchByEmailHash
        {

        };
    }

    /**
     * @covers \StopForumSpam\SearchByEmailHash::__construct
     * @throws HttpException
     */
    public function testCreateInstance()
    {
        $this->assertIsObject($this->instance);
        $this->assertInstanceOf(SearchByEmailHash::class, $this->instance);
    }

    /**
     * @covers \StopForumSpam\SearchByEmailHash::__construct
     * @throws HttpException
     */
    public function testCreateInstanceBadEmailHash()
    {
        $this->expectException(\TypeError::class);
        (new SearchByEmailHash(null))->search();
    }

    /**
     * @covers \StopForumSpam\SearchByEmailHash::search
     * @throws HttpException
     */
    public function testSearchByEmailHash()
    {
        $response = $this->instance->search();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));
        $this->assertTrue(isset($jsonResult->emailhash));

        $this->assertEquals(1, $jsonResult->success);
    }
}
