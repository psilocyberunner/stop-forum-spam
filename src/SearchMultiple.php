<?php

namespace StopForumSpam;

use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchMultiple
 *
 * @package StopForumSpam
 */
class SearchMultiple extends StopForumSpam
{

    /**
     * SearchMultiple constructor.
     *
     * @param array $parameters
     * @param array $options
     *
     * @throws HttpException
     */
    public function __construct(array $parameters, array $options = [])
    {
        parent::__construct($options);

        if (!empty($parameters)) {
            $this->setOptions(['query' => $parameters]);
        } else {
            throw new HttpException('Bad ip ' . print_r($parameters, true) . ' format given.');
        }
    }
}
