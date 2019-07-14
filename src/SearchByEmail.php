<?php

namespace StopForumSpam;

use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchByEmail
 *
 * @package StopForumSpam
 */
class SearchByEmail extends StopForumSpam
{

    /**
     * SearchByEmail
     *
     * @param string $email
     * @param array  $options
     *
     * @throws HttpException
     */
    public function __construct(string $email, array $options = [])
    {
        parent::__construct($options);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setOptions(['query' => ['email' => $email]]);
        } else {
            throw new HttpException('Bad email ' . htmlspecialchars($email) . ' format given.');
        }
    }
}
