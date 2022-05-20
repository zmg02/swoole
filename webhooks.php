<?php

$requestBody = file_get_contents("php://input");
if (empty($requestBody)) exit('data null!');

$content = json_decode($requestBody, true);

// 验证 webhooks 配置的 secret，也可以不验证
// if (empty($content['password']) || $content['password'] != '123456') {
//     exit('password error!');
// }

// 判断主分支上是否有提交
if ($content['ref'] == 'refs/heads/main') {
    // 项目存放的物理路径，也就是站点的访问地址
    $path = '/www/wwwroot/swoole/swoole/';

    // 执行脚本 git pull，拉取分支最新代码
    $res = shell_exec("cd {$path} && git pull origin main 2>&1"); //当前为www用户

    // 记录日志
    $res_log = "____________________Time: ". date('Y-m-d H:i:s', time());
    $res_log .= "用户：" . $content['pusher']['name'] . ',项目：'. $content['repository']['name'] . ',分支：' . $content['ref'] . 'PUSH: ' . $content['commits'][0]['message'] . PHP_EOL; 
    $res_log .= $res . PHP_EOL;

    file_put_contents('webhooks.log', $res_log, FILE_APPEND);
}

echo 'done';