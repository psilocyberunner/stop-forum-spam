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
     * Up to 15 queries of any field combination can be made by constructing the fields as an array.
     */
    const SearchVarsLimit = 15;

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
            throw new HttpException('Bad bulk data ' . print_r($bulk, true) . ' format given or search limit (' . self::SearchVarsLimit . ') exceeded.');
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
            if ((count($bulk['ip'] ?? []) + count($bulk['username'] ?? []) + count($bulk['email'] ?? [])) <= self::SearchVarsLimit) {
                return true;
            }
        }

        return false;
    }
}
