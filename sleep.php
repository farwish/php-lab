<?php
/**
 * Useage:
 *
 * php sleep.php
 * OR
 * php exec_nohup.php
 *
 * sleep 在Web环境(php-fpm)会等待所有执行完毕, 最后一次输出.
 * 
 * @farwish
 */

for ($i = 0; $i < 5; $i ++) {
	echo $i . "\n";
	sleep(1);
}
