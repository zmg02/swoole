<?php

//创建Server对象，监听 127.0.0.1:9501 端口
$server = new Swoole\Server('127.0.0.1', 9501);

$server->set(array(
    // 'reactor_num'   => 2,     // reactor 线程数
    'worker_num'    => 4,     // worker 进程数 =》 cup核数的1-4倍
    'backlog'       => 128,   // listen backlog
    // 'max_request'   => 50,
    // 'dispatch_mode' => 1,
));

//监听连接进入事件
$server->on('Connect', function ($server, $fd, $reactor_id) {
    echo "Client- {$fd} - {$reactor_id}: Connect.\n";
});

//监听数据接收事件
$server->on('Receive', function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "Server: {$data}");
});

//监听连接关闭事件
$server->on('Close', function ($server, $fd) {
    echo "Client-$fd: Close.\n";
});

//启动服务器
$server->start(); 

