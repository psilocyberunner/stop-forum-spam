<?php

use StopForumSpam\SearchByEmail;

require_once 'bootstrap.php';

# --- Search by email address

$client = new SearchByEmail('shamrykenkokatya@gmail.com');
$client->useEuropeRegion(); # If you want to use europe region servers
$client->asJSON();
$client->withConfidence(); # If you need confidence score
$result = $client->search();

return $result->getBody()->getContents();
