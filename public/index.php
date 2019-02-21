<?php

/**
 * Example usage below
 */

require_once '../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

# ---

$client = (new \StopForumSpam\SearchMultiple([
    'email'    => 'test@test.tld',
    'ip'       => '77.111.247.62',
    'username' => 'c0dex',
]));
$client->withConfidence();

$result = $client->search();

dd(json_decode($result->getBody()));

# ---

$client = (new \StopForumSpam\SearchByEmailHash(md5('shamrykenkokatya@gmail.com')));
$client->withConfidence();
//$client->withUnixTimestamp();
$client->withExpire(2);

$result = $client->search();

dd(json_decode($result->getBody()));

# ---

$client = (new \StopForumSpam\SearchByUsername('Nicole'));
$client->asJSON();
$client->withConfidence();

$result = $client->search();

dd(json_decode($result->getBody()));

# ---

$client = (new \StopForumSpam\SearchByIp('77.111.247.62'));
$client->asJSON();
$client->withConfidence();

$result = $client->search();

dd(json_decode($result->getBody()));

# ---

$client = (new \StopForumSpam\SearchByEmail('shamrykenkokatya@gmail.com'));
$client->asJSON();
$client->withConfidence();
//dd($client);
$result = $client->search();
//$client->asJSON();
//$result = $client->search();

dd(json_decode($result->getBody()));


