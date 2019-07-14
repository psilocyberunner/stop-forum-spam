<?php

namespace StopForumSpam;

use StopForumSpam\Exceptions\HttpException;

/**
 * Class SearchByEmailHash
 * No email normalization on the server will be performed in this mode so this method will likely produce false negatives on checks with gmail addresses.
 *
 * @package StopForumSpam
 */
class SearchByEmailHash extends StopForumSpam
{

    /**
     * SearchByEmailHash constructor.
     *
     * @param string $emailHash - md5 of email address
     * @param array  $options
     *
     * @throws HttpException
     */
    public function __construct(string $emailHash, array $options = [])
    {
        parent::__construct($options);

        if (!empty($emailHash)) {
            $this->setOptions(['query' => ['emailhash' => $emailHash]]);
        } else {
            throw new HttpException('Can not use empty string as search criteria');
        }
    }
}
