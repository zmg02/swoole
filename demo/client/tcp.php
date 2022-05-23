<?php

$client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);

if (!$client->connect('127.0.0.1', 9501)) exit('连接失败！');

// php cli常量
fwrite(STDOUT, "请输入内容:");
$msg = trim(fgets(STDIN));

// 给tcp服务端发送数据
$client->send($msg);
// 接收tcp服务端数据
echo $client->recv();
// 关闭客户端
$client->close();

