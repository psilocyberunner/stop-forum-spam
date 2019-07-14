<?php

namespace StopForumSpam;

use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchByUsername
 *
 * @package StopForumSpam
 */
class SearchByUsername extends StopForumSpam
{

    /**
     * SearchByUsername constructor.
     *
     * @param string $username
     * @param array  $options
     *
     * @throws HttpException
     */
    public function __construct(string $username, array $options = [])
    {
        parent::__construct($options);

        if (!empty($username)) {
            $this->setOptions(['query' => ['username' => $username]]);
        } else {
            throw new HttpException('Can not use empty string as search criteria');
        }
    }
}
