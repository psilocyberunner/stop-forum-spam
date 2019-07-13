<?php

use StopForumSpam\SearchByBulk;

require_once 'bootstrap.php';

# --- Search multiple targets at once

$client = new SearchByBulk([
    'ip'       => [
        '127.0.0.1',
        '77.111.247.62',
    ],
    'username' => [
        'Nicole',
        'some-random-username-for-test',
    ],
    'email'    => [
        'shamrykenkokatya@gmail.com',
        'some-email@test.tld',
    ],
]);
$client->withConfidence(); # If you need confidence score

$result = $client->search();

return $result->getBody()->getContents();