<?php
/*----------------------------------
 | Cli Tasker (not support web env)
 | @farwish MIT License
 |----------------------------------
 */
class Tasker
{
	protected static $sec;

	public function __construct($sec)
	{
		if (! function_exists("pcntl_signal")) {
			die("pcntl_signal function not exists!\n");
		}

		self::$sec = $sec;

		self::run();
	}

	private static function run()
	{
		declare(ticks = 1);
	}
	
	// 传入函数作为任务
	public function task($callback = '')
	{
		pcntl_alarm(self::$sec);
		$callback();
	}

	// 自定义超时处理方式
	public function handle($callback = '')
	{
		pcntl_signal(SIGALRM, ($callback ? $callback() : function() {
			echo "超时!";die;
		}));
	}
}

// Example:

$start_time = time();

function task($num = 10000 * 10000 * 10)
{
	$j = 0;
	for ($i = 0; $i < $num/10; $i++) {
		$j++;
	}
}

$tasker = new Tasker(5);

$tasker->handle();

$tasker->task("task");

$end_time = time();

$spend_time = $end_time - $start_time;

echo "process time : {$spend_time}\n";
