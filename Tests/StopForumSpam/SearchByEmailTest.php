<?php

namespace Tests\StopForumSpam;

use StopForumSpam\Exceptions\HttpException;
use StopForumSpam\SearchByEmail;
use Tests\TestCase;

/**
 * Class SearchByEmailTest
 *
 * @package Tests\StopForumSpam
 */
class SearchByEmailTest extends TestCase
{
    /**
     * @var SearchByEmail
     */
    protected $instance;

    /**
     * SearchByEmailTest constructor.
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
        $this->instance = new class('test@test.ru') extends SearchByEmail
        {

        };
    }

    /**
     * @covers \StopForumSpam\SearchByEmail::__construct
     * @throws HttpException
     */
    public function testCreateInstance()
    {
        $sfs = new SearchByEmail('test@test.tld');
        $this->assertIsObject($sfs);
        $this->assertInstanceOf(SearchByEmail::class, $sfs);
    }

    /**
     * @covers \StopForumSpam\SearchByEmail::__construct
     * @throws HttpException
     */
    public function testCreateInstanceBadEmail()
    {
        $this->expectException(HttpException::class);
        (new SearchByEmail('NOT_AN_EMAIL_ADDR'))->search();
    }

    /**
     * @covers \StopForumSpam\SearchByEmail::__construct
     * @throws HttpException
     */
    public function testCreateInstanceEmptyEmail()
    {
        $this->expectException(\TypeError::class);
        (new SearchByEmail(null))->search();
    }

    /**
     * @covers \StopForumSpam\SearchByEmail::search
     * @throws HttpException
     */
    public function testSearchByEmail()
    {
        $response = $this->instance->search();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResult = json_decode($response->getBody()->getContents());

        $this->assertTrue(isset($jsonResult->success));
        $this->assertTrue(isset($jsonResult->email));

        $this->assertEquals(1, $jsonResult->success);
    }

}
