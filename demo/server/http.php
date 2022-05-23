<?php

    $http = new Swoole\Http\Server('0.0.0.0', 9501);

    $http->on('Request', function ($request, $response) {
        $response->header('Content-Type', 'text/html; charset=utf-8');
        $response->cookie('address', 'xiamen', time()+60*10);
        $response->end('sss. #' . json_encode($request->get) . rand(1000,9999));
    });

    $http->start();
