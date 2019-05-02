<?php

use StopForumSpam\SearchByEmailHash;

require_once 'bootstrap.php';

# --- Search by email hash (hash is md5 checksum for desired email)

$client = new SearchByEmailHash(md5('shamrykenkokatya@gmail.com'));
$client->withConfidence(); # If you need confidence score

# $client->withUnixTimestamp();
# $client->withExpire(2);

$result = $client->search();

return $result->getBody()->getContents();