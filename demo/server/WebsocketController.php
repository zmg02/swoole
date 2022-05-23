<?php

class WebsocketController
{
    const HOST = '0.0.0.0';
    const PORT = '9502';
    public $ws = null;

    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\server('0.0.0.0', '9502');

        $this->ws->set([
            'enable_static_handler' => true,
            'document_root' => '/www/wwwroot/swoole/swoole/public',
        ]);

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param [type] $ws
     * @param [type] $request
     */
    public function onOpen($ws, $request)
    {
        $ws->push($request->fd, 'hello,welcome\n');
    }

    /**
     * 监听ws消息事件
     * @param [type] $ws
     * @param [type] $frame
     */
    public function onMessage($ws, $frame)
    {
        echo "Message: {$frame->data}\n";
        $ws->push($frame->fd, "Server: {$frame->data}\n");
    }

    /**
     * 监听ws关闭事件
     * @param [type] $ws
     * @param [type] $fd
     */
    public function onClose($ws, $fd)
    {
        echo "client-{$fd}: is closed\n";
    }

}


$wsObj = new WebsocketController();