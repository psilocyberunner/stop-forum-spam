<?php

namespace StopForumSpam;

use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchByBulk
 *
 * @package StopForumSpam
 */
class SearchByBulk extends StopForumSpam
{
    /**
     * SearchByBulk constructor.
     *
     * @param array $bulk
     * @param array $options
     *
     * @throws HttpException
     */
    public function __construct(array $bulk, array $options = [])
    {
        parent::__construct($options);

        if (!empty($bulk) && $this->checkBulk($bulk)) {
            $this->setOptions(['query' => $bulk]);
        } else {
            throw new HttpException('Bad bulk data ' . print_r($bulk, 1) . ' format given.');
        }
    }

    /**
     * Check if $bulk is array with proper search-keys (ip, username, email)
     *
     * @param array $bulk
     *
     * @return bool
     */
    private function checkBulk($bulk)
    {
        # We wait $bulk as two-dimensional array
        if (is_array($bulk['ip']) || is_array($bulk['username']) || is_array($bulk['email'])) {
            return true;
        }

        return false;
    }
}
