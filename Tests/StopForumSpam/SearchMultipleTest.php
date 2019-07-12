<?php

namespace Tests\StopForumSpam;

use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\SearchMultiple;
use Tests\TestCase;

/**
 * Class SearchMultipleTest
 *
 * @package Tests\StopForumSpam
 */
class SearchMultipleTest extends TestCase
{
    /**
     * @var SearchMultiple
     */
    protected $instance;

    /**
     * SearchMultipleTest constructor.
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
        $this->instance = new class([
            'email'    => 'test@test.tld',
            'ip'       => '77.111.247.62',
            'username' => 'test',
        ]) extends SearchMultiple
        {

        };
    }

    /**
     * @covers \StopForumSpam\SearchMultiple::__construct
     * @throws HttpException
     */
    public function testCreateInstance()
    {
        $this->assertIsObject($this->instance);
        $this->assertInstanceOf(SearchMultiple::class, $this->instance);
    }

    /**
     * @covers \StopForumSpam\SearchMultiple::__construct
     * @throws HttpException
     */
    public function testCreateInstanceBadParameters()
    {
        $this->expectException(\TypeError::class);
        (new SearchMultiple(null))->search();

        $this->expectException(HttpException::class);
        (new SearchMultiple([]))->search();
    }

    /**
     * @covers \StopForumSpam\SearchMultiple::search
     * @throws HttpException
     */
    public function testSearchByUsername()
    {
        $response = $this->instance->search();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));

        $this->assertTrue(isset($jsonResult->ip));
        $this->assertTrue(isset($jsonResult->username));
        $this->assertTrue(isset($jsonResult->email));

        $this->assertEquals(1, $jsonResult->success);
    }

}
