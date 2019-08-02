<?php

namespace StopForumSpam;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchByDomain
 *
 * @package StopForumSpam
 */
class SearchByDomain extends StopForumSpam
{

    protected $apiUrl = 'https://www.stopforumspam.com/downloads/toxic_domains_whole.txt';

    /**
     * SearchByDomain constructor.
     * Usage similar to SearchByEmail except you need to pass <domain.tld> and not <email@domain.tld>
     *
     * @param string $domain
     *
     * @throws HttpException
     */
    public function __construct(string $domain)
    {
        parent::__construct();

        if (filter_var($domain, FILTER_VALIDATE_DOMAIN)) {

            $this->setOptions(['query' => ['domain' => $domain]]);
        } else {
            throw new HttpException('Bad domain ' . htmlspecialchars($domain) . ' format given.');
        }
    }

    /**
     * @return ResponseInterface
     * @throws HttpException
     */
    public function search(): ResponseInterface
    {
        try {
            $response = $this->doRequest();

            $search = function ($domain) use ($response) {
                foreach (explode("\n", $response->getBody()->getContents()) as $row) {
                    if ($domain == $row) {
                        return 200;
                    }
                }

                return 404;
            };

            $searchStatus = $search($this->getOptions()['query']['domain']);

            if ($searchStatus == 200) {
                $resultJson = [
                    'success' => 1,
                    'domain'  => [
                        'appears' => 1,
                    ],
                ];
            } else {
                $resultJson = [
                    'success' => 1,
                    'domain'  => [
                        'appears' => 0,
                    ],
                ];
            }

            return new Response($searchStatus, ['content-type' => 'application/json'], (string) json_encode($resultJson));

        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
