<?php

namespace Tests\StopForumSpam;

use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\SearchByUsername;
use Tests\TestCase;

/**
 * Class SearchByUsernameTest
 *
 * @package Tests\StopForumSpam
 */
class SearchByUsernameTest extends TestCase
{
    /**
     * @var SearchByUsername
     */
    protected $instance;

    /**
     * SearchByUsernameTest constructor.
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
        $this->instance = new class('test') extends SearchByUsername
        {

        };
    }

    /**
     * @covers \StopForumSpam\SearchByUsername::__construct
     * @throws HttpException
     */
    public function testCreateInstance()
    {
        $sfs = new SearchByUsername('test');
        $this->assertIsObject($sfs);
        $this->assertInstanceOf(SearchByUsername::class, $sfs);
    }

    /**
     * @covers \StopForumSpam\SearchByUsername::__construct
     * @throws HttpException
     */
    public function testCreateInstanceBadUsername()
    {
        $this->expectException(HttpException::class);
        (new SearchByUsername(null))->search();
    }

    /**
     * @covers \StopForumSpam\SearchByUsername::search
     * @throws HttpException
     */
    public function testSearchByUsername()
    {
        $response = $this->instance->search();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));
        $this->assertTrue(isset($jsonResult->username));

        $this->assertEquals(1, $jsonResult->success);
    }

}
