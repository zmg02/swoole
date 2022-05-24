<?php

/**
 * HTTP Task Test
 * Task任务没有执行
 */
class HttpController
{

    private $http = null;
    public function __construct($host, $port)
    {
        $this->http = new Swoole\Http\Server($host, $port);
        $this->http->set([
            'enable_static_handler' => true,
            'document_root' => '/www/wwwroot/swoole/swoole/public',
            'task_worker_num' => 4, //设置异步任务的工作进程数量
        ]);
        $this->http->on('request', [$this, 'onRequest']);
        $this->http->on('receive', [$this, 'onReceive']);
        $this->http->on('task', [$this, 'onTask']);
        $this->http->on('finish', [$this, 'onFinish']);
        $this->http->start();
    }

    /**
     * http请求事件
     * @param [type] $request
     * @param [type] $response
     */
    public function onRequest($request, $response)
    {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            $response->end();
            return;
        }
        $response->header('Content-Type', 'text/html; charset=utf-8');
        $response->cookie('author', 'zmg', time()+60*60);
        $fileName = __DIR__ . '/data/http.log';
        $log = json_encode($request);
        // Swoole\Coroutine\run(function() use ($fileName,$log) { //Swoole事件中不能加run方法，加了会报错
            Swoole\Coroutine\System::writeFile($fileName, $log, FILE_APPEND);
        // });
        // list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
        // //根据 $controller, $action 映射到不同的控制器类和方法
        // $controllerName = $controller.'Controller';
        // $controllerPath = $controllerName.'.php';
        // include_once $controllerPath;
        // $result = (new $controllerName)->$action($request, $response);
        $result = 'http server';
        $response->end('result: ' . $result . ' # ' . rand(1000,9999));
    }

    /**
     * 投递异步任务
     * @param [type] $http
     * @param [type] $fd
     * @param [type] $reactorId
     * @param [type] $data
     * @return void
     */
    public function onReceive($http, $fd, $reactorId, $data)
    {
        $taskId = $http->task($data);
        echo "Dispatch AsyncTask: id={$taskId}\n";
    }

    /**
     * 处理异步任务（Task）
     * @param [type] $http
     * @param [type] $taskId
     * @param [type] $reactorId
     * @param [type] $data
     * @return void
     */
    public function onTask($http, $taskId, $reactorId, $data)
    {
        echo "New AsyncTask[id={$taskId}]".PHP_EOL;
        sleep(5);
        //返回任务执行的结果
        $http->finish("{$data} -> OK");
    }

    /**
     * 处理异步任务的结果
     * @param [type] $http
     * @param [type] $taskId
     * @param [type] $data
     * @return void
     */
    public function onFinish($http, $taskId, $data)
    {
        echo "AsyncTask[{$taskId}] Finish: {$data}".PHP_EOL;
    }
    
}

$httpObj = new HttpController('0.0.0.0', '9501');