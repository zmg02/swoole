<?php

use Swoole\Coroutine\Client;
use function Swoole\Coroutine\run;

run(function () {
    $client = new Client(SWOOLE_SOCK_UDP);
    if (!$client->connect('127.0.0.1', 9502, 0.5)) {
        echo "连接失败. Error: {$client->errCode}\n";
    }
    // php cli常量
    fwrite(STDOUT, "请输入内容:");
    $msg = trim(fgets(STDIN));

    $client->send("$msg\n");
    while (true) {
        $data = $client->recv();
        if (strlen($data) > 0) {
            echo $data;
            $client->send(time() . PHP_EOL);
        } else {
            if ($data === '') {
                // 全等于空 直接关闭连接
                $client->close();
                break;
            } else {
                if ($data === false) {
                    // 可以自行根据业务逻辑和错误码进行处理，例如：
                    // 如果超时时则不关闭连接，其他情况直接关闭连接
                    if ($client->errCode !== SOCKET_ETIMEDOUT) {
                        $client->close();
                        break;
                    }
                } else {
                    $client->close();
                    break;
                }
            }
        }
        \Co::sleep(1);
    }
});
