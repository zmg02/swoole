<?php

//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

//监听WebSocket连接打开事件
$ws->on('Open', function ($ws, $request) {
    $ws->push($request->fd, "hello, welcome\n");
    if ($request->fd == 1) {
        //设置一个间隔时钟定时器。
        Swoole\Timer::tick(2000, function($timerId) {
            echo "2s: timerId:{$timerId}\n";
        });
    }
});

//监听WebSocket消息事件
$ws->on('Message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    //在指定的时间后执行函数。
    Swoole\Timer::after(5000, function() use ($ws, $frame) {
        echo "5s-after\n";
        $ws->push($frame->fd, "server time after: ".date('Y-m-d H:i:s'));
    });
    $ws->push($frame->fd, "server: {$frame->data}");
});

//监听WebSocket连接关闭事件
$ws->on('Close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();