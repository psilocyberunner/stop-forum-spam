<?php

namespace StopForumSpam;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use StopForumSpam\Exceptions\HttpException;

/**
 * Class StopForumSpam
 *
 * @package StopForumSpam
 *
 * @property Client $requestClient
 */
abstract class StopForumSpam
{
    /**
     * API base url
     *
     * @var string
     */
    protected $apiUrl = 'http://api.stopforumspam.org/api';

    /**
     * Preferred response type
     *
     * @var string
     */
    protected $responseType = 'json';

    /**
     * Options for constructing Guzzle client instance
     * http://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @var array
     */
    protected $options = [];

    /**
     * StopForumSpam constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = array_merge_recursive([
            'base_uri' => $this->apiUrl,
            'query'    => [
                'json' => true,
            ],
        ], $options);
    }

    /**
     * Set runtime options.
     *
     * @param array $options
     *
     * @return StopForumSpam
     * @throws HttpException
     */
    public function setOptions(array $options): self
    {
        if (empty($options)) {
            throw new HttpException('Can not use empty $options array');
        }

        $this->options = array_replace_recursive($this->options, $options);

        return $this;
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
            throw new HttpException('Base Uri can not be empty.');
        }

        if (empty($this->options['query'])) {
            throw new HttpException('Can not perform search requests without query parameters.');
        }

        return $this->requestClient = new Client($this->options);
    }

    /**
     * @return ResponseInterface
     */
    protected function doRequest(): ResponseInterface
    {
        return $this->requestClient->get($this->apiUrl, $this->options);
    }

    /**
     * This call will return a mime type of application/json
     *
     * @return StopForumSpam
     * @throws HttpException
     */
    public function asJSON(): self
    {
        $this->setOptions(['query' => ['json' => true]]);

        return $this;
    }

    /**
     * Provide ajax/jquery support with JSONP, which allows for a callback function to be specified around a json result.
     *
     * @param string $function
     *
     * @return StopForumSpam
     * @throws HttpException
     */
    public function asJSONP($function): self
    {
        $this->setOptions(['query' => ['jsonp' => true, 'callback' => $function]]);

        return $this;
    }

    /**
     * Return a serialized PHP result
     *
     * @return StopForumSpam
     * @throws HttpException
     */
    public function asSerialized(): self
    {
        $this->setOptions(['query' => ['serial' => true]]);

        return $this;
    }

    /**
     * This confidence score is a reasonably good indicator that the field under test would result in unwanted activity.
     *
     * @return StopForumSpam
     * @throws HttpException
     */
    public function withConfidence(): self
    {
        $this->setOptions(['query' => ['confidence' => true]]);

        return $this;
    }

    /**
     * @return ResponseInterface
     * @throws HttpException
     */
    public function search(): ResponseInterface
    {
        try {
            $response = $this->doRequest();

            return $response;
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * This provides the ability to have API results filtered if the lastseen date is older than the age (in days).
     * If the lastseen date is 90 days ago and the &expire=60 parameter is passed with the query, then a "not found" API result will be returned.
     *
     * @param int $expire
     *
     * @return StopForumSpam
     * @throws HttpException
     */
    public function withExpire(int $expire): self
    {
        $this->setOptions(['query' => ['expire' => $expire]]);

        return $this;
    }

    /**
     * Appending &unix to any query will return data/time results as UNIXTIME format, that being seconds since Unix epoch start
     *
     * @return StopForumSpam
     * @throws HttpException
     */
    public function withUnixTimestamp(): self
    {
        $this->setOptions(['query' => ['unix' => true]]);

        return $this;
    }

    /**
     * To ignore the email/domain list checks
     *
     * @return $this
     * @throws HttpException
     */
    public function withNoBadEmail()
    {
        $this->setOptions(['query' => ['nobademail' => true]]);

        return $this;
    }

    /**
     * To ignore the username (partial string) list checks
     *
     * @return $this
     * @throws HttpException
     */
    public function withNoBadUsername()
    {
        $this->setOptions(['query' => ['nobadusername' => true]]);

        return $this;
    }

    /**
     * To ignore the IP lists (which includes some of the Internets most hostile spam friendly networks)
     *
     * @return $this
     * @throws HttpException
     */
    public function withNoBadIp()
    {
        $this->setOptions(['query' => ['nobadip' => true]]);

        return $this;
    }

    /**
     * To ignore all wildcard checks
     *
     * @return $this
     * @throws HttpException
     */
    public function withNoBadAll()
    {
        $this->setOptions(['query' => ['nobadall' => true]]);

        return $this;
    }


    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
