<?php

namespace Tests\StopForumSpam;

use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\SearchByDomain;
use Tests\TestCase;

/**
 * Class SearchByDomainTest
 *
 * @package Tests\StopForumSpam
 */
class SearchByDomainTest extends TestCase
{
    /**
     * @var SearchByDomain
     */
    protected $instance;

    /**
     * SearchByDomainTest constructor.
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
        $this->instance = new class('kinogomyhit.ru') extends SearchByDomain
        {

        };
    }

    /**
     * @covers \StopForumSpam\SearchByDomain::__construct
     * @throws HttpException
     */
    public function testCreateInstance()
    {
        $this->assertIsObject($this->instance);
        $this->assertInstanceOf(SearchByDomain::class, $this->instance);
    }

    /**
     * @covers \StopForumSpam\SearchByDomain::__construct
     * @throws HttpException
     */
    public function testCreateInstanceBadEmail()
    {
        $this->expectException(\TypeError::class);
        (new SearchByDomain(null))->search();
    }

    /**
     * @covers \StopForumSpam\SearchByDomain::search
     * @throws HttpException
     */
    public function testSearchByDomain()
    {
        $response = $this->instance->search();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));
        $this->assertTrue(isset($jsonResult->domain));

        # When a query results in a blacklist result, the frequncy field will be a value of 255, and the lastseen date will update to the current time (UTC)
        $this->assertTrue(isset($jsonResult->domain->appears));
        $this->assertEquals(1, $jsonResult->domain->appears);

        $this->assertEquals(1, $jsonResult->success);
    }

    public function testSearchByClearDomain()
    {
        $client = new SearchByDomain('test.ru');

        $response = $client->search();

        $this->assertEquals(404, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));
        $this->assertTrue(isset($jsonResult->domain));

        # When a query results in a blacklist result, the frequncy field will be a value of 255, and the lastseen date will update to the current time (UTC)
        $this->assertTrue(isset($jsonResult->domain->appears));
        $this->assertEquals(0, $jsonResult->domain->appears);

        $this->assertEquals(1, $jsonResult->success);
    }

}
