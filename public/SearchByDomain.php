<?php


use StopForumSpam\SearchByDomain;

require_once 'bootstrap.php';

# --- Search by domain

/**
 * Only JSON response available for this type of search as this search is not a part of a native SFS API.
 * No options are available as Timestamps, expires and etc.
 * Here used https://www.stopforumspam.com/downloads/toxic_domains_whole.txt list of domains for search
 */
$client = new SearchByDomain('kinogomyhit.ru');
$result = $client->search();

return $result->getBody()->getContents();
