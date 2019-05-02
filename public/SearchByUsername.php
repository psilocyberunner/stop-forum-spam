<?php

use StopForumSpam\SearchByUsername;

require_once 'bootstrap.php';

# --- Search by user name

$client = new SearchByUsername('Nicole');
$client->asJSON();
$client->withConfidence(); # If you need confidence score
$result = $client->search();

return $result->getBody()->getContents();