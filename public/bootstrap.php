<?php

require_once '../vendor/autoload.php';

# Add exception handler, Whoops is a good choice for your experiments
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
