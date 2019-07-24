<?php

use StopForumSpam\SearchByIp;

require_once 'bootstrap.php';

# --- Search by ip address

$client = new SearchByIp('77.111.247.62');
$client->useUSRegion(); # If you want to use US region server
$client->asJSON();
$client->withConfidence(); # If you need confidence score

$result = $client->search();

return $result->getBody()->getContents();
