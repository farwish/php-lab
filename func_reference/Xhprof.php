<?php
/**
 * Xhprof 非侵入式使用指南.
 *
 * 操作步骤：
 * 1. 装好 xhprof (pecl install xhprof-0.9.2)，php.ini 加入 extension=xhprof.so 
 * 2. 把 本文件、xhprof源代码中xhprof_html目录、xhprof_lib目录，三者放在web可访问根目录.
 * 3. 配置 php.ini 加入以下部分，并重启 php-fpm:
 *      auto_prepend_file=/home/www/Xhprof.php
 *
 *    ( 如果不改php.ini，可以改nginx配置，在访问php的区间段加入： )
 *    ( fastcgi_param PHP_VALUE "auto_prepend_file=/home/www/Xhprof.php"; )
 *
 * 4. 修改程序 configure 部分，一般情况需要改 $xhprof_lib_path 和 $supervise['urls']；
 * 5. 访问你的任何web应用.
 * 6. 查看run_id：tail /tmp/xhprof.log
 * 7. 查看效果：http://localhost/xhprof_html/?run=58468d515bddd&sort=mu&source=xhprof_testing
 *      用run_id替换地址中的run.
 *
 * NOTICE：
 *      如需要自定义，请查看和改动程序.
 * 
 * @farwish.com BSD-License
 */

$start_time = microtime(true);

/* ==================== configure =================== */

// 开启
$xhprof_enable = true;

// 报警阀值 'ms
$threshold_time = 100;

// xhprof_lib SDK目录, 必须和 xhprof_html 所属同一层上级目录，除非更改xhprof_html内文件.
$xhprof_lib_path = '/home/www/xhprof_lib/';

// 存放 xhprof 分析数据的目录，xhprof 默认也是/tmp
// 如果自定义，需要相应 xhprof_html/index.php 传参 new XHProfRuns_Default('/tmp/xxxx')
$xhprof_data_dir = '/tmp';

// 应用的 run_id 记录位置
$xhprof_log_file = '/tmp/xhprof.log';

// 监测的url
$supervise['urls'] = [
    '/frontend/index/questions' => $threshold_time,
];

// 不需要监测的url
$noneeded = [
    '/xhprof_html/',
];

/* ================================================== */

$pure_request_uri = isset($_SERVER['REQUEST_URI']) ?
    explode('?', $_SERVER['REQUEST_URI'])[0] : ' ';

$threshold_time = isset($supervise['urls'][$pure_request_uri]) ?
    $supervise['urls'][$pure_request_uri] : $threshold_time;

if (! function_exists('xhprof_save_data')) {
    function xhprof_save_data()
    {
        global $xhprof_enable,
            $xhprof_lib_path,
            $xhprof_data_dir,
            $xhprof_log_file,
            $start_time,
            $pure_request_uri,
            $threshold_time;

        $end_time = microtime(true);
        $cost_time = ($end_time - $start_time) * 1000;

        if ( $xhprof_enable ) {
            include_once $xhprof_lib_path . "utils/xhprof_lib.php";
            include_once $xhprof_lib_path . "utils/xhprof_runs.php";

            $xhprof_data = xhprof_disable();

            if (! is_dir($xhprof_data_dir)) mkdir($xhprof_data_dir);
            if (! file_exists($xhprof_log_file)) shell_exec(`touch /tmp/xhprof.log`);

            $run_obj = new XHProfRuns_Default($xhprof_data_dir);
            $run_id = $run_obj->save_run($xhprof_data, 'xhprof_testing');
            $log_data = "[cost_time]: {$cost_time} [run_id]: {$run_id} [request_uri] {$pure_request_uri}\n";

            // 并发情况写入mysql.
            file_put_contents($xhprof_log_file, $log_data, FILE_APPEND);
        }
    }
}

if ( $xhprof_enable && isset($threshold_time) && ! in_array($pure_request_uri, $noneeded) ) {
    // xhprof 在 php > 5.5 平台上为避免502时的写法，https://bugs.php.net/bug.php?id=65345
    xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
    register_shutdown_function("xhprof_save_data");
}
