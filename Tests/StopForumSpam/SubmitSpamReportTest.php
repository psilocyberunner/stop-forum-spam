<?php

namespace Tests\StopForumSpam;

use StopForumSpam\SubmitSpamReport;
use Tests\TestCase;

class SubmitSpamReportTest extends TestCase
{
    /**
     * @var SubmitSpamReport
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
        /** @var SubmitSpamReport instance */
        $this->instance = new class extends SubmitSpamReport
        {

        };
    }

    /**
     * @covers \StopForumSpam\SubmitSpamReport::setApiToken
     * @throws \StopForumSpam\Exceptions\HttpException
     */
    public function testSetApiToken()
    {
        $this->instance->setApiToken('some-token');
        $options = $this->instance->getOptions();
        $this->assertEquals($options['query']['api_key'], 'some-token');
    }
}
