<?php

use Yohns\Core\Config;

include __DIR__.'/../../vendor/autoload.php';

$configDir = __DIR__.'/../../lib/Config';

new Config($configDir);

$ary = Config::getAll('directories');
echo print_r($ary,1);

echo dirname(__DIR__);