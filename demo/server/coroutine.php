<?php

// $pdo = new PDO('mysql:host=101.33.235.45;dbname=test_2022;charset=utf8', 'root', '91haoxue2022');
// $statement = $pdo->prepare('SELECT * FROM `user`');
// $statement->execute();
// echo count($statement->fetchAll());
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->set('key', 'value');
echo $redis->get('key');
exit;

use Swoole\Runtime;
use Swoole\Coroutine;
use function Swoole\Coroutine\run;

// 此行代码后，文件操作，sleep，Mysqli，PDO，streams等都变成异步IO，见'一键协程化'章节
Runtime::enableCoroutine();
$s = microtime(true);

// Swoole\Coroutine\run()见'协程容器'章节
run(function() {
    //i just want to sleep...
    echo 'sleep'.PHP_EOL;
    for ($c = 100; $c--;) {
        Coroutine::create(function () {
            for ($n = 100; $n--;) {
                usleep(1000);
            }
        });
    }
    // 10k file read and write
    echo 'io'.PHP_EOL;
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
    // 10k pdo and mysqli read
    echo 'pdo'.PHP_EOL;
    for ($c = 50; $c--;) {
        Coroutine::create(function () {
            $pdo = new PDO('mysql:host=101.33.235.45;dbname=test_2022;charset=utf8', 'root', '91haoxue2022');
            $statement = $pdo->prepare('SELECT * FROM `user`');
            for ($n = 100; $n--;) {
                $statement->execute();
                echo count($statement->fetchAll());
                assert(count($statement->fetchAll()) > 0);
            }
        });
    }
});