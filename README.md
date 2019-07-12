# stop-forum-spam
PHP wrapper for https://www.stopforumspam.com/usage web service API as a standalone library.


**Installation**

Clone this repository

`git clone git@github.com:psilocyberunner/stop-forum-spam.git`

Or require package via composer

`composer require psilocyberunner/stop-forum-spam`


### Usage

First of all - bootstrap the application (see public/bootstrap.php for details)

```php
<?php
require_once '../vendor/autoload.php';

# Add exception handler, Whoops is a good choice for your experiments
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
```

#### Search by email

```php
<?php

use StopForumSpam\SearchByEmail;

require_once 'bootstrap.php';

# --- Search by email address

$client = new SearchByEmail('shamrykenkokatya@gmail.com');
$client->asJSON();
$client->withConfidence(); # If you need confidence score
/** @var Psr\Http\Message\ResponseInterface $result */
$result = $client->search();

return $result->getBody()->getContents();
```

Example response

```json
{
    "success": 1,
    "email": {
        "lastseen": "2019-03-24 14:19:15",
        "frequency": 5027,
        "appears": 1,
        "confidence": 97.01
    }
}
```

#### Search by email hash

_Email hash is plain md5 checksum for email address_

```php
<?php

use StopForumSpam\SearchByEmailHash;

require_once 'bootstrap.php';

# --- Search by email hash (hash is md5 checksum for desired email)

$client = new SearchByEmailHash(md5('shamrykenkokatya@gmail.com'));
$client->withConfidence(); # If you need confidence score

$result = $client->search();

return $result->getBody()->getContents();
```

Example response

```json
{
    "success": 1,
    "emailhash": {
        "lastseen": "2019-03-24 14:19:15",
        "frequency": 5027,
        "appears": 1,
        "confidence": 97.01
    }
}
```

#### Search by ip

```php
<?php

use StopForumSpam\SearchByIp;

require_once 'bootstrap.php';

# --- Search by ip address

$client = new SearchByIp('77.111.247.62');
$client->asJSON();
$client->withConfidence(); # If you need confidence score

$result = $client->search();

return $result->getBody()->getContents();
```

Example response

```json
{
    "success": 1,
    "ip": {
        "lastseen": "2018-10-02 11:48:41",
        "frequency": 8,
        "appears": 1,
        "confidence": 0.46,
        "delegated": "fr",
        "country": "fr",
        "asn": 205016
    }
}
```

#### Search by username

```php
<?php

use StopForumSpam\SearchByUsername;

require_once 'bootstrap.php';

# --- Search by user name

$client = new SearchByUsername('Nicole');
$client->asJSON();
$client->withConfidence(); # If you need confidence score
$result = $client->search();

return $result->getBody()->getContents();
```

Example response

```json
{
    "success": 1,
    "username": {
        "lastseen": "2019-03-04 02:07:58",
        "frequency": 12,
        "appears": 1,
        "confidence": 3.01
    }
}
```

#### Multiple search parameters

```php
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
```

Example response

```json
{
    "success": 1,
    "username": {
        "frequency": 0,
        "appears": 0
    },
    "email": {
        "frequency": 0,
        "appears": 0
    },
    "ip": {
        "lastseen": "2018-10-02 11:48:41",
        "frequency": 8,
        "appears": 1,
        "confidence": 0.46,
        "delegated": "fr",
        "country": "fr",
        "asn": 205016
    }
}
```

#### Some useful methods

Method call **withConfidence()** will include in response additional info about confidence score

`
$client->withConfidence();
`

Method call **withUnixTimestamp()** will return data/time results as UNIXTIME format, that being seconds since Unix epoch

`
$client->withUnixTimestamp();
`

Method call **withExpire(10)** provides the ability to have API results filtered if the lastseen date is older than the age (in days).

`
$client->withExpire(10);
`

Method call **asJSONP('function name')** provide ajax/jquery support with JSONP, which allows for a callback function to be specified around a json result.

`
$client->asJSONP('test');
`

Response example

`test({"success":1,"email":{"lastseen":"2019-03-24 14:19:15","frequency":5027,"appears":1,"confidence":97.01}})`


#### Future plans

Add Wildcards disable logic

Add submit spam data logic

Add bulk search logic (?) 

Add xmlcdata (?) 

Add xmldom (?)

