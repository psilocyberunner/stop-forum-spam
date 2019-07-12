<?php

use StopForumSpam\SubmitSpamReport;

require_once 'bootstrap.php';

# --- Report spam data

$client = new SubmitSpamReport();
$client->setApiToken('token');
$client->setIpAddress('178.159.37.84'); # already listed as spam source
$client->setEmail('test@test.com');
$client->setEvidence('evidence');
$client->setUsername('tester');

$result = $client->submit();

return $result->getStatusCode();