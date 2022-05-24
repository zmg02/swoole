<?php

$fileName = __DIR__ . '/data/php.txt';
Swoole\Coroutine\run(function () use ($fileName) {
    $r = Swoole\Coroutine\System::readFile($fileName);
    var_dump($r);
});

$res = Swoole\Coroutine\System::readFile($fileName, function($fileName, $fileContent) {
    echo 'filename:'.$fileName.PHP_EOL;
});
var_dump($res);