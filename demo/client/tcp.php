<?php

use Swoole\Coroutine\Client;
use function Swoole\Coroutine\run;

run(function () {
    $client = new Client(SWOOLE_SOCK_TCP);
    if (!$client->connect('127.0.0.1', 9501, 0.5))
    {
        echo "连接失败. Error: {$client->errCode}\n";
    }

    // php cli常量
    fwrite(STDOUT, "请输入内容:");
    $msg = trim(fgets(STDIN));

    // 给tcp服务端发送数据
    $client->send("$msg\n");
    // 接收tcp服务端数据
    echo $client->recv();
    // 关闭客户端
    $client->close();
});

