<?php

$fileName = __DIR__ . '/data/mysql.txt';

Swoole\Coroutine\run(function() use ($fileName) {
    $content = 'varchar与char的区别？'.PHP_EOL;
    $w = Swoole\Coroutine\System::writeFile($fileName, $content, FILE_APPEND);
    var_dump($w);
    echo 'success'.PHP_EOL;
});

echo 'start'.PHP_EOL;