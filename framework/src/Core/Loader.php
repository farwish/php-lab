<?php

namespace src\Core;

class Loader
{
	/**
	 * PSR-0
	 * used in apl_autoload_register('\src\Core\Loader::autoload').
	 *
	 * when calling \src\Lib\Demo::index(), $class is src\Lib\Demo.
	 *
	 * @farwish
	 */
	public static function autoload($class)
	{
		require BASEPATH . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	}
}
