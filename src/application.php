<?php

use noip\Commands\NoIpCommand;
use Symfony\Component\Console\Application;

$autoLoader = require_once __DIR__ .'/../vendor/autoload.php';

$command = new NoIpCommand();

$application = new Application();
$application->add($command);
$application->run();


