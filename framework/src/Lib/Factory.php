<?php

namespace src\Lib;

class Factory
{
	/**
	 * 对象统一实例化, 需要库自己getInstance返回.
	 *
	 * \src\Lib\Factory::create('\src\Lib\Demo')
	 *
	 * @farwish
	 */
	public static function create($class)
	{
		return $class::getInstance();
	}
}
