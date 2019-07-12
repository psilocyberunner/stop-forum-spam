<?php


use StopForumSpam\SearchByDomain;

require_once 'bootstrap.php';

# --- Search by email address

$client = new SearchByDomain('@kinogomyhit.ru');
$client->asJSON();
$client->withConfidence(); # If you need confidence score
$result = $client->search();

# When a query results in a blacklist result, the frequncy field will be a value of 255, and the lastseen date will update to the current time (UTC)
return $result->getBody()->getContents();