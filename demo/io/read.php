<?php

$r = Swoole\Coroutine\System::readFile(__DIR__ . '/data/php.txt' . $fileName);

var_dump($r);