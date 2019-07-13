<?php

namespace StopForumSpam;

use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchByDomain
 *
 * @package StopForumSpam
 */
class SearchByDomain extends StopForumSpam
{

    /**
     * SearchByDomain constructor.
     * Usage similar to SearchByEmail except you need to pass <@domain.tld> and not <email@domain.tld>
     *
     * @param string $domain
     * @param array  $options
     *
     * @throws HttpException
     */
    public function __construct($domain, array $options = [])
    {
        parent::__construct($options);

        if (filter_var($domain, FILTER_VALIDATE_DOMAIN)) {

            if ($domain[0] !== '@') {
                $domain = '@' . $domain;
            }

            $this->setOptions(['query' => ['email' => $domain]]);
        } else {
            throw new HttpException('Bad domain ' . htmlspecialchars($domain) . ' format given.');
        }
    }
}
