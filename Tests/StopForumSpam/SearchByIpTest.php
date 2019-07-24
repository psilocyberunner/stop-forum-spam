<?php

namespace Tests\StopForumSpam;

use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\SearchByIp;
use Tests\TestCase;

/**
 * Class SearchByIpTest
 *
 * @package Tests\StopForumSpam
 */
class SearchByIpTest extends TestCase
{
    /**
     * @var SearchByIp
     */
    protected $instance;

    /**
     * SearchByIpTest constructor.
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
        $this->instance = new class('77.111.247.62') extends SearchByIp
        {

        };
    }

    /**
     * @covers \StopForumSpam\SearchByIp::__construct
     * @throws HttpException
     */
    public function testCreateInstance()
    {
        $sfs = new SearchByIp('77.111.247.62');
        $this->assertIsObject($sfs);
        $this->assertInstanceOf(SearchByIp::class, $sfs);
    }

    /**
     * @covers \StopForumSpam\SearchByIp::__construct
     * @throws HttpException
     */
    public function testCreateInstanceBadIp()
    {
        $this->expectException(\TypeError::class);
        (new SearchByIp(null))->search();
    }

    /**
     * @covers \StopForumSpam\SearchByIp::search
     * @throws HttpException
     */
    public function testSearchByIp()
    {
        $response = $this->instance->search();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));
        $this->assertTrue(isset($jsonResult->ip));

        $this->assertEquals(1, $jsonResult->success);
    }

    /**
     * @covers \StopForumSpam\SearchByIp::withNoTorExit
     * @throws HttpException
     */
    public function testSearchByIpWithNoTorExit()
    {
        $sfs = new SearchByIp('1.2.3.4');
        $sfs->withNoTorExit();

        $options = $sfs->getOptions();

        $this->assertEquals('1.2.3.4', $options['query']['ip']);
        $this->assertEquals(true, $options['query']['notorexit']);

        $result = $sfs->search();

        $this->assertEquals(200, $result->getStatusCode());

        $result = json_decode($result->getBody()->getContents(), true);
        $this->assertEquals(1, $result['success']);
    }

    /**
     * @covers \StopForumSpam\SearchByIp::withBadTorExit
     * @throws HttpException
     */
    public function testSearchByIpWithBadTorExit()
    {
        $sfs = new SearchByIp('1.2.3.4');
        $sfs->withBadTorExit();

        $options = $sfs->getOptions();

        $this->assertEquals('1.2.3.4', $options['query']['ip']);
        $this->assertEquals(true, $options['query']['badtorexit']);

        $result = $sfs->search();

        $this->assertEquals(200, $result->getStatusCode());

        $result = json_decode($result->getBody()->getContents(), true);
        $this->assertEquals(1, $result['success']);
    }
}
