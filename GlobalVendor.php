<?php

/**
 * 全局使用 composer 包的步骤:
 *
 * 1. 建一个包含所有需要全局使用的composer包的项目。
 *      如：globalVendor/
 *      composer init 初始化
 *      composer install 安装 
 *
 * 2. php.ini中指定 auto_prepend_file = '/path/to/globalVendor/vendor/autoload.php'
 *      重启 fpm, 那么之后所有php项目在访问前，都将 require 上面的文件，做到了全局安装。
 *
 * @author farwish <farwish@foxmail.com>
 */

#echo \Alcon\Supports\Codes::ACTION_FAL . PHP_EOL;

