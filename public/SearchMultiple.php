<?php

use StopForumSpam\SearchMultiple;

require_once 'bootstrap.php';

# --- Search multiple targets at once

$client = new SearchMultiple([
    'email'    => 'test@test.tld',
    'ip'       => '77.111.247.62',
    'username' => 'c0dex',
]);
$client->withConfidence(); # If you need confidence score

$result = $client->search();

return $result->getBody()->getContents();