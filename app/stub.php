#!/usr/bin/env php
<?php

Phar::mapPhar('phrekyll.phar');
require_once 'phar://phrekyll.phar/Phrekyll/Autoloader.php';

$loader = Phrekyll\Autoloader::getInstance();
$runner = new Phrekyll\Runner\CommandLine($loader);
$runner->run();

unset($runner, $loader);
__HALT_COMPILER();

