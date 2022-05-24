<?php

Co::set(['hook_flags' => SWOOLE_HOOK_ALL | SWOOLE_HOOK_CURL]); //真正的hook所有类型，包括CURL

Co\run(function() {
    for ($i=100; $i--;) { 
        go(function() {//创建100个协程
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379); //此处产生协程调度，cpu切到下一个协程，不会阻塞进程
            $redis->set('key', 'value'); //此处产生协程调度，cpu切到下一个协程，不会阻塞进程
            echo $redis->get('key'); //此处产生协程调度，cpu切到下一个协程，不会阻塞进程
        });
    }
});

var_dump('content');