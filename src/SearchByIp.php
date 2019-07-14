<?php

namespace StopForumSpam;

use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchByIp
 *
 * @package StopForumSpam
 */
class SearchByIp extends StopForumSpam
{

    /**
     * SearchByIp constructor.
     *
     * @param string $ip
     * @param array  $options
     *
     * @throws HttpException
     */
    public function __construct(string $ip, array $options = [])
    {
        parent::__construct($options);

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->setOptions(['query' => ['ip' => $ip]]);
        } else {
            throw new HttpException('Bad ip ' . htmlspecialchars($ip) . ' format given.');
        }
    }
}
