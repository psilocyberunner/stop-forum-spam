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

    /**
     * If you wish to ignore the listing for any known Tor exit node, then include the notorexit parameter in the request.
     * Any IP address listed that is known as a Tor exit node will return a frequency of 0.
     *
     * @return $this
     * @throws HttpException
     */
    public function withNoTorExit()
    {
        $this->setOptions(['query' => ['notorexit' => true]]);

        return $this;
    }

    /**
     * Some administrators may wish to block a known Tor exit node regardless of it's listing.
     * A result for an IP that is recorded as a Tor exit node will return a frequency of 255 regardless of being listed or not.
     *
     * @return $this
     * @throws HttpException
     */
    public function withBadTorExit()
    {
        $this->setOptions(['query' => ['badtorexit' => true]]);

        return $this;
    }
}
