<?php

$fileName = __DIR__ . '/data/php.txt';
Swoole\Coroutine\run(function () use ($fileName) {
    $r = Swoole\Coroutine\System::readFile($fileName);
    var_dump($r);
});
