<?php
/**
 * Web 环境下快速响应, fastcgi_finish_request 后面程序由后台执行.
 * (touch tmp.log && chmod 777 tmp.log)
 *
 * fastcgi_finish_request 仅在Web环境下存在, Cli 不存在该函数.
 *
 * @farwish
 */

echo "start ...\n";

fastcgi_finish_request(); // 快速响应浏览器请求, 后面任务进入后台执行.

sleep(3); // 浏览器环境下模拟耗时操作.

date_default_timezone_set('PRC');
file_put_contents("tmp.log", "文件 " . __FILE__ . " 在 " . date('Y-m-d H:i:s') . " 写入");
