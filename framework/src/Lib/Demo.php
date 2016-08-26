<?php

namespace src\Lib;

class Demo
{
	/**
	 * static 减少内存占用.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * 避免直接new.
	 *
	 * @farwish
	 */
	private function __construct()
	{
	}

	public static function index()
	{
		echo "calling : " . __METHOD__ . "\n";
	}

	/**
	 * 单例.
	 *
	 *
	 */
	public static function getInstance()
	{
		if (! static::$instance) {
			static::$instance = new self();
		}

		return static::$instance;
	}
}
