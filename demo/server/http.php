<?php

    $http = new Swoole\Http\Server('0.0.0.0', 9501);

    $http->set([
        'enable_static_handler' => true,
        'document_root' => '/www/wwwroot/swoole/swoole/public',
    ]);

    $http->on('Request', function ($request, $response) {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            $response->end();
            return;
        }
        $response->header('Content-Type', 'text/html; charset=utf-8');
        $response->cookie('address', 'xiamen', time()+60*10);
        $fileName = __DIR__ . '/data/http.log';
        $log = [
            'data:' => date('Y-m-d H:i:s'),
            'get:'  => $request->get,
            'post:'  => $request->post,
            'header:'  => $request->header,
        ];
        $log = json_encode($log) . PHP_EOL;
        Swoole\Coroutine\run(function() use ($fileName,$log) {
            Swoole\Coroutine\System::writeFile($fileName, $log, FILE_APPEND);
        });
        // $response->end('sss. #' . json_encode($request->get) . rand(1000,9999));

        // list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
        // //根据 $controller, $action 映射到不同的控制器类和方法
        // $controllerName = $controller.'Controller';
        // $controllerPath = $controllerName.'.php';
        // include_once $controllerPath;
        // $result = (new $controllerName)->$action($request, $response);
        $result = 'http server';
        $response->end('result: ' . $result . ' # ' . rand(1000,9999));
    });

    $http->start();
