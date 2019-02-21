<?php

namespace Tests\StopForumSpam;

use Tests\TestCase;

class SearchByEmailTest extends TestCase
{
    protected $instance;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testCreateInstance()
    {
        $sfs = new \StopForumSpam\SearchByEmail('test@test.tld');
        $this->assertIsObject($sfs);
        $this->assertInstanceOf(\StopForumSpam\SearchByEmail::class, $sfs);




//        $rp = new \ReflectionProperty($sfs, 'options');
//        $rp->setAccessible(true);

//        dd($sfs->getOptions());
//
//        $this->assertArrayHasKey('query', $rp->getValue());
    }

    public function _testCreateInstanceBadEmail()
    {
# SearchByEmail used as example
        $sfs = new SearchByEmail('test@test.tld');

        $sfs->asJSON();

        $options = $sfs->getOptions();

        $this->assertTrue($options['query']['json'] === true);

        $result = $sfs->search();

        $this->assertTrue(mb_strpos($result->getHeaderLine('Content-Type'), 'application/json') !== false);
    }

}
