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
            // 'enable_static_handler' => true,
            // 'document_root' => '/www/wwwroot/swoole/swoole/public',
            'task_worker_num' => 4,
        ]);

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);
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
        $ws->push($request->fd, 'hello,welcome222\n');
    }

    /**
     * 监听ws消息事件
     * @param [type] $ws
     * @param [type] $frame
     */
    public function onMessage($ws, $frame)
    {
        echo "Message: {$frame->data}\n";
        // todo 5s
        $data = [
            'task' => 1,
            'fd'   => $frame->fd,
        ];
        $ws->task($data);
        $ws->push($frame->fd, "Server: {$frame->data}\n");
    }

    /**
     * 处理异步任务（Task）
     * @param [type] $ws
     * @param [type] $taskId
     * @param [type] $reactorId
     * @param [type] $data
     * @return void
     */
    public function onTask($ws, $taskId, $reactorId, $data)
    {
        echo "New AsyncTsak[id={$taskId}]".PHP_EOL;
        // 耗时场景 5s
        sleep(5);
        return json_encode($data);
    }

    /**
     * 处理异步任务的结果
     * @param [type] $ws
     * @param [type] $taskId
     * @param [type] $data
     * @return void
     */
    public function onFinish($ws, $taskId, $data)
    {
        echo "AsyncTask[{$taskId}] Finish: {$data}".PHP_EOL;
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