<?php

/**
 * 多进程任务处理.
 *
 * @author farwish Apache-License
 *
 * <code>
 * // 原始数据
 * $data = [1, 2, 3, 4, 5];
 *
 * // 分组数据量
 * $chunk_size = 2;
 *
 * // 执行分组
 * $data_group = array_chunk($data, 2);
 *
 * // 所需进程数
 * $worker_num = count($data_group);
 *
 * // 执行逻辑
 * deal($worker_num, function($worker_idx, $data_group) {
 *    $current_data_group = $data_group[$worker_idx];
 *    print_r($current_data_group);
 * }, $data_group);
 * </code>
 *
 * @param int       $worker_num 进程数
 * @param callable  $callback   回调函数(共两个参数：第一个参数是进程索引号, 第二个参数$param_arr)
 * @param array     $param_arr  回调函数的参数(如：分组后的数据)
 *
 * @return mixed
 */
function deal(int $worker_num = 1, callable $callback, array $param_arr)
{
    if (! extension_loaded('pcntl')) {
        throw new \Exception('Pcntl extension missed!');
    }

    if (! $worker_num) {
        throw new \Exception("Worker_num can't be zero!");
    }

    $pids = [];

    for ($i = 0; $i < $worker_num; $i++) 
    {
        $pid = pcntl_fork();

        switch ($pid) {
            case -1:
                throw new \Exception('Fork fail!');
                break;
            case 0:
                // child
                call_user_func_array($callback, [$i, $param_arr]); 
                die;
                break;
            default:
                // parent: $pid > 0
                $pids[$pid] = $pid;
                break;
        }
    }

    // `man 2 wait` for more comment.
    foreach ($pids as $pid) 
    {
        if ($pid) {
            $status = 0;

            // Wait for process to change state ( 3 situation ).
            // Perform a wait allows the system to release the resources associated with the child.
            pcntl_waitpid($pid, $status);
        }
    }
}
