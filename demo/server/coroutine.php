<?php

use Swoole\Runtime;
use Swoole\Coroutine;
use function Swoole\Coroutine\run;

// 此行代码后，文件操作，sleep，Mysqli，PDO，streams等都变成异步IO，见'一键协程化'章节
Runtime::enableCoroutine();
$s = microtime(true);

// Swoole\Coroutine\run()见'协程容器'章节
run(function() {
    //i just want to sleep...
    for ($c = 100; $c--;) {
        Coroutine::create(function () {
            for ($n = 100; $n--;) {
                usleep(1000);
            }
        });
    }
    // 10k file read and write
    for ($c = 100; $c--;) {
        Coroutine::create(function () use ($c) {
            $tmp_filename = "/tmp/test-{$c}.php";
            for ($n = 100; $n--;) {
                $self = file_get_contents(__FILE__);
                file_put_contents($tmp_filename, $self);
                assert(file_get_contents($tmp_filename) === $self);
            }
            unlink($tmp_filename);
        });
    }
});