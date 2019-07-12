<?php

namespace StopForumSpam;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use StopForumSpam\Exceptions\HttpException;

/**
 * Class SubmitSpamReport
 *
 * @package StopForumSpam
 *
 * @property Client $requestClient
 */
class SubmitSpamReport extends StopForumSpam
{

    /**
     * API base url
     *
     * @var string
     */
    protected $apiUrl = 'http://www.stopforumspam.com/add.php';

    /**
     * API token for submit requests
     *
     * @var string
     */
    protected $apiToken;

    /**
     * StopForumSpam constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = array_merge_recursive([
            'base_uri' => $this->apiUrl,
        ], $options);
    }

    /**
     * @param $name
     *
     * @return Client
     * @throws HttpException
     */
    public function __get($name)
    {
        if (empty($this->options['base_uri'])) {
            throw new HttpException('Base API Uri can not be empty.');
        }

        if (empty($this->options['query']['api_key'])) {
            throw new HttpException('Can not perform submit requests without api token parameter.');
        }

        return $this->requestClient = new Client($this->options);
    }

    /**
     * API token used to submit your spam data.
     *
     * @param string $apiToken
     *
     * @return $this
     * @throws HttpException
     */
    public function setApiToken($apiToken): self
    {
        $this->setOptions(['query' => ['api_key' => $apiToken]]);

        return $this;
    }

    /**
     * Set spammer username
     *
     * @param string $username
     *
     * @return $this
     * @throws HttpException
     */
    public function setUsername($username)
    {
        $this->setOptions(['query' => ['username' => urlencode($username)]]);

        return $this;
    }

    /**
     * Set spammer ip
     *
     * @param string $ip
     *
     * @return $this
     * @throws HttpException
     */
    public function setIpAddress($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->setOptions(['query' => ['ip_addr' => urlencode($ip)]]);
        } else {
            throw new HttpException('Bad ip ' . htmlspecialchars($ip) . ' format given.');
        }

        return $this;
    }

    /**
     * Set evidence for spam report (spammer message as example)
     *
     * @param string $evidence
     *
     * @return $this
     * @throws HttpException
     */
    public function setEvidence($evidence)
    {
        $this->setOptions(['query' => ['evidence' => urlencode($evidence)]]);

        return $this;
    }

    /**
     * Set spammer email
     *
     * @param string $email
     *
     * @return $this
     * @throws HttpException
     */
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setOptions(['query' => ['email' => urlencode($email)]]);
        } else {
            throw new HttpException('Bad email ' . htmlspecialchars($email) . ' format given.');
        }

        return $this;
    }

    /**
     * Submit spam report
     *
     * @return ResponseInterface
     * @throws HttpException
     */
    public function submit(): ResponseInterface
    {
        try {
            $response = $this->doRequest();

            return $response;
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}