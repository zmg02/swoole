<?php

$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

$ws->on('Open', function($ws, $request) {
    $ws->push($request->fd, "连接ws服务端成功\n");
});

$ws->on('Message', function($ws, $frame) {
    echo "消息：{$frame->data}\n";
    $ws->push($frame->fd, "server: {$frame->data}");
});

$ws->on('Close', function($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();